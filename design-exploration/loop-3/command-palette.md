# Command Palette: Interaction Design Spec

## Why this matters

Cookslate currently has 14+ destinations and growing. Loop 1 reduced top-level nav to 5, but that pushes Browse, Cook History, Collections, Ingredients, etc. behind a second click. The command palette makes everything one `Cmd+K` away — it's the escape hatch that lets us simplify the sidebar without losing discoverability.

Reference: Linear's command palette is the gold standard — fast, categorized, keyboard-first. Notion's is good but tries to do too much (creating content + navigating + formatting). We want Linear's focus, not Notion's sprawl.

---

## Trigger

| Method | Context |
|--------|---------|
| `Cmd+K` / `Ctrl+K` | Global hotkey, works everywhere except inside Cook Mode |
| Click search icon in sidebar rail | Same as Cmd+K |
| `/` key | Only when no input is focused (vim-style quick search) |
| Header search input click (mobile) | Opens the palette as a full-screen overlay instead of the current inline search |

## Anatomy

```
┌──────────────────────────────────────────────────┐
│  🔍  Search recipes, actions, pages...     ⌘K   │
├──────────────────────────────────────────────────┤
│                                                  │
│  RECENT                                          │
│  ┌─ 🕐  Thai Basil Chicken              recipe  │
│  ├─ 🕐  Grocery: Weekly Shop             page   │
│  └─ 🕐  Pasta Carbonara                 recipe  │
│                                                  │
│  QUICK ACTIONS                                   │
│  ┌─ ＋  Add new recipe                  action  │
│  ├─ 📋  Import from URL                 action  │
│  ├─ 🛒  New grocery list                action  │
│  └─ 📅  Plan this week                  action  │
│                                                  │
│  NAVIGATE                                        │
│  ┌─ 📖  Recipes                          page   │
│  ├─ ❤️  Favorites                        page   │
│  ├─ 🛒  Grocery Lists                    page   │
│  ├─ 📅  Meal Plan                        page   │
│  ├─ 📚  Collections                      page   │
│  ├─ 🧭  Discover                         page   │
│  ├─ 📊  Kitchen Stats                    page   │
│  └─ ⚙️  Settings                         page   │
│                                                  │
│  ↑↓ Navigate  ⏎ Select  esc Close               │
└──────────────────────────────────────────────────┘
```

## Result categories & ranking

When the input is empty, show three sections:
1. **Recent** — last 3 visited recipes/pages (from `useRecentlyViewed` hook, extend to pages)
2. **Quick Actions** — 4 common creation/import actions
3. **Navigate** — all app sections

When the user types, results are ranked:

| Priority | Category | Match type | Example |
|----------|----------|------------|---------|
| 1 | Recipes | Title starts with query | "thai" → Thai Basil Chicken |
| 2 | Recipes | Title contains query | "chicken" → Thai Basil Chicken |
| 3 | Pages | Name matches query | "grocery" → Grocery Lists |
| 4 | Actions | Label matches query | "import" → Import from URL |
| 5 | Recipes | Description/tag match | "spicy" → recipes tagged "spicy" |
| 6 | Ingredients | Ingredient name match | "garlic" → recipes containing garlic |

Max 10 results shown. Categories with 0 matches are hidden.

## Search behavior

- **Debounce:** 150ms (faster than home page's 300ms — palette should feel instant)
- **API call:** Only fires for recipe/ingredient search. Page and action matches are client-side (static list).
- **Endpoint:** Reuse existing `GET /recipes?search=` but with `perPage=5` for speed
- **Loading state:** Subtle spinner replaces the search icon. Never show a loading skeleton in the palette — it should feel like typing into memory.

## Special input modes

| Prefix | Behavior | Example |
|--------|----------|---------|
| `>` | Filter to actions only | `>import` → shows Import from URL, Bulk Import |
| `#` | Filter to tags | `#italian` → shows tag "Italian", offers to filter home page by it |
| `@` | Filter to ingredients | `@chicken @garlic` → find-by-ingredients (reuses existing API) |
| No prefix | Universal search (default) | `pasta` → recipes + pages + actions |

The prefix hint appears as a muted label below the input when the palette opens: `Type to search · > actions · # tags · @ ingredients`

## Keyboard navigation

| Key | Action |
|-----|--------|
| `↑` / `↓` | Move selection through results |
| `Enter` | Execute selected result (navigate to recipe/page, or run action) |
| `Escape` | Close palette. If input has text, first press clears it, second press closes. |
| `Tab` | Cycle between result categories |
| `Cmd+Enter` | Open in new tab (recipes/pages only) |

## Visual design (using Loop 1 tokens)

- **Overlay:** Centered, 560px wide, max 480px tall. Appears with `duration-normal` (200ms) scale+fade.
- **Backdrop:** `rgba(62, 39, 35, 0.3)` with `backdrop-filter: blur(4px)` — warm tint, not gray.
- **Input:** 48px height, `font-size: 16px` (prevents iOS zoom), no border — just the background.
- **Results:** Each row 44px min-height (touch target). Selected row gets `accent-primary-soft` background.
- **Category headers:** `text-xs font-semibold uppercase tracking-wide text-muted` — same treatment as the sidebar section headers from Loop 1.
- **Type badges:** Small pill on the right: "recipe" in terracotta-soft, "page" in sage-soft, "action" in cream-200.
- **Shadow:** `shadow-xl` from tokens — this is the most elevated element in the app.

## Mobile behavior

On screens below `md` (768px):
- Palette opens as **full-screen overlay** with the input at the top
- Results fill the remaining space, scrollable
- No backdrop blur (performance)
- `Escape` = back gesture or X button
- Recent section shows 5 items instead of 3 (more vertical space available)

## Implementation notes

```
// Component structure
<CommandPalette>
  <CommandInput />
  <CommandList>
    <CommandGroup heading="Recent">
      <CommandItem />
    </CommandGroup>
    <CommandGroup heading="Quick Actions">
      <CommandItem />
    </CommandGroup>
    <CommandGroup heading="Recipes">
      <CommandItem />
    </CommandGroup>
  </CommandList>
  <CommandFooter /> {/* keyboard hints */}
</CommandPalette>
```

Consider using `cmdk` (pacocoursey/cmdk) as a headless base — it handles keyboard navigation, focus management, and result filtering out of the box. Style it with Tailwind. It's 4KB gzipped and has zero dependencies.

## What this replaces

- **Header search bar** (desktop): Still exists for casual browse, but power users will use Cmd+K exclusively
- **Mobile header search**: Replaced entirely — tapping search opens the full palette
- **By-ingredient search toggle on home page**: The `@` prefix in the palette does the same thing without a mode toggle

## What this does NOT replace

- **Filter bar on home page** (from Loop 2): Filters are for persistent, visible narrowing of results while browsing. Command palette is for quick jumps. Different mental models.
- **Sidebar navigation**: Still needed for spatial orientation ("where am I?"). Command palette is for "take me somewhere" — it doesn't show your current location.
