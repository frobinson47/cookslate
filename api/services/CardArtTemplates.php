<?php

/**
 * Defines the v1 "Generate Recipe Art" template lineup: an OpenAI image size
 * and a prompt builder per template. Prompts are adapted from Midjourney-style
 * prompts (Forgejo issue #65) — MJ flags (--ar, --style) are stripped and
 * aspect ratio is expressed via OpenAI's `size` param instead.
 */
class CardArtTemplates
{
    public const TEMPLATES = [
        'gold-luxury', 'editorial-storyboard', 'boho-scrapbook', 'wellness-editorial',
        'anatomy-diagram', 'vintage-cookbook', 'dynamic-infographic', 'lifestyle-sidebar',
        'modern-minimal',
    ];

    private const SIZES = [
        'gold-luxury' => '1024x1536',
        'editorial-storyboard' => '1024x1536',
        'boho-scrapbook' => '1024x1536',
        'wellness-editorial' => '1024x1536',
        'anatomy-diagram' => '1024x1024',
        'vintage-cookbook' => '1536x1024',
        'dynamic-infographic' => '1024x1024',
        'lifestyle-sidebar' => '1024x1536',
        'modern-minimal' => '1024x1536',
    ];

    public static function isValid(string $template): bool
    {
        return in_array($template, self::TEMPLATES, true);
    }

    public static function sizeFor(string $template): string
    {
        return self::SIZES[$template] ?? '1024x1536';
    }

