<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/Vehicle.php';

class VehicleTest extends TestCase
{
    public function testRead()
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockConn = $this->createMock(PDO::class);

        $mockConn->method('prepare')->willReturn($mockStmt);
        $mockStmt->expects($this->once())->method('execute');

        $vehicle = new Vehicle($mockConn);
        $result = $vehicle->read();

        $this->assertInstanceOf(PDOStatement::class, $result);
    }

    public function testReadWithSearchTerm()
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockConn = $this->createMock(PDO::class);

        $mockConn->method('prepare')->willReturn($mockStmt);
        $mockStmt->expects($this->once())->method('execute');
        $mockStmt->expects($this->once())->method('bindParam');

        $vehicle = new Vehicle($mockConn);
        $result = $vehicle->read('search');

        $this->assertInstanceOf(PDOStatement::class, $result);
    }
}
