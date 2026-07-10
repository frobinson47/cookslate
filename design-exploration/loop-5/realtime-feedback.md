# Realtime Feedback: Toast System & Optimistic Updates

## Current State

### What exists
- **FavoriteButton**: Optimistic toggle — flips heart immediately, reverts on error. Silent on both success and failure.
- **CookButton**: Increments count optimistically. Silent.
- **MealPlanPage**: Optimistic drag-and-drop reorder. Silent.
- **Spinner component**: Full-page centered spinner for initial loads.
- **Skeleton component**: Placeholder shimmer for recipe cards and recipe detail.
- **Timer notification**: Browser Notification API for cook timers (the only real feedback mechanism).

### What doesn't exist
- No toast / snackbar system
- No inline success/error messages after form submissions
- No undo support for destructive actions
- No progress indication for multi-step operations (recipe import, bulk import)
- Empty `catch` blocks throughout — errors are silently swallowed

### The problem
The app is polite but mute. Users perform actions and get no confirmation. Did "Add to Grocery List" work? Did the recipe save? The UI changes happen, but there's no moment of acknowledgment. For optimistic updates that fail, the revert is silent — the user doesn't know something went wrong.

---

## Proposed: Toast System

### Design principles
1. **Toasts confirm what users can't see.** If the visual change IS the feedback (favoriting a heart, switching density), no toast needed. If the result is on another page (added to grocery list) or invisible (recipe saved to database), toast.
2. **Toasts are ephemeral.** 4 seconds default, auto-dismiss. Not persistent. Not a notification inbox.
3. **One action per toast.** Either "Undo" or "View" — never both. Most toasts have no action.
4. **Errors must be noticeable but not alarming.** Food apps should never feel stressful.

### Toast anatomy

```
┌─────────────────────────────────────────────┐
│  ✓  Added to "Weekly Shop"         [Undo]  │
└─────────────────────────────────────────────┘
     │                                  │
     icon (variant)                     action (optional)
```

- **Position:** Bottom-center, 24px from bottom edge. Above mobile bottom nav.
- **Width:** Auto (min 280px, max 480px), centered.
- **Stack:** Up to 3 visible, newest on top, older ones scale down slightly.
- **Enter:** Slide up + fade in, `duration-normal` (200ms), `ease-spring`.
- **Exit:** Fade out + slide down, `duration-fast` (150ms), `ease-default`.
- **Dismiss:** Auto at 4s, or swipe left/right on mobile, or click X.

### Variants

| Variant | Icon | Colors (light) | Colors (dark) | Use case |
|---------|------|----------------|---------------|----------|
| **success** | Checkmark circle | Sage-600 icon, sage-50 bg, sage border | Sage-400 icon, sage-900 bg | Action completed |
| **error** | Alert circle | Red-600 icon, red-50 bg, red border | Red-400 icon, red-900 bg | Action failed |
| **info** | Info circle | Terracotta-500 icon, terracotta-50 bg | Terracotta-400 icon, terracotta-900 bg | Informational |
| **warning** | Alert triangle | Amber-600 icon, amber-50 bg | Amber-400 icon, amber-900 bg | Caution |
| **loading** | Spinner | Cream-700 icon, surface-raised bg | Cream-300 icon, surface-raised bg | In-progress |
| **undo** | Checkmark + undo button | Sage icon, surface-raised bg | Same | Action with undo window |

### Visual styling (using Loop 1 tokens)

```css
.toast {
  background: var(--surface-raised);
  border: 1px solid var(--border-subtle);
  border-radius: var(--radius-xl);        /* 1rem */
  padding: 12px 16px;
  box-shadow: var(--shadow-lg);           /* warm-tinted */
  font-size: 14px;
  font-weight: 500;
  display: flex;
  align-items: center;
  gap: 10px;
  min-height: 48px;
}

/* Variant: success */
.toast-success {
  background: var(--sage-50);
  border-color: var(--sage-300);
}
.toast-success .toast-icon { color: var(--sage-600); }

/* Variant: error */
.toast-error {
  background: #FEF2F2;
  border-color: #FECACA;
}
.toast-error .toast-icon { color: #DC2626; }
```

### Action buttons in toasts

- **Undo:** Appears for destructive/reversible actions. Bold text, terracotta color, no border. Clicking triggers the undo API call and dismisses the toast.
- **View:** Appears when the result is on another page (e.g., "Added to grocery list" → "View"). Navigates and dismisses.
- **Retry:** Appears on error toasts for retryable actions. Triggers the same API call again.

---

## Optimistic Update Patterns

### Pattern 1: Toggle (instant, silent on success)
**Used by:** Favorite, checkbox, toggle settings

```
User clicks → UI updates immediately → API call fires
  ├─ Success: do nothing (UI is already correct)
  └─ Failure: revert UI + show error toast
```

