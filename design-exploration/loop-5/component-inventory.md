# Component Inventory: Proposed Design System

Consolidation of all components proposed across Loops 1-5 into a buildable inventory. Organized by layer: tokens → primitives → composites → pages.

---

## Layer 0: Design Tokens (Loop 1)

Already defined in `loop-1/design-tokens.json`. Implementation: migrate from current flat `@theme` block in `index.css` to the structured token system. All components below reference these tokens.

| Token group | File | Notes |
|-------------|------|-------|
| Color primitives | `design-tokens.json` → `color.primitive` | Cream/terracotta/sage/neutral 50-900 scales |
| Color semantics | `design-tokens.json` → `color.semantic` | Surface, text, border, accent, feedback |
| Typography | `design-tokens.json` → `typography` | Nunito + Playfair Display, 8-step scale |
| Spacing | `design-tokens.json` → `spacing` | 4px grid |
| Radius | `design-tokens.json` → `radius` | sm through full |
| Shadow | `design-tokens.json` → `shadow` | Warm-tinted (brown alpha) |
| Motion | `design-tokens.json` → `motion` | 5 durations, 5 easings |

---

## Layer 1: Primitive Components

These are the base UI building blocks. Most already exist; entries marked **[new]** or **[modify]** indicate what changes.

### Existing → Modify

| Component | File | Change | Loop |
|-----------|------|--------|------|
| `Button` | `ui/Button.jsx` | **[modify]** Apply token radius/shadow/motion. Add `variant="accent-secondary"` for sage buttons. | 1,2 |
| `Input` | `ui/Input.jsx` | **[modify]** Apply token focus ring (terracotta), border colors. | 1 |
| `Modal` | `ui/Modal.jsx` | **[modify]** Warm backdrop (`rgba(62,39,35,0.3)` + blur). Token shadows. | 1 |
| `Spinner` | `ui/Spinner.jsx` | **[modify]** Use terracotta color, token sizing. | 1 |
| `Skeleton` | `ui/Skeleton.jsx` | **[modify]** Use cream-200→cream-100 shimmer instead of generic gray. | 1 |
| `StarRating` | `ui/StarRating.jsx` | No change needed. Amber color is correct. | — |
| `TagBadge` | `ui/TagBadge.jsx` | **[modify]** Apply token radius-full, cream-200 background, hover state from Loop 2. | 2 |
| `ServingsAdjuster` | `ui/ServingsAdjuster.jsx` | **[modify]** Pill-shaped container, token colors per Loop 2 mockup. | 2 |
| `Timer` | `ui/Timer.jsx` | No change needed (functional component, styling is minimal). | — |

### New Components

| Component | Purpose | Loop | Priority |
|-----------|---------|------|----------|
| **`Toast`** | Ephemeral feedback messages. 5 variants (success/error/info/warning/loading). | 5 | High |
| **`ToastProvider`** | Context provider + render portal. Wraps `<App>`. | 5 | High |
| **`DensityToggle`** | 3-button radio group (grid/list/compact). Persists to localStorage. | 4 | Medium |
| **`DifficultyBadge`** | Extracted from inline rendering in RecipeCard/RecipePage. Token colors. | 2 | Low |
| **`TrendBadge`** | ↑/↓/→ indicator for stats. Sage/terracotta/cream variants. | 4 | Low |

---

## Layer 2: Composite Components

Built from primitives. These are the feature-level components.

### Navigation (Loop 1)

| Component | Status | Change |
|-----------|--------|--------|
| `Sidebar` | **[rewrite]** → `SidebarRail` | 64px collapsed rail with icon buttons + tooltips. Expand on hover/pin. Tier 1 items only (5 icons + search + settings). |
| `Header` | **[modify]** | Remove desktop search input (replaced by Cmd+K). Keep mobile search trigger → opens CommandPalette. Slim down to logo + breadcrumb + user avatar. |
| `BottomNav` | **[modify]** | Keep 5-item layout. Update icons/labels to match Tier 1 IA (Recipes, Favorites, Grocery, Meal Plan, Search). |
| `Layout` | **[modify]** | Adjust grid to 64px sidebar rail. Add ToastProvider. |

### Command Palette (Loop 3)

| Component | Status | Notes |
|-----------|--------|-------|
| `CommandPalette` | **[new]** | Full-screen on mobile, centered overlay on desktop. Consider `cmdk` as headless base. |
| `CommandInput` | **[new]** | 48px input with prefix mode detection (`>`, `#`, `@`). |
| `CommandGroup` | **[new]** | Collapsible section with heading (Recent, Actions, Recipes, Navigate). |
| `CommandItem` | **[new]** | 44px row with icon + label + type badge + keyboard hint. |

### Recipe Components

