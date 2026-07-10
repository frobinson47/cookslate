# Loop 4: Density Toggle & Data Visualization

## Direction
The two remaining unexplored dimensions, both deferred from Loop 3. Density toggle addresses how the home page recipe grid adapts between browsing modes (visual scan vs. quick lookup). Data visualization redesigns the Kitchen Stats page, which currently uses basic bar charts that don't leverage the token system.

## Dimensions explored
1. **Density toggle** — grid vs. list vs. compact views for the recipe home page, with persistent user preference
2. **Data visualization** — redesigning Kitchen Stats charts, color semantics, and information hierarchy

## What this loop builds on
- Loop 1 tokens: all chart colors pull from the terracotta/sage/cream primitive scales
- Loop 1 nav IA: collapsible rail gives stats page more horizontal space for charts
- Loop 2 filtering: the density toggle interacts with the filter bar — compact view shows more results per screen when filtering

## What this loop rejected
- Did not explore realtime feedback yet — saving for Loop 5 where it pairs with a toast/notification system and ties all prior interaction patterns together
- Did not build a full HTML mockup of the stats page — a current-vs-proposed analysis is higher leverage at this stage since the data shapes are complex

## Deliverables
1. `density-toggle.html` — HTML/CSS mockup showing 3 density modes for the recipe grid
2. `data-visualization.md` — current vs proposed Kitchen Stats visualization design
