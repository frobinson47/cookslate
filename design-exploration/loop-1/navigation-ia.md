# Navigation / IA: Current vs Proposed

## Current State

### Structure
- **Desktop**: 264px fixed sidebar (always expanded) + sticky header with search + main content
- **Mobile**: Hamburger drawer (full nav clone) + sticky header + 5-item bottom tab bar

### Sidebar items (14 total)
Recipes, Add Recipe, Bulk Import, Favorites, Meal Plan*, Grocery Lists, Collections, Discover, Cook History, Kitchen Stats*, Admin*, Ingredients, Settings, Export (collapsible submenu), Theme toggle, Logout

### Problems identified

1. **Too many top-level items.** 14 nav items creates decision fatigue. Linear has 6. Notion groups into sections. Mealie uses ~8 with clear hierarchy. Cookslate treats everything as equal weight.

2. **No grouping.** "Add Recipe" and "Bulk Import" are creation actions sitting next to browse destinations. "Ingredients" (a database reference tool) sits next to "Settings" (a config page). Users scan nav by category — mixed categories slow scanning.

3. **Sidebar width is generous.** 264px permanently allocated. The content area loses that space on every page. For a recipe app where the content (photos, ingredient lists, instructions) is the star, this is expensive real estate.

4. **Export buried in sidebar.** Export is an infrequent action living in the primary navigation. It should be in Settings or a menu.

5. **Mobile drawer duplicates sidebar.** The drawer is a 1:1 clone of the sidebar. Mobile users have different priorities (quick access to grocery list at the store, checking a recipe while cooking). The bottom nav does good work here but the drawer doesn't add differentiation.

6. **Search is header-only.** The search input is in the header, which is correct, but there's no command palette or quick-jump. Power users (who have 100+ recipes) need faster access patterns than scrolling.

---

## Proposed: Tiered Navigation

### Principle: Frequency-based hierarchy
Put the 4 things people do every day at the top level. Group the rest behind one click. Hide the rare stuff behind two clicks.

### Tier 1: Always visible (sidebar icons + bottom nav)
| Icon | Label | Path | Why top-level |
|------|-------|------|---------------|
| LayoutGrid | Recipes | / | The home screen. Most visited. |
| Heart | Favorites | /favorites | Quick access to go-to recipes |
| ShoppingCart | Grocery | /grocery | Used in-store, needs instant access |
| CalendarDays | Meal Plan | /meal-plan | Weekly planning ritual |
| Search | Search | (overlay) | Cross-cutting, always available |

### Tier 2: Grouped sections (sidebar, collapsed by default)
**Create & Import**
- Add Recipe
- Bulk Import
- Import from URL (currently part of Add Recipe)

**Browse & Discover**
- Collections
- Discover
- Ingredient Database

**Activity**
- Cook History
- Kitchen Stats*

### Tier 3: Settings/overflow
- Settings (includes Export, Theme, Account)
- Admin* (admin users only)

### Sidebar behavior change: Collapsible rail

**Collapsed (default on lg-xl):** 64px icon rail showing Tier 1 items + a "..." or kebab for Tier 2.
**Expanded (hover/click or pinned):** 256px with full labels and grouped sections.
**Hidden (below lg):** Bottom nav + header handles everything.

This gives content ~200px more horizontal space in the default state. Recipe cards and recipe detail pages benefit enormously.

### Search upgrade path
Add `Cmd/Ctrl+K` command palette that searches:
- Recipe titles (primary)
- Ingredients ("chicken thigh")
- Tags
- Navigation destinations ("go to grocery list")

This replaces the header search bar for power users while keeping the existing search for casual use.

---

## Reference alignment

| Pattern | Linear | Notion | Mealie | Cookslate proposed |
|---------|--------|--------|--------|--------------------|
| Sidebar style | Collapsible rail | Expandable + breadcrumbs | Always expanded | Collapsible rail |
| Top-level items | 5-6 | 4 + recents | 7-8 | 5 |
| Search | Cmd+K palette | Cmd+K palette | Basic header | Header + Cmd+K |
| Mobile nav | Bottom tab | Bottom tab | Hamburger | Bottom tab (keep) |
| Grouping | Workspaces > sections | Pages > databases | Flat list | Frequency tiers |

---

## Risk assessment

- **Collapsible rail is more complex.** Requires state management (collapsed vs expanded vs pinned), animation, and tooltip labels when collapsed. But the codebase already has responsive sidebar logic — this extends rather than replaces it.
- **Reducing top-level items might frustrate users** who have muscle memory for "Cook History" being one click away. Mitigation: the command palette makes everything one `Cmd+K` away.
- **Grouping requires category decisions.** "Discover" could be Browse or could be its own thing. These category names will need user testing.
