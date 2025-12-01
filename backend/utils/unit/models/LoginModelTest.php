<?php
use PHPUnit\Framework\TestCase;

class LoginModelTest extends TestCase
{
    public function testPasswordHashingWorks()
    {
        $password = 'test123';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertTrue(password_verify($password, $hash));
        $this->assertFalse(password_verify('wrong', $hash));
    }
    
    public function testPasswordHashIsSecure()
    {
        $password = 'mypassword';
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        $this->assertNotEquals($password, $hash);
        $this->assertGreaterThan(50, strlen($hash));
    }
}
