<?php

/**
 * Extracts store, date, total, and line items from a photo of a receipt
 * using a vision-capable LLM.
 */
interface ReceiptVisionParser
{
    /**
     * @param string $imageBase64 Raw base64-encoded image data (no data: prefix)
     * @param string $mimeType e.g. image/jpeg
     * @param string $apiKey Plaintext user API key for this provider
     * @return array store_name, trip_date, total_amount, items (each with
     *               name, quantity, unit, price), and error_code/error on failure.
     */
    public function parseReceipt(string $imageBase64, string $mimeType, string $apiKey): array;
}
