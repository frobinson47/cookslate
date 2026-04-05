<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DemoGuardTest extends TestCase
{
    protected function setUp(): void
    {
        require_once __DIR__ . '/../../middleware/DemoGuard.php';
    }

    public function testAllowsPostForDemoUser(): void
    {
        $_SESSION = ['is_demo' => true];
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $result = \DemoGuard::check();
        $this->assertTrue($result['allowed']);
    }

    public function testAllowsPutForDemoUser(): void
    {
        $_SESSION = ['is_demo' => true];
        $_SERVER['REQUEST_METHOD'] = 'PUT';

        $result = \DemoGuard::check();
        $this->assertTrue($result['allowed']);
    }

    public function testAllowsDeleteForDemoUser(): void
    {
        $_SESSION = ['is_demo' => true];
        $_SERVER['REQUEST_METHOD'] = 'DELETE';

        $result = \DemoGuard::check();
        $this->assertTrue($result['allowed']);
    }

    public function testAllowsGetForDemoUser(): void
    {
        $_SESSION = ['is_demo' => true];
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $result = \DemoGuard::check();
        $this->assertTrue($result['allowed']);
    }

    public function testBlocksPasswordChangeForDemoUser(): void
    {
        $_SESSION = ['is_demo' => true];
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $result = \DemoGuard::check('auth', 'password');
        $this->assertFalse($result['allowed']);
    }

    public function testBlocksAdminForDemoUser(): void
    {
        $_SESSION = ['is_demo' => true];
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $result = \DemoGuard::check('admin');
        $this->assertFalse($result['allowed']);
    }

    public function testAllowsPostForNonDemoUser(): void
    {
        $_SESSION = ['is_demo' => false];
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $result = \DemoGuard::check();
        $this->assertTrue($result['allowed']);
    }

    public function testAllowsWhenNoDemoFlag(): void
    {
        $_SESSION = [];
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $result = \DemoGuard::check();
        $this->assertTrue($result['allowed']);
    }
}
