<?php

require_once __DIR__ . '/../models/UserApiKey.php';
require_once __DIR__ . '/../middleware/Auth.php';
require_once __DIR__ . '/../services/ValidationHelper.php';
require_once __DIR__ . '/../services/LoggerService.php';

/**
 * Manages a user's own third-party API keys (BYOK), e.g. for image import.
 * Distinct from UserController, which is admin-only user management.
 */
class UserApiKeyController {
    private const PROVIDER = 'openai';

    /**
     * GET /users/me/openai-key
     * Returns whether a key is configured. Never returns the key itself.
     */
    public function status(): array {
        $userId = Auth::requireAuth();
        $model = new UserApiKey();
        return ['configured' => $model->isConfigured($userId, self::PROVIDER)];
    }

    /**
     * PUT /users/me/openai-key
     * Expects JSON: { api_key }
     */
    public function save(): array {
        $userId = Auth::requireAuth();

        $input = json_decode(file_get_contents('php://input'), true);
        $key = trim((string) ($input['api_key'] ?? ''));

        $v = new ValidationHelper();
        $v->required($key, 'api_key')->maxLength($key, 'api_key', 200);
        $response = $v->responseIfFailed();
        if ($response) return $response;

        if (!str_starts_with($key, 'sk-')) {
            http_response_code(400);
            return ['error' => "That doesn't look like a valid OpenAI API key", 'code' => 400];
        }

        $model = new UserApiKey();
        try {
            $model->upsert($userId, self::PROVIDER, $key);
        } catch (RuntimeException $e) {
            LoggerService::channel('encryption')->error('Failed to encrypt API key', ['error' => $e->getMessage()]);
            http_response_code(500);
            return ['error' => 'Server encryption is not configured. Contact your administrator.', 'code' => 500];
        }

        return ['configured' => true];
    }

    /**
     * DELETE /users/me/openai-key
     */
    public function remove(): array {
        $userId = Auth::requireAuth();
        (new UserApiKey())->delete($userId, self::PROVIDER);
        return ['configured' => false];
    }
}
