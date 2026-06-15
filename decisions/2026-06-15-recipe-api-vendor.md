## Decision: Decline integration with recipe-api.com (Recipe API) as a recipe/nutrition data source.

## Context
Paul Crossland (paul@recipe-api.com) sent a cold outreach email pitching Recipe
API — a commercial API offering 25k+ structured recipes, grouped ingredients,
dietary/allergen metadata, and 32 USDA-backed nutrients per serving. Pitched as
a way to use one consistent schema instead of normalizing each source ourselves.
Possible use cases: Discover page seeding, ingredient database nutrition data,
allergen/dietary metadata.

## Alternatives considered
1. Integrate Recipe API as the backing data source for the Discover page.
2. Integrate Recipe API as nutrition fallback for the Ingredient Database (Pro).
3. Use directly only on import (one-shot enrichment when scraping).
4. Decline. Continue with existing scraper + Mealie/Paprika/Tandoor import flows.

## Reasoning (why option 4 won)
- **Self-hosted positioning.** Cookslate's marketing pitch (README, demo) is
  privacy-first, self-hosted, "your data your way." A commercial third-party
  API dependency means user instances phone home — directly contradicts the
  r/selfhosted target audience we're aiming at (issue #23).
- **Business model mismatch.** Recipe APIs are typically $5–$100/mo recurring.
  Cookslate is a $29.99 one-time purchase. Eating recurring vendor costs hurts
  per-user margin; passing the cost on kills the value proposition.
- **Licensing trap.** Commercial recipe APIs usually disallow persisting/
  redistributing data. That breaks our own data-portability story (JSON-LD
  export, ZIP export, "get your data out").
- **No real gap before launch.** Scraper already extracts JSON-LD nutrition
  + tags (#10). USDA nutrition data is free and public if we ever need a
  fallback. Discover seeding is the only plausible benefit, and it's a
  Differentiators concern, not launch-blocking.

## Trade-offs accepted
- Slower Discover page growth — content has to come from user imports or
  manual curation rather than a bulk seed.
- More work later if we ever want a richer allergen/dietary metadata model;
  we'll have to build or source it ourselves (USDA + open datasets).

## Supersedes
N/A — first decision in this area.

## Revisit if
- We need a Discover page expansion post-launch and can find a recipe data
  source whose license permits store-and-serve.
- We add a hosted SaaS tier where recurring vendor costs amortize across a
  user base.
