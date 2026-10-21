# Cookslate brand

Read this before making anything visual (video, social graphics, marketing pages).
Save any newly approved color, font, logo or style decision here.

## Colors and type — source of truth is the code
Don't copy hex values into other files. The tokens live in the `@theme` block of
`frontend/src/index.css` (cream, terracotta, sage, brown, warm-gray, surfaces, shadows, motion easings).
Core identity: terracotta `--color-terracotta` is the primary, sage is the accent, cream is the background,
brown is text. Shadows are warm-tinted (brown alpha), never black.

Fonts (loaded from Google Fonts in `frontend/index.html`):
- Nunito — UI and body text
- Playfair Display — serif, for editorial/hero headings

## Logo and imagery
- App icon: `frontend/public/cookslate_icon.png` (also `favicon.svg`, `icon-192.png`, `icon-512.png`)
- Product shot: `frontend/public/cookslate-product-1600x300.png`
- Marketing images: `marketing/` (`cookslate_banner.PNG`, `cookslate-product-1600x1200.png`)

## Motion
Spring and emphasized easings are defined as `--ease-spring` and `--ease-emphasize` in the `@theme` block.

## Footer (FMR Digital standard)
"Cookslate is a product of FMR Digital LLC · © 2026 FMR Digital LLC · All rights reserved", linking to FMR Digital.

## Voice and tone
TODO (Frank): not written down anywhere in the repo. Add 3-5 lines on how Cookslate talks, and what it never says.

## Approved looks
None recorded yet. When a generated video or graphic is signed off, note the file path and what made it right.
