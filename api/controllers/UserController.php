<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../middleware/Auth.php';

class UserController {

    /**
     * GET /users
     * Admin only. Returns all users (no password hashes).
     */
    public function list(): array {
        Auth::requireAdmin();
        $userModel = new User();
        return $userModel->getAll();
    }

    /**
     * POST /users
     * Admin only. Create a new user. Expects JSON: { username, password, role? }
     */
    public function create(): array {
        Auth::requireAdmin();

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['username']) || empty($input['password'])) {
            http_response_code(400);
            return ['error' => 'Username and password are required', 'code' => 400];
        }

        $role = $input['role'] ?? 'member';
        if (!in_array($role, ['admin', 'member'])) {
            http_response_code(400);
            return ['error' => 'Role must be admin or member', 'code' => 400];
        }

        // Enforce user limit based on license tier
        require_once __DIR__ . '/../config/license.php';
        $license = License::getInstance();
        $userModel = new User();
        $currentCount = $userModel->countReal();
        $maxUsers = $license->maxUsers();

        if ($currentCount >= $maxUsers) {
            http_response_code(403);
            $tierLabel = $license->tier() === 'household' ? 'Household' : 'Pro';
            return [
                'error' => "User limit reached ({$currentCount}/{$maxUsers}). Upgrade to " .
                    ($license->tier() === 'household' ? 'add more users.' : 'Household to add up to 5 users.'),
                'code' => 403,
                'max_users' => $maxUsers,
                'current_users' => $currentCount,
            ];
        }

        require_once __DIR__ . '/../services/PasswordValidator.php';
        $validator = new PasswordValidator();
        $passwordResult = $validator->validate($input['password']);
        if (!$passwordResult['valid']) {
            http_response_code(400);
            return ['error' => implode('. ', $passwordResult['errors']), 'code' => 400];
        }

        // Check if username already exists
        $existing = $userModel->findByUsername($input['username']);
        if ($existing) {
            http_response_code(409);
            return ['error' => 'Username already exists', 'code' => 409];
        }

        $email = !empty($input['email']) ? trim($input['email']) : null;
        $user = $userModel->create($input['username'], $input['password'], $role, $email);
        http_response_code(201);
        return $user;
    }

    /**
     * PUT /users/{id}
     * Admin only. Update a user's email and role.
     */
    public function update(int $id): array {
        Auth::requireAdmin();

        $input = json_decode(file_get_contents('php://input'), true);

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            http_response_code(404);
            return ['error' => 'User not found', 'code' => 404];
        }

        $email = isset($input['email']) ? trim($input['email']) : $user['email'];
        $role = $input['role'] ?? $user['role'];

        if (!in_array($role, ['admin', 'member'])) {
            http_response_code(400);
            return ['error' => 'Role must be admin or member', 'code' => 400];
        }

        $userModel->update($id, $email ?: null, $role);
        return $userModel->findById($id);
    }

    /**
     * DELETE /users/{id}
     * Admin only. Delete a user.
     */
    public function delete(int $id): array {
        Auth::requireAdmin();

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            http_response_code(404);
            return ['error' => 'User not found', 'code' => 404];
        }

        // Prevent self-deletion
        if ($id === (int) ($_SESSION['user_id'] ?? 0)) {
            http_response_code(400);
            return ['error' => 'Cannot delete your own account', 'code' => 400];
        }

        $userModel->delete($id);
        return ['message' => 'User deleted successfully'];
    }

    /**
     * PUT /users/{id}/password
     * Admin only. Reset a user's password. Expects JSON: { password }
     */
    public function resetPassword(int $id): array {
        Auth::requireAdmin();

        $input = json_decode(file_get_contents('php://input'), true);

        if (empty($input['password'])) {
            http_response_code(400);
            return ['error' => 'New password is required', 'code' => 400];
        }

        require_once __DIR__ . '/../services/PasswordValidator.php';
        $validator = new PasswordValidator();
        $passwordResult = $validator->validate($input['password']);
        if (!$passwordResult['valid']) {
            http_response_code(400);
            return ['error' => implode('. ', $passwordResult['errors']), 'code' => 400];
        }

        $userModel = new User();
        $user = $userModel->findById($id);

        if (!$user) {
            http_response_code(404);
            return ['error' => 'User not found', 'code' => 404];
        }

        $userModel->resetPassword($id, $input['password']);
        return ['message' => 'Password reset successfully'];
    }
}
