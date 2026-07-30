## Decision: AUTO-030 (household permissions) will not add a `households` table — a Cookslate instance already IS the household. Scope: add a `viewer` role, and widen list/read visibility on collections, meal plans, grocery lists, and pantry to all users on the instance, while keeping edit/delete creator-or-admin-only (unchanged). Recipes, favorites, ratings, cook log, shopping trips, and API keys are unaffected.

## Context
AUTO-030 was imported from Forgejo issue #82 as "extend the binary admin/member
role model with finer-grained sharing (e.g. a viewer/cook role)." It was
deliberately left unbuilt (Roadmap v3) pending a scoping discussion given its
apparent blast radius — every ownership check across RecipeController/
CollectionController looked like it would need to change.

Research (two Explore-agent passes) found the actual current state:
- No `households` table or `household_id` column exists anywhere. The
  "Household" pricing tier (`license.php::maxUsers() = 5`) is purely a
  seat-count cap on one instance's `users` table — not a grouping mechanism.
  Since Cookslate is self-hosted per-family (one instance per household), all
  users on an instance already function as one household.
- Recipes are already visible instance-wide unless `is_private`, with
  edit/delete gated to creator-or-admin via `Recipe::isCreator()`.
- Collections, meal plans, grocery lists, and pantry are currently strictly
  siloed per-user (`WHERE created_by/user_id = ?` on every list/read query) —
  no visibility to other users on the same instance at all.
- Favorites, ratings, cook log, shopping trip history, and API keys are
  inherently personal (my favorite, my receipt, my key) and were never in
  scope for sharing.

## Alternatives considered
1. Build a real multi-household model (`households` table, `household_id` on
   `users`, invite flow) to support multiple households per instance.
2. Treat "instance = household" (no new table), widen visibility on the 4
   siloed resource types, add a `viewer` role, leave edit rights unchanged.
3. Same as (2) but also let any member edit/delete any other member's shared
   items (true collaborative editing, not just shared visibility).
4. Extend the same shared-edit model to recipes too, for full consistency.

## Reasoning (why option 2 won)
- **Self-hosted architecture already provides the boundary.** Cookslate has
  no multi-tenant concept and isn't being built toward one — a `households`
  table (option 1) would model a relationship that will only ever have one
  row in 99% of deployments, pure speculative generality for a feature nobody
  asked for (multi-household-per-instance).
- **Matches the actual competitive ask.** The Tandoor-style "granular
  permissions" gap this was scoped against is about read/write role
  granularity (viewer vs. editor), not multi-tenancy — option 2 delivers that
  directly.
- **Smaller, safer blast radius.** Only 4 models' list queries change
  (drop the ownership filter), plus one new role-gate reused across
  mutating endpoints. No ownership-check logic itself changes — creator/
  admin-only edit rights are untouched, so there's no risk of one member's
  edits clobbering another's data unexpectedly.
- **Recipes are deliberately left untouched (declined option 4).** Recipes
  already work exactly like the new shared-collections/meal-plan/grocery/
  pantry model (shared view, creator/admin edit) — no reason to touch
  working code. Extending shared *editing* to recipes (option 4) or to
  collections/etc. (option 3) was explicitly declined: existing users may
  have recipes/lists they don't expect others to be able to modify, and
  "shared to view, not to edit" is the safer default that still solves the
  stated problem (a family wants to see the shared meal plan / pantry / list,
  not necessarily co-edit anyone else's item without attribution).

## Trade-offs accepted
- No true collaborative editing (e.g. any member checking off any grocery
  item) in this pass — only the creator or an admin can edit/delete a shared
  item. If real-time co-editing turns out to matter in practice, that's a
  clear, separate follow-up (option 3) rather than blocking this pass.
- If Cookslate ever needs multi-household-per-instance (e.g. a future hosted
  SaaS tier), this decision will need to be revisited and a real `households`
  table introduced then — deferred rather than solved speculatively now.
- Viewer role is instance-wide only (a user is a viewer everywhere or nowhere)
  — no per-resource viewer grants in this pass.

## Supersedes
N/A — first household/permissions decision.

## Revisit if
- Cookslate adds a hosted SaaS tier where multiple independent households
  could share one instance/database (would need a real `households` table).
- Users request true collaborative editing on shared collections/meal-plans/
  grocery-lists/pantry (would extend to option 3).
