# Design Loop: Cookslate

MISSION: Evolve **Cookslate** (saas-dashboard) from its current state into a distinctive, polished, professional product. No existing screenshots or component directories were detected — you'll be starting from the current code.

## Reference aesthetic (study, don't copy)

- notion
- linear
- paprika
- mealie
- tandoor

Study how these products make decisions — type, color, density, motion, empty states, errors. Don't clone any one of them. The goal is a coherent direction for Cookslate that sits comfortably alongside them.

## Per-loop deliverables

Every loop must produce **at least one** of the following, filed under `./design-exploration/loop-{n}/`:

1. Single-file HTML/CSS mockup of a specific screen or component
2. Design tokens file (colors, type scale, spacing, shadows, motion)
3. "Current vs proposed" `.md` for one pattern, with rationale
4. Component inventory / IA proposal
5. Novel interaction sketch (can be prose + ASCII + CSS)

Each loop also gets a short `README.md` stating: what direction this loop took, why, and what it rejected from earlier loops.

## Exploration dimensions

Pick 1-2 per loop and go deep. Don't sample every dimension shallowly.

- Data visualization — chart density, legend treatment, color semantics for series
- Density toggle — comfortable vs compact row heights, breakpoints where it flips
- Command palette — global actions, keyboard entry points, result ranking
- Navigation / IA — sidebar vs topbar vs hybrid, section grouping, secondary nav
- Empty states — zero data, zero permissions, first-run onboarding moments
- Filtering & search — faceted filters, saved views, URL state, query language
- Realtime feedback — optimistic updates, inline loading, toast semantics

## Detected stack constraints (respect these)

- **Framework:** React 18 (Vite, React Router SPA)
- **Style system:** Tailwind CSS 4
- **Icons:** Lucide React
- **Existing components at:** `frontend/src/components/`, `frontend/src/pro/`
- **API client:** `frontend/src/services/api.js`
- **Domain:** recipe management, meal planning, grocery lists, cook logging, ingredient parsing

## General constraints

- Dark mode default unless the project's domain clearly suggests otherwise
- Accessibility non-negotiable — contrast, focus states, keyboard navigation
- Must work at 1280x800 minimum
- No generic "Material 3" / "default Tailwind" looks — make an opinionated choice

## Iteration rules

- Each loop builds on or deliberately rejects prior loops
- By loop 6, I want **convergence**, not 6 unrelated directions
- Final loop MUST produce:
  - `manifesto.md` — the principles that emerged from the exploration
  - Hero mockup of **recipe-detail** so I can directly compare it to the current state

## How to run

Run this prompt via `/loop 10m` for 6 loops. Between loops, read prior loop READMEs before starting the next one — the exploration is cumulative, not parallel.
