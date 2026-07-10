# Filtering & Search: Current vs Proposed

## Current State

### Search
- Single text input in the header (desktop) or expandable search icon (mobile)
- Debounced 300ms, hits API with `search` param
- Full-text search on `recipes(title, description)` MySQL index
- No search history, no suggestions, no result count shown

### Filtering
- **Tags only.** Horizontal scrollable tag bar on the home page. Click to filter, click again to deselect. Single tag at a time.
- **Ingredient search.** Toggle between "text" and "ingredients" search mode. User adds ingredient chips, results show recipes containing those ingredients. Good feature, but hidden behind a mode toggle.
- No sort options (newest, rating, cook count, time)
- No multi-faceted filtering (e.g., "vegetarian + under 30 min + high rating")
- No saved views or URL state persistence

### Problems
1. **Single-tag limitation.** Users mentally categorize by multiple axes: "I want something that's _Italian_ AND _quick_ AND _chicken_." Current UI forces one tag at a time.
2. **No sorting.** The grid has a fixed order (newest first). Users can't find "what's popular" or "what's fast."
3. **Search and filter are separate.** You can search OR filter by tag, but combining them requires the API to support both simultaneously (it does, but the UI doesn't expose it).
4. **No persistence.** Leaving the page loses the filter state. Users who browse, open a recipe, then go back start from scratch.

---

## Proposed: Faceted Filter Bar

### Design principle
Filters should feel like refining a thought, not filling a form. Each filter chip narrows the result set visually and immediately. The URL updates so the view is shareable and back-button friendly.

### Filter dimensions

| Dimension | UI | Current support | Needs API work? |
|-----------|-----|-----------------|-----------------|
| Text search | Input field (keep) | Yes | No |
| Tags | Multi-select pills | Partial (single tag) | Needs `tag[]=a&tag[]=b` |
| Time | Preset buttons: Quick (<15m), Medium (15-45m), Long (45m+) | No | Simple WHERE clause |
| Difficulty | Easy / Medium / Hard pills | Client-side calc exists | No (client filter) |
| Rating | Min stars slider or pills | No | WHERE clause |
| Ingredients | "Has ingredient" chips | Yes (separate mode) | Already exists |
| Sort | Dropdown: Newest, Rating, Most Cooked, Fastest | Only newest | Needs ORDER BY options |

### Layout

```
┌─────────────────────────────────────────────────────────┐
│ 🔍 Search recipes...              [Sort: Newest ▾]     │
├─────────────────────────────────────────────────────────┤
│ Tags: [Italian ×] [Chicken ×]  + Add tag               │
│ Time: ( ) Any  (●) Quick  ( ) Medium  ( ) Long         │
│ More filters ▾                                          │
├─────────────────────────────────────────────────────────┤
│ 23 recipes · Clear all filters                          │
└─────────────────────────────────────────────────────────┘
```

### Interaction details

1. **Filter bar lives below the header, above the grid.** Sticky on scroll so active filters remain visible.
2. **Tag multi-select.** Clicking a tag adds it to the active filter set. Tags show as removable pills. A "+" button opens the full tag list as a dropdown.
3. **Time presets.** Radio-style buttons. One active at a time. Maps to `total_time` ranges.
4. **"More filters" expander.** Shows difficulty, rating, ingredients, source. Keeps the default view clean for casual browsing.
5. **Result count.** Always visible. Shows how many recipes match the current filter combination.
6. **Clear all.** Single button resets everything.
7. **URL state.** Every filter maps to a search param: `?search=pasta&tags=italian,quick&time=quick&sort=rating`. Back button works. URLs are shareable.

### Sort options
| Label | API param | Notes |
|-------|-----------|-------|
| Newest | `sort=newest` | Current default, keep it |
| Top Rated | `sort=rating` | avg_rating DESC, needs index |
| Most Cooked | `sort=popular` | cook_count DESC |
| Quickest | `sort=fastest` | total_time ASC, NULL last |
| Recently Cooked | `sort=recent` | From cook_log, per-user |

### Mobile adaptation
- Filter bar collapses to a single row: search input + filter icon button
- Filter icon opens a bottom sheet with all facets stacked vertically
- Active filter count shown as badge on filter icon
- Sort accessible from the bottom sheet

---

## What this enables in later loops

- **Saved views** (Loop 4+): "My weeknight dinners" = tags:quick,dinner + time:quick + sort:fastest. Save as a named filter, appears in sidebar.
- **Command palette** (Loop 3): `Cmd+K` → type "quick italian" → search results + filter suggestions appear together.
- **Smart defaults**: When user opens the app at 5pm on a weekday, auto-suggest "Quick recipes" filter. (This is a product decision, not a design one — but the filter architecture supports it.)

---

## API changes needed

1. **Multi-tag filtering:** `GET /recipes?tag[]=italian&tag[]=chicken` (AND logic)
2. **Sort parameter:** `GET /recipes?sort=rating|popular|fastest|recent`
3. **Time filter:** `GET /recipes?max_time=30` (total of prep+cook)
4. **Result count in response:** `{ "recipes": [...], "total": 23, "page": 1 }` (may already exist in pagination)

These are additive — no breaking changes to existing API behavior.
