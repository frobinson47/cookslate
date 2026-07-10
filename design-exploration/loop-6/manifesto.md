# Cookslate Design Manifesto

Ten principles that emerged from six loops of design exploration. These aren't aspirational — they're the specific decisions we made and why. Follow them when building, revisit them when in doubt.

---

## 1. Warm, not sterile

Cookslate is a kitchen tool, not a spreadsheet. The palette is cream, terracotta, and sage — earth tones that feel like a butcher-block counter, not a hospital. Shadows use brown-tinted alpha channels, not pure black. The serif typeface (Playfair Display) appears only on recipe titles and hero headings — just enough warmth without becoming a wedding invitation.

**Token expression:** `shadow-md: 0 2px 8px rgba(62, 39, 35, 0.08)` — notice the brown, not gray.

## 2. Light mode is home

Food photography looks best on light backgrounds. Dark mode exists and is well-supported, but light is the default. We rejected the prompt's suggestion of dark-mode-first because the domain demands it — cream (#FFF8F0) makes food images pop, and the warm palette reads as inviting, not washed out.

**Exception:** Cook Mode goes dark to reduce eye strain in a dim kitchen.

## 3. Content gets the space

The sidebar collapsed from 264px to a 64px icon rail. That's 200 extra pixels for recipe photos, ingredient lists, and instructions. The navigation earned its tax cut by proving that 5 tier-1 items + Cmd+K can replace 14 top-level links without losing discoverability.

**The math:** At 1280px viewport, old layout gave content 1016px. New layout gives 1216px. That's a recipe card grid that fits 3 comfortable columns instead of squeezing 3 tight ones.

## 4. Frequency earns visibility

Navigation items are tiered by how often people use them:
- **Tier 1 (always visible):** Recipes, Favorites, Grocery, Meal Plan, Search
- **Tier 2 (one click):** Collections, Discover, Cook History, Stats, Import
- **Tier 3 (two clicks):** Settings, Admin, Export

This isn't minimalism for its own sake — it's recognition that a daily-use app should optimize for daily actions. Cmd+K makes everything equally fast regardless of tier.

## 5. Search is layered, not modal

Three search experiences for three mental models:
1. **Filter bar** (home page): "I'm browsing and want to narrow what I see." Persistent, visible, URL-stateful. Multi-tag, time presets, sort.
2. **Command palette** (Cmd+K): "Take me somewhere specific." Fast jump to any recipe, page, or action. Prefix modes: `>` actions, `#` tags, `@` ingredients.
3. **Header search** (mobile): Opens the command palette as a full-screen overlay.

They share the same API but serve different intents. Filter bar is for lingering. Command palette is for leaping.

## 6. Feedback matches the action

Not every action deserves a toast. The rule:
- **Visual change IS the feedback:** Favoriting a heart, checking a grocery item, switching density. No toast.
- **Result is invisible or on another page:** Adding to grocery list, saving a recipe, import completing. Toast with optional undo.
- **Something went wrong:** Always toast. Never swallow errors in empty `catch` blocks.

Toasts are bottom-center, max 3 stacked, 4-second auto-dismiss, spring easing for playful entrance. Destructive actions use deferred delete with an undo window — the API call waits 3 seconds.

## 7. Empty states are emotional moments

Zero-data screens are the app's first impression. They deserve more than "No results found" and a gray icon. Every empty state has:
- A branded illustration (terracotta/sage/cream circle with decorative dots)
- Copy specific to the context ("No recipes match 'beef wellington'" not "No results")
- At least one actionable CTA ("Add 'Beef Wellington'" with pre-filled search term)
- Contextual education when appropriate (favorites page shows where the heart icon lives)

The grocery "all done" state is celebratory. The error state is calm. The first-run state is exciting. Match the emotion.

## 8. Density is a user choice, not a breakpoint

Three recipe grid modes — Grid (photo-forward), List (scannable), Compact (data-dense) — toggled explicitly by the user, not silently by the viewport. The user's choice persists in localStorage.

Mobile always shows Grid (compact is too dense for touch targets). The density toggle disappears below `md`.

**Why not auto-detect?** Because the same user on the same screen wants Grid when browsing and Compact when looking up a specific recipe. Intent, not screen size, determines density.

## 9. Charts tell stories, not list facts

Kitchen Stats redesign principle: answer "how am I doing?" before "what are the numbers?" Hero stat cards show trends (↑12% vs last month) before absolute values. Monthly activity uses an area sparkline (continuous line for trend questions) instead of bars (discrete comparison questions). No charting library — everything is inline CSS bars and a 40-line SVG sparkline.

Color semantics: terracotta = activity/cooking, sage = time/growth, amber = ratings/quality. Consistent across every chart, every page.

## 10. 42 components, zero dependencies

The proposed design system has 42 components (20 new, 22 modified). Zero new runtime dependencies for the core system. The only recommended library is `cmdk` (4KB, headless) for the command palette — and that's optional.

No Chart.js. No Radix UI. No component library. Tailwind CSS 4 + the token system + Lucide icons is the stack. Every component is built from the same primitives. The constraint isn't purism — it's that a self-hosted recipe app should load fast on a Raspberry Pi.

---

## What this manifesto is NOT

- **A style guide.** It doesn't specify button padding to the pixel. That's what the tokens and mockups are for.
- **Permanent.** These are the principles we landed on after 6 loops of exploration. They should be tested against real usage and revised when evidence contradicts them.
- **Complete.** It says nothing about animation choreography, print styles, PWA install flow, or accessibility audit results. Those are implementation concerns, not design principles.

---

## Reading order for implementation

1. `loop-1/design-tokens.json` — the raw materials
2. `loop-6/manifesto.md` — the principles (you are here)
3. `loop-5/component-inventory.md` — the build plan
4. `loop-6/recipe-detail-final.html` — the target to build toward
5. Individual loop specs as needed during implementation