| Component | Status | Change | Loop |
|-----------|--------|--------|------|
| `RecipeCard` | **[modify]** | Apply token shadows, typography. Remove inline difficulty calc → use `DifficultyBadge`. | 2,4 |
| `RecipeGrid` | **[rewrite]** → `RecipeCollection` | Support 3 density modes (grid/list/compact). Accept `density` prop. Render appropriate sub-layout. | 4 |
| `RecipeListItem` | **[new]** | 88px list row: 64px thumb + title + meta + tags + time. | 4 |
| `RecipeCompactItem` | **[new]** | 40px compact row: fav + title + rating + tags + time + cook count. | 4 |
| `IngredientList` | **[modify]** | Checkbox styling per Loop 2 (rounded checkbox, sage check, strikethrough). | 2 |
| `StepList` | **[modify]** | Numbered circles in terracotta-soft per Loop 2 mockup. | 2 |
| `RecipeForm` | No change. | — | — |
| `ImportForm` | **[modify]** | Add loading toast integration for URL import. | 5 |
| `FavoriteButton` | **[modify]** | Add error toast on failure (currently silent). | 5 |
| `CookButton` | **[modify]** | Add success toast with cook count message. | 5 |
| `AddToMealPlanButton` | **[modify]** | Add success toast with day name. | 5 |
| `NutritionFacts` | **[modify]** | Redesign as 3-col grid with token colors per Loop 2 mockup. | 2 |
| `RecipeInsights` | No change. | — | — |
| `RelatedRecipes` | No change. | — | — |
| `CookMode` | No change (self-contained fullscreen experience). | — | — |

### Empty States (Loop 3)

| Component | Status | Notes |
|-----------|--------|-------|
| `EmptyState` | **[new]** | Reusable shell: illustration circle + title + description + CTA buttons. Configurable variant (terracotta/sage/cream/celebration/error). |
| `OnboardingSteps` | **[new]** | 3-step horizontal card row for first-run empty state. |
| `SearchSuggestions` | **[new]** | Horizontal chip row for no-results state. Clickable, triggers search. |

### Data Visualization (Loop 4)

| Component | Status | Notes |
|-----------|--------|-------|
| `StatCard` | **[modify]** | Add optional `TrendBadge`. Distinguish primary (large) vs secondary (small) sizes. |
| `BarChart` | **[new]** | Inline horizontal bar (for top recipes, top tags). Width proportional, single-hue opacity scale. |
| `Sparkline` | **[new]** | SVG area chart for monthly activity. ~40 lines. |
| `WeekdayBars` | **[modify]** | Increase height, add average line, gradient fill. |

### Filtering (Loop 2)

| Component | Status | Notes |
|-----------|--------|-------|
| `FilterBar` | **[new]** | Sticky bar below header. Search input + sort dropdown + active filter pills. |
| `TagMultiSelect` | **[new]** | Multi-select tag picker. Chips with remove buttons + add dropdown. |
| `TimeFilter` | **[new]** | Radio-style preset buttons: Quick/Medium/Long. |
| `FilterSheet` | **[new]** | Mobile bottom sheet containing all filter facets. |

---

## Layer 3: Page-Level Changes

| Page | Key changes | Loops |
|------|------------|-------|
| `HomePage` | Add FilterBar, DensityToggle, RecipeCollection (3 modes). Remove inline search/mode toggle. | 2, 4 |
| `RecipePage` | Apply Loop 2 mockup layout. Integrate toast feedback for actions. Redesign nutrition grid. | 2, 5 |
| `StatsPage` | Restructure layout (hero strip → two-col → full-width). Add TrendBadges. Replace monthly bars with Sparkline. | 4 |
| `FavoritesPage` | Add EmptyState (Loop 3 favorites design). | 3 |
| `GroceryPage` | Add EmptyState (celebration variant for all-checked). Toast for item operations. | 3, 5 |
| `MealPlanPage` | Add EmptyState (empty week). Toast for meal additions. | 3, 5 |

---

## Build Priority

### Phase 1: Foundation (affects everything)
1. Migrate `index.css` tokens to structured system
2. `ToastProvider` + `Toast` (unblocks all feedback improvements)
3. `SidebarRail` (layout change affects every page)

### Phase 2: Hero Screen
4. `RecipePage` redesign (highest-traffic page)
5. `EmptyState` component (used across 5+ pages)

### Phase 3: Home Page
6. `FilterBar` + `TagMultiSelect` + `TimeFilter`
7. `RecipeCollection` with density modes
8. `DensityToggle`

### Phase 4: Power Features
9. `CommandPalette` (complex but high-value)
10. `StatsPage` redesign with `Sparkline` + `TrendBadge`

---

## Component Count Summary

| Category | Existing (modify) | New | Total |
|----------|--------------------|-----|-------|
| Primitives | 8 | 5 | 13 |
| Navigation | 4 | 0 | 4 |
| Command Palette | 0 | 4 | 4 |
| Recipe | 8 | 2 | 10 |
| Empty States | 0 | 3 | 3 |
| Data Viz | 2 | 2 | 4 |
| Filtering | 0 | 4 | 4 |
| **Total** | **22** | **20** | **42** |

20 new components, 22 modifications to existing ones. No component is being deleted — all changes are additive or evolutionary.
