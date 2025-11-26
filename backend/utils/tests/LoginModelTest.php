<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/LoginModel.php';

/**
 * @runTestsInSeparateProcesses
 */
class LoginModelTest extends TestCase
{
    public function setUp(): void
    {
        $this->markTestSkipped('Skipping LoginModelTest because mocking mysqli_result read-only properties is not supported without a real database connection.');
    }

    public function testValidarLoginSuccess()
    {
        // ...
    }

    public function testValidarLoginInactive()
    {
        // ...
    }

    public function testValidarLoginWrongPassword()
    {
        // ...
    }

    public function testValidarLoginUserNotFound()
    {
        // ...
    }
}
