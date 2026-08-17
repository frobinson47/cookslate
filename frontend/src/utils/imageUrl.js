/**
 * Build image URLs from the stored image_path (e.g., "recipes/10/full.webp" or legacy "recipes/10/full.jpg").
 */

export function fullImageUrl(imagePath, updatedAt) {
  if (!imagePath) return null;
  return `/uploads/${imagePath}${cacheBuster(updatedAt)}`;
}

export function thumbImageUrl(imagePath, updatedAt) {
  if (!imagePath) return null;
  // Support both .webp (new) and .jpg (legacy) paths
  return `/uploads/${imagePath.replace(/full\.(webp|jpg)$/, 'thumb.$1')}${cacheBuster(updatedAt)}`;
}

// The service worker caches /uploads/* by URL (CacheFirst/StaleWhileRevalidate),
// and a regenerated recipe photo overwrites the same filename — so without a
// cache-busting query param, browsers can keep serving the old bytes for that
// URL until the runtime cache's max-age expires.
function cacheBuster(updatedAt) {
  if (!updatedAt) return '';
  const ts = new Date(updatedAt).getTime();
  return Number.isNaN(ts) ? '' : `?v=${ts}`;
}