    /**
     * @param array $recipe     Recipe row shaped like Recipe::findById() (title, ingredients, instructions)
     * @param array $enrichment Output of CardArtEnricher::enrich() (subtitle, slogan, quote, main_ingredient, cooking_vibe, color_palette, headline_style)
     */
    public static function buildPrompt(string $template, array $recipe, array $enrichment): string
    {
        $title = (string) ($recipe['title'] ?? '');
        $ingredientLines = array_map(
            fn($ing) => trim(($ing['amount'] ?? '') . ' ' . ($ing['unit'] ?? '') . ' ' . ($ing['name'] ?? '')),
            $recipe['ingredients'] ?? []
        );
        $instructionLines = array_values($recipe['instructions'] ?? []);

        $ingredientsBlock = implode("\n", array_map(fn($i) => "- $i", $ingredientLines));
        $instructionsBlock = implode("\n", array_map(fn($i, $n) => ($n + 1) . ". $i", $instructionLines, array_keys($instructionLines)));
        $ingredientsInline = implode(', ', $ingredientLines);
        $instructionsInline = implode(' ', array_map(fn($i, $n) => ($n + 1) . ") $i", $instructionLines, array_keys($instructionLines)));

        $subtitle = (string) ($enrichment['subtitle'] ?? '');
        $slogan = (string) ($enrichment['slogan'] ?? '');
        $quote = (string) ($enrichment['quote'] ?? '');
        $mainIngredient = (string) ($enrichment['main_ingredient'] ?? '');
        $cookingVibe = (string) ($enrichment['cooking_vibe'] ?? '');
        $colorPalette = (string) ($enrichment['color_palette'] ?? '');
        $headlineStyle = (string) ($enrichment['headline_style'] ?? '');

        $realDataNote = "\n\nRender these exact ingredients and steps as the actual legible text content "
            . "in their respective sections (do not invent different ingredients):\nINGREDIENTS:\n{$ingredientsBlock}\nSTEPS:\n{$instructionsBlock}";

        return match ($template) {
            'gold-luxury' => "A professional luxury culinary infographic and recipe card layout on a dark charcoal textured background. Generate this as a fully rendered digital design composition, not a photograph of a printed piece.\n\nThe layout features elegant gold serif typography for the main title \"{$title}\" and a sophisticated script subtitle \"{$slogan}\". All text is in English using the Latin alphabet. A high-end food photography shot of the dish appears in the top right corner with dramatic chiaroscuro lighting, shallow depth of field, and exquisite plating on a dark ceramic plate.\n\nStructured grid sections include: 'INGREDIENTS' with minimalist gold icons, 'COOKING STEPS' as a numbered list, 'CHEF'S TIPS' in a framed box, and a 'PLATING GUIDE' with a secondary smaller food photo. A footer row contains icons for prep time and difficulty level. A gold-accented pull quote at the bottom right reads \"{$quote}\".\n\nVisual style: gold foil accents, refined thin borders, premium editorial design, high contrast, cinematic atmosphere. The composition fills the frame edge to edge with no white margins, no outer border, and no frame. All text is sharp, legible, and correctly rendered." . $realDataNote,

            'editorial-storyboard' => "A high-converting editorial culinary storyboard layout for \"{$title}\", organized as a visual narrative that guides the viewer through the dish from ingredients to finished plate. The centerpiece is a hyper-realistic hero photograph rendering the tactile details of {$mainIngredient} with precision. Lighting follows a chiaroscuro arrangement with softbox side-diffusers and rim lighting to create depth and a {$cookingVibe} atmosphere.\n\nThe layout uses a premium grid-based editorial system inspired by Kinfolk and Cereal magazine: bold, legible headline for the recipe title, cleanly organized body text for ingredients and steps, generous negative space, and a visual hierarchy that reads naturally from hero image to supporting details. Typography is minimal and high-contrast for print readability. Composition follows the rule of thirds. Color science is vivid and accurate, matching the actual appearance of \"{$title}\" and its ingredients.\n\nText and ingredient visuals are hyper-accurate to the dish name. No borders, frames, white margins, or edges. No blurry, low-contrast, muddy, faded, or cluttered elements. No clipping, overlapping, or illegibly small fonts. Not a photograph of a printed image." . $realDataNote,

            'boho-scrapbook' => "Generate a single vertical recipe poster image in a bohemian free-spirit style using the variables provided below.\n\nTitle: {$title}\nSubtitle: {$subtitle}\nIngredients: {$ingredientsInline}\nSteps: {$instructionsInline}\nColor palette: {$colorPalette}\n\nVisual style: Airy, freeform, asymmetric composition with generous soft negative space. No boxes, grids, or rigid columns, every element drifts naturally across the surface.\n\nHero image: Bright, light-filled photography of the finished dish as the focal point. Soft diffused daylight, luminous true-to-life food colors, shallow depth of field, styled on a pale washed-wood or light linen surface.\n\nTypography: Oversized hand-painted brush-lettered title sprawling airily across the top in a loose bohemian script with delicate watercolor-wash texture and thin flowing ink strokes. Small flowing script subtitle tucked beneath it like a handwritten note.\n\nDecorative accents: Sparse pressed wildflowers, thin macrame threads, feathers, and delicate pale rattan linework drifting around the frame edges, airy and uncluttered.\n\nIngredients block: A loosely clustered, free-flowing list on a pale torn-paper or light-linen patch, hand-lettered with small delicate botanical doodles beside each item.\n\nSteps: Drawn as a wandering thin-inked path of numbered stepping-stone icons that meanders across the poster, each paired with a tiny whimsical sketch.\n\nPalette: Apply the given color palette as soft washes across threads, ink strokes, textile patches, and background so every element feels light, sun-bleached, and airy.\n\nBackground: Smooth pale paper with subtle grain, bright even lighting throughout, no vignette. Tactile boho-market feel, high detail, editorial food-styling quality.",

            'wellness-editorial' => "Vertical recipe infographic poster for \"{$title}\" in a bright airy wellness editorial style.\n\nHero image: Finished \"{$title}\" in a clear glass or pale ceramic vessel, garnished and glistening, positioned on the right side with generous breathing room on a bright white marble or pale bleached-wood surface. Shot at a 45-degree angle with soft diffused natural morning window light from the upper left, high-key, airy, soft overexposed highlights, barely-there shadows, shallow depth of field, clean and ethereal.\n\nTypography & header: Elegant tall serif display title at the top reading \"{$title}\", with a flowing handwritten script accent line beneath it, a small rounded pill-shaped tagline badge, and one short descriptive sentence.\n\nLeft column infographic panel:\n- Ingredients section headed by a small leaf icon; each ingredient on its own line preceded by a tiny minimalist line icon: {$ingredientsInline}\n- How to Make section headed by a small blender icon; short numbered steps: {$instructionsInline}\n\nLower strip: Slim nutritional information bar with small simple icons, followed by a row of small circular wellness feature badges.\n\nDecorative details: Delicate hand-drawn heart and sparkle doodle accents scattered lightly across the layout.\n\nOverall aesthetic: Bright off-white linen textured background, abundant negative space, Scandinavian morning feel, pastel-muted desaturated palette with white dominant and subtle color accents drawn from the hero dish, fresh, vibrant, and airy throughout.",

            'anatomy-diagram' => "Generate a premium editorial-style anatomy infographic poster for \"{$title}\".\n\nVisual composition (square, print-ready):\n- Large illustrated hero food centered, rendered with exaggerated appetizing detail, creamy textures, vibrant colors, crisp outlines, soft shading, clean vector style\n- Ingredient callout lines pointing to each visible component, labeled with these real ingredients: {$ingredientsInline}\n- Bold headline: \"THE ANATOMY OF {$headlineStyle}\"\n- Small ingredient checklist panel in a corner\n- Decorative icons integrated naturally into the layout\n\nStyle and tone:\n- Vintage-inspired magazine layout with subtle paper texture and warm color palette\n- Playful yet professional typography\n- Premium restaurant branding aesthetic, balanced, polished, advertisement-ready\n\nOutput: Ultra-detailed, high-resolution illustration suitable for print. Treat this as a finished poster, not a sketch or concept.",

            'vintage-cookbook' => "An elegant vintage recipe journal displayed as an open two-page cookbook on aged parchment paper. The left page showcases \"{$title}\" beautifully illustrated in watercolor and ink, while the right page contains {$ingredientsInline} carefully arranged with cooking utensils, herbs, sauces, sliced produce, handwritten recipe notes, and ingredient labels. Also handwrite these steps in the margin notes: {$instructionsInline}\n\nRender the full spread with: decorative flourishes, soft watercolor splashes, realistic food textures, warm natural lighting, balanced editorial composition, authentic cookbook aesthetic, artistic sketchbook style, highly detailed handcrafted illustration, premium culinary artwork.",

            'dynamic-infographic' => "Generate a recipe infographic image for \"{$title}\".\n\nHero Visual: Show the finished dish sliced, plated, or portioned, presented at a perspective or angled view, floating slightly above the surface. This is the focal centerpiece.\n\nLayout: Arrange ingredients, steps, and tips dynamically around the dish in an editorial style (not restricted to top-down). Flow elements organically so the composition feels alive rather than gridded.\n\nIngredients Section: Include a mini icon or illustration for each ingredient alongside its quantity. Group them in clusters, circular flows, or connected lists that visually link back to the dish.\n\nStep Panels: Display preparation steps in soft-gradient or glassmorphic panels. Use modern typography with clear hierarchy. Accent colors can call out key stats like calories or prep time.\n\nVisual Style: Editorial infographic meets lifestyle food photography, vibrant natural food colors, clean vector icons, subtle drop shadows, soft gradients, minimal textured or gradient background, soft natural studio lighting.\n\nHierarchy: Dish, then Steps, then Ingredients, then optional stats. Preserve enough negative space to keep the design airy and readable.\n\nOutput: Ultra-crisp, social-feed optimized, no watermark." . $realDataNote,

            'lifestyle-sidebar' => "A lifestyle magazine style recipe card for \"{$title}\" ({$subtitle}). A warm, appetizing photo-style hero image of the dish on the left two-thirds, with a clean sidebar on the right listing ingredients and steps in a modern sans-serif font, soft natural lighting aesthetic. Include the tagline \"{$slogan}\" near the title, and a small pull-quote at the bottom reading \"{$quote}\".\n\nRender these exact ingredients and steps as the actual legible text content in the sidebar (do not invent different ingredients):\nINGREDIENTS:\n{$ingredientsBlock}\nSTEPS:\n{$instructionsBlock}",

            'modern-minimal' => "A modern minimal editorial style recipe card for \"{$title}\". Lots of white space, thin sans-serif typography, a single small elegant photo-style image of the dish, generous margins, Scandinavian/editorial magazine design aesthetic. Small subtitle line reading \"{$subtitle}\" beneath the title, and a minimal pull-quote near the bottom reading \"{$quote}\". Color accents drawn from: {$colorPalette}.\n\nRender these exact ingredients and steps as the actual legible text content (do not invent different ingredients):\nINGREDIENTS:\n{$ingredientsBlock}\nSTEPS:\n{$instructionsBlock}",

            default => throw new InvalidArgumentException("Unknown card art template: {$template}"),
        };
    }
}