**Current:** FavoriteButton already does this. Keep it. Add error toast on failure.
**No success toast.** The visual change (heart fills red) IS the feedback.

### Pattern 2: Create (instant with undo window)
**Used by:** Add to grocery list, add to meal plan, log cook

```
User clicks → UI shows success toast with "Undo" → API call fires
  ├─ Success: toast auto-dismisses after 4s
  ├─ Undo clicked (within 4s): cancel API call or fire delete, dismiss toast
  └─ Failure: show error toast, revert if needed
```

**Example toast:** `✓ Added 9 ingredients to "Weekly Shop"  [Undo]`

This is the biggest UX upgrade. Currently, "Add to Grocery List" opens a modal, user picks a list, items are added, modal closes — and the user has no idea if it worked. The toast confirms it AND gives an undo path.

### Pattern 3: Submit (loading → result)
**Used by:** Save recipe, import from URL, bulk import

```
User submits → Show loading toast → API call fires
  ├─ Success: replace loading toast with success toast, navigate if needed
  └─ Failure: replace loading toast with error toast + "Retry"
```

**Example sequence:**
1. `⏳ Importing recipe...` (loading toast, no auto-dismiss)
2. `✓ "Thai Basil Chicken" imported!  [View]` (success toast, 4s auto-dismiss)

For recipe import specifically, this replaces the current full-page loading spinner with a non-blocking toast. The user could navigate away and come back — the toast persists until the import completes.

### Pattern 4: Delete (confirm → undo window)
**Used by:** Delete recipe, remove from meal plan, clear grocery list

```
User clicks delete → Show confirmation modal (keep current) → On confirm:
  → UI removes item immediately
  → Show undo toast: "Recipe deleted  [Undo]"
  → API call fires after 3s delay (undo window)
    ├─ Undo clicked: cancel the API call, restore UI, dismiss toast
    └─ No undo: API call fires, toast auto-dismisses
```

This is a **deferred delete** pattern — the API call doesn't fire until the undo window expires. This means undo is always possible and instant (no need for a server-side undelete).

---

## Action-by-Action Feedback Map

| Action | Current feedback | Proposed feedback |
|--------|-----------------|-------------------|
| **Favorite toggle** | Heart fills/unfills (optimistic) | Keep. Add error toast on failure only |
| **Log cook** | Count increments, subtle animation | Keep. Add success toast: "Cooked! 8th time making this" |
| **Add to grocery list** | Modal closes | Toast: "Added 9 items to 'Weekly Shop'" [Undo] |
| **Add to meal plan** | Button state changes | Toast: "Added to Wednesday dinner" [Undo] |
| **Save recipe (edit)** | Navigates to recipe page | Toast: "Recipe saved" (on the destination page) |
| **Import from URL** | Full-page spinner | Loading toast → success toast with [View] |
| **Bulk import** | Progress bar (keep) | Keep progress bar. Add completion toast |
| **Delete recipe** | Confirmation modal → redirect | Keep modal. Add undo toast, defer API call |
| **Share link created** | Modal with link | Keep modal. Add "Link copied!" toast on copy |
| **Grocery item check** | Strikethrough (optimistic) | Keep. No toast (visual change IS feedback) |
| **Servings adjusted** | Numbers update | Keep. No toast |
| **View density changed** | Grid reflows | Keep. No toast |
| **Error (any API failure)** | Silent (empty catch) | Error toast: "Couldn't save. Check your connection." [Retry] |

---

## Implementation: Toast Context

```jsx
// Usage throughout the app
const { toast } = useToast();

// Success
toast.success('Added to "Weekly Shop"', { action: { label: 'Undo', onClick: handleUndo } });

// Error
toast.error('Couldn\'t save recipe. Check your connection.', { action: { label: 'Retry', onClick: handleRetry } });

// Loading → Success
const id = toast.loading('Importing recipe...');
// ... later
toast.dismiss(id);
toast.success('"Thai Basil Chicken" imported!', { action: { label: 'View', onClick: () => navigate(`/recipe/${newId}`) } });
```

**Provider:** Wrap `<App>` in `<ToastProvider>`. Renders a portal at the bottom of `<body>`.
**State:** Simple array of `{ id, variant, message, action?, duration? }`. Max 3 visible.
**No library needed.** This is ~120 lines of React. Sonner is the popular choice but at 8KB it's unnecessary for this simple a spec.

---

## What this does NOT cover

- **Persistent notifications** (e.g., "Your import from 2 hours ago failed"). That's a notification center — a future feature, not a design exploration.
- **WebSocket / SSE realtime updates** (e.g., another user edited a shared recipe). Out of scope for a self-hosted single-user/small-household app.
- **Email notifications.** Not applicable.
