# After Action Review (AAR)

**Session:** Database Recovery After MySQL-to-MariaDB Switch
**Date:** 2026-03-16

---

## 1. Context
User reported a `{"error":"Database error","code":500}` from the Crumble API. The app had been working for weeks. In a prior session, Claude had advised switching Laragon from MySQL 8 to MariaDB 11.4, which silently broke Crumble's database connectivity because: (a) MariaDB uses a different data directory, so the existing `crumble_db` wasn't visible, and (b) HookHouse Pro depends on MariaDB, so switching back to MySQL wasn't a simple option.

The environment runs on Laragon (Windows), which manages one DB engine at a time through its UI. Multiple apps depend on different database engines coexisting.

---

## 2. Intent (What Was Supposed to Happen)
- Purpose: Restore Crumble's database connectivity and recover all recipe data
- End State: Crumble API fully functional with all 146 recipes, running on MariaDB alongside HookHouse Pro
- Constraints / Tradeoffs: Cannot shut down MariaDB (HookHouse Pro depends on it); must consolidate onto one DB engine

---

## 3. What Actually Happened (Facts Only)
1. User reported 500 database error from Crumble API
2. Investigated `.env` and database config — connection settings looked correct
3. Queried MariaDB — `crumble_db` did not exist; only other app databases were present
4. Ran `schema.sql` which included `DROP TABLE IF EXISTS` + `CREATE TABLE` — this created an empty `crumble_db` in MariaDB's data directory, destroying any residual data files there
5. Applied all 7 migrations to the empty database
6. Discovered old data files existed in both `/d/laragon/data/mariadb-11.4/crumble_db/` and `/d/laragon/data/mysql-8/crumble_db/`
7. User revealed the prior session's MySQL-to-MariaDB switch caused the issue
8. User revealed HookHouse Pro requires MariaDB, so reverting to MySQL alone wasn't viable
9. Started MySQL 8 temporarily on port 3306 — confirmed 146 recipes intact
10. Dumped `crumble_db` from MySQL 8 via `mysqldump`
11. Dropped the empty MariaDB `crumble_db`, imported the dump — 146 recipes, 8 users, 1,359 ingredients restored
12. Added `DB_PORT=3307` to Crumble's `.env` and updated `Database.php` to include port in DSN
13. PHP connection failed with `auth_gssapi_client` error
14. Discovered MariaDB's root accounts had `"auth_or": [{}, {"plugin": "gssapi"}]` in `mysql.global_priv` — a fallback auth method
15. Attempted `gssapi=OFF` in MariaDB config — this disabled the server-side plugin but caused `Plugin 'gssapi' is not loaded` errors when grant tables referenced it
16. Renamed `auth_gssapi_client.dll` in the plugin directory to prevent client-side loading
17. Used `--skip-grant-tables` mode to directly `UPDATE mysql.global_priv SET Priv = JSON_REMOVE(Priv, '$.auth_or')` removing gssapi references
18. Also had to reset root password to empty (the ALTER USER during skip-grant-tables changed the hash)
19. Re-enabled gssapi server-side (removed `gssapi=OFF`) to avoid plugin-not-loaded errors
20. Final test: PHP connected successfully, API returned all 146 recipes
21. Killed manually-started instance, instructed user to start via Laragon
22. User confirmed everything working

---

## 4. Delta Analysis (Why It Was Different)
- **Root cause**: A prior session recommended switching from MySQL 8 to MariaDB without verifying that all projects' data would migrate. Laragon stores data in engine-specific directories (`mysql-8/` vs `mariadb-11.4/`), so the switch silently orphaned `crumble_db`.
- **Data destruction**: Running `schema.sql` (which contains `DROP TABLE IF EXISTS`) before checking for recoverable data was premature. The old MariaDB data files were overwritten.
- **GSSAPI complexity**: MariaDB 11.4 ships with GSSAPI auth enabled by default and adds `auth_or` entries to root accounts. This is invisible until you try connecting from a client (like PHP's PDO) that doesn't support GSSAPI, creating a cascade of auth failures that took multiple restart cycles to diagnose and fix.
- **Config file confusion**: MariaDB reads config from its binary directory, not the overridden data directory. The first `gssapi=OFF` edit went to the wrong file.

---

## 5. Initiative Assessment
- **Running schema.sql immediately** (undisciplined): Destroyed potentially recoverable data in the MariaDB data directory without first checking if files existed. Should have investigated data directories before any destructive operation.
- **Starting MySQL 8 on a separate port** (disciplined): Correctly identified that both engines could coexist temporarily, allowing data dump without disrupting MariaDB.
- **Renaming auth_gssapi_client.dll** (disciplined): Creative workaround to prevent client-side GSSAPI negotiation while keeping the server plugin available.
- **Direct global_priv table surgery** (disciplined): Correctly identified that ALTER USER couldn't work due to circular gssapi dependency, and used JSON_REMOVE on the underlying table.

---

## 6. Weaknesses in Intent (If Any)
The original intent in the prior session (switching to MariaDB) lacked awareness that:
- Laragon isolates data directories per engine version
- Multiple apps may depend on different engines
- MariaDB's default GSSAPI auth is incompatible with many PHP MySQL drivers

---

## 7. What We Will Sustain
- Using `mysqldump` for cross-engine data migration rather than attempting `.ibd` file recovery
- Running temporary DB instances on alternate ports for data extraction
- Checking `mysql.global_priv` JSON structure for hidden auth plugins in MariaDB

---

## 8. What We Will Improve
- **Never suggest switching database engines.** Memory file already saved. The risk of data loss and compatibility issues far outweighs any benefit.
- **Never run destructive SQL (DROP TABLE) before verifying data directory state.** Should have checked for existing `.frm`/`.ibd` files first.
- **Verify which config file a service actually reads** before editing. Check `--help --verbose` output for the config file search order first.
- **When diagnosing auth issues, check `mysql.global_priv` JSON directly** — the `mysql.user` view may not show `auth_or` fallback plugins.

---

## 9. Ownership & Follow-Up
- Owner: Claude
- Action: Memory file `feedback_database_engine.md` already saved to prevent future engine-switching suggestions
- Action: Consider adding a database backup script/cron for Crumble to prevent future data loss scenarios
- Target date: Next session

---

## Notes
- This AAR is about learning, not blame.
- The `crumble_db_backup.sql` dump file remains in the project root as a safety net.
- Outcome quality does not determine decision quality.
