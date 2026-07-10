<?php

/**
 * Extracts recipe data from a photo using a vision-capable LLM.
 * Implementations return the same array shape as RecipeScraper::scrape().
 */
interface VisionRecipeParser
{
    /**
     * @param string $imageBase64 Raw base64-encoded image data (no data: prefix)
     * @param string $mimeType e.g. image/jpeg
     * @param string $apiKey Plaintext user API key for this provider
     * @return array RecipeScraper-shaped result: title, description, prep_time,
     *               cook_time, servings, ingredients, instructions, image_url,
     *               source_url, and error_code/error on failure.
     */
    public function parseImage(string $imageBase64, string $mimeType, string $apiKey): array;
}
