# Data Visualization: Current vs Proposed

## Current State

The Kitchen Stats page (`pro/pages/StatsPage.jsx`) has four visualization types:

### 1. Stat Cards (8 cards across 2 rows)
- Generic centered layout: icon + large number + label + sublabel
- All look the same — no visual hierarchy between primary stats (Total Cooks, Streak) and secondary (Favorites count, Avg Rating)
- No trend indication (is this going up or down?)

### 2. Top Recipes (ranked list)
- Simple numbered list with thumbnail + title + cook count
- Functional but feels like a data table, not a dashboard element
- No relative scale visualization — "12x" vs "3x" reads the same visually

### 3. Top Tags (horizontal bar chart)
- Terracotta bars at 60% opacity on cream track
- Single color, no differentiation between tag categories
- Width is relative to the top tag (good), but the bars are thin (h-2) and hard to compare

### 4. Weekday Heatmap (vertical bars)
- Terracotta bars with opacity mapped to intensity
- Effective pattern but small (max 64px height)
- No benchmark or average line

### 5. Monthly Activity (vertical bars)
- Sage-tinted bars, max 80px height
- Shows volume but no context (what's a "good" month?)

### Problems
1. **No visual hierarchy.** All 5 sections look equally important. The page is a flat list of cards.
2. **Single color per chart.** Everything is terracotta or sage. No way to compare dimensions visually.
3. **No trends or benchmarks.** Numbers without context. "47 Total Cooks" — is that a lot? Is it growing?
4. **Too many stat cards.** 8 stat cards in a 4-column grid creates a wall of numbers. The eye doesn't know where to land.

---

## Proposed Redesign

### Principle: Stats should tell a story, not list facts

The page should answer three questions in order:
1. **How active am I?** (hero stat + trend)
2. **What do I cook?** (top recipes + tags)
3. **When do I cook?** (weekday + monthly patterns)

### Layout: Hero → Two-column → Full-width

```
┌────────────────────────────────────────────────┐
│  HERO STAT STRIP                                │
│  ┌──────┐  ┌──────┐  ┌──────┐  ┌──────┐       │
│  │ 47   │  │ 23   │  │ 8h   │  │ 5🔥  │       │
│  │cooks │  │unique│  │total │  │streak│       │
│  │ ↑12% │  │ ↑3   │  │ ↑2h  │  │      │       │
│  └──────┘  └──────┘  └──────┘  └──────┘       │
├────────────────────┬───────────────────────────┤
│  MOST COOKED       │  WHAT YOU COOK            │
│  1. 🖼 Thai Basil  │  [====== Italian    12]   │
│     ████████ 12x   │  [===== Chicken    10]    │
│  2. 🖼 Carbonara   │  [==== Quick        8]    │
│     ██████ 8x      │  [=== Stir-Fry     6]    │
│  3. 🖼 Tacos       │  [== Baking        4]    │
│     ████ 5x        │                           │
├────────────────────┴───────────────────────────┤
│  COOKING RHYTHM                                 │
│  ┌─ Weekday heatmap ─┐  ┌─ Monthly sparkline ─┐│
│  │ M T W T F S S      │  │ ___/\___/\_/\___   ││
│  │ ■ ■ ■ ■ ■ □ ■      │  │ Jan → Dec          ││
│  └────────────────────┘  └─────────────────────┘│
└────────────────────────────────────────────────┘
```

### Color semantics for charts

Each chart type gets a consistent color role from the token system:

| Data type | Color | Token | Rationale |
|-----------|-------|-------|-----------|
| Cook count / activity | Terracotta 500 | `accent.primary` | Primary metric, brand color |
| Time-related | Sage 500 | `accent.secondary` | Sage = growth, patience |
| Rating/quality | Warm amber `#D97706` | `feedback.warning` | Stars are traditionally gold/amber |
| Streak/achievement | Terracotta 600 (darker) | `accent.primary-hover` | Intensity variant of primary |
| Tag categories | Terracotta at varying opacity | 20%-100% of `accent.primary` | Single-hue scale for ranked data |
| Benchmarks/averages | Cream 400 (dashed) | `border.strong` | Subtle, non-competing reference line |

### Hero stat strip redesign

Reduce from 8 cards to **4 primary stats** in a horizontal strip at the top. Each one gets:

```
┌─────────────────────────┐
│  ChefHat                │
│  47                     │  ← Large number, font-display, 2xl
│  Times Cooked           │  ← Label, text-muted, xs uppercase
│  ↑ 12% vs last month   │  ← Trend badge: green if up, amber if flat, red if down
└─────────────────────────┘
```

The remaining 4 stats (Favorites, Avg Rating, New This Year, Busiest Day) move to a secondary row below the hero strip — smaller cards, no trend badges. This creates visual hierarchy: the first row is the dashboard headline, the second row is supporting context.

### Trend badges

**Where does trend data come from?** The API already returns `monthly_activity`. We can derive:
- "vs last month" = compare current month count to previous month
- "vs same month last year" = if data exists

Trends are a powerful addition for minimal API cost. Compute client-side from existing `monthly_activity` and `stats` response.

Format:
- **Up:** `↑ 12%` in sage-600 on sage-50 background
- **Down:** `↓ 8%` in terracotta-600 on terracotta-50 background (not red — down isn't always bad in cooking)
- **Flat:** `→ same` in cream-600 on cream-200 background
- **No data:** Hidden (don't show "0%" — it reads as failure)

### Most Cooked: add relative bars

Current: numbered list with text count.
Proposed: keep the list but add an inline bar behind each entry:

```
1.  [Thai Basil Chicken ████████████████ 12x]
2.  [Pasta Carbonara    ██████████       8x]
3.  [Fish Tacos         ██████           5x]
```

The bar fills proportionally to #1. Color: terracotta at 15% opacity as background fill, with the count in terracotta-500. This makes relative popularity scannable at a glance without needing to compare numbers.

### Top Tags: color variation + rounded bars

Current: single-color thin bars.
Proposed:
- Thicker bars (h-3 → h-4) with rounded ends
- Each bar gets terracotta at a different opacity level (100% for #1, stepping down by 15% per rank)
- Add the count inside the bar (right-aligned) if the bar is wide enough, or outside if narrow
- Cap at 6 tags (current shows all, which can be 10+)

### Weekday heatmap: GitHub-style grid

Current: vertical bars.
Proposed: Replace with a **heat grid** (like GitHub's contribution graph):

```
Week of:  Apr 7    Apr 14   Apr 21   Apr 28
Mon       ■■       ■■■      ■        ■■
Tue       ■        ■■       ■■       
Wed       ■■■      ■■       ■■       ■
Thu       ■        ■        ■■■      ■■
Fri       ■■       ■■       ■        ■■■
Sat                ■                  ■
Sun       ■                          
```

Actually — for a cooking app, the simple weekday bar chart is fine. The user cares about "I cook most on Wednesdays" not "what did I cook on April 14." **Keep the vertical bars** but improve them:

- Increase height to 80px max
- Add a thin dashed line at the average (cream-400, dashed)
- Label the peak day with a small badge: "Your top day"
- Use terracotta gradient (lighter at bottom, darker at top) instead of flat opacity

### Monthly activity: sparkline + area fill

Current: vertical bars.
Proposed: **Area chart with sparkline**. Monthly data is inherently continuous — bars create visual gaps that break the trend story.

- Smooth SVG path connecting the 12 data points
- Fill below the line with terracotta at 10% opacity
- Data points as small circles (4px) at each month, terracotta-500
- Hover reveals exact count in a tooltip
- X-axis: month abbreviations
- Y-axis: implied (no labels needed at this scale)
- Add the same average dashed line as the weekday chart

Why area chart over bars? It answers "am I cooking more or less over time?" — which is a trend question, best served by a continuous line. Bars answer "which month had the most?" which is the wrong question for this data.

---

## Implementation notes

**No charting library needed.** All these visualizations are simple enough to render as:
- Inline `<div>` bars with percentage widths (existing pattern, just refined)
- SVG `<path>` for the sparkline (one component, ~40 lines)
- CSS gradients for the heat-mapped bars

Avoid pulling in Chart.js or Recharts for this. The stats page has 5 simple chart types — a library would be overkill and add 40KB+ to the bundle.

**Responsive behavior:**
- `lg`+: two-column layout for Most Cooked + Top Tags
- `md`: stack to single column
- `sm`: hero stats collapse to 2x2 grid, secondary stats hide behind "Show more"
