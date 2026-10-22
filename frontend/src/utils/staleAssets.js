// After a deploy, a tab opened before it still points at old hashed JS files that
// no longer exist. Loading one fails; a reload fetches the new index.html.
const KEY = 'cookslate:stale-reload';
const STALE_ASSET_ERROR =
  /dynamically imported module|Importing a module script failed|Unable to preload CSS/i;

export function isStaleAssetError(error) {
  return STALE_ASSET_ERROR.test(String(error?.message || error || ''));
}

// Reloads at most once per 30s so a genuinely broken deploy can't loop.
export function reloadForNewVersion() {
  try {
    const last = Number(sessionStorage.getItem(KEY) || 0);
    if (Date.now() - last < 30000) return false;
    sessionStorage.setItem(KEY, String(Date.now()));
  } catch {
    return false;
  }
  window.location.reload();
  return true;
}
