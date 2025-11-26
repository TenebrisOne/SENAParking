<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../models/Access.php';

class AccessTest extends TestCase
{
    public function testCreateSuccess()
    {
        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockConn = $this->createMock(mysqli::class);

        $mockConn->method('prepare')->willReturn($mockStmt);
        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->expects($this->once())->method('bind_param');
        $mockStmt->expects($this->once())->method('close');

        $access = new Access($mockConn);
        $access->id_vehiculo = 1;
        $access->id_userSys = 1;
        $access->tipo_accion = 'entrada';
        $access->fecha_hora = '2023-10-27 10:00:00';
        $access->espacio_asignado = 5;

        $result = $access->create();

        $this->assertTrue($result);
    }

    public function testGetPlacaPorValorFound()
    {
        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockResult = $this->createMock(mysqli_result::class);
        $mockConn = $this->createMock(mysqli::class);

        $mockResult->method('fetch_assoc')->willReturn(['placa' => 'ABC-123']);
        $mockStmt->method('get_result')->willReturn($mockResult);
        $mockStmt->method('execute')->willReturn(true);
        $mockConn->method('prepare')->willReturn($mockStmt);

        $getPlaca = new GetPlaca($mockConn);
        $result = $getPlaca->getPlacaPorValor('ABC-123');

        $this->assertEquals('ABC-123', $result);
    }

    public function testGetPlacaPorValorNotFound()
    {
        $mockStmt = $this->createMock(mysqli_stmt::class);
        $mockResult = $this->createMock(mysqli_result::class);
        $mockConn = $this->createMock(mysqli::class);

        $mockResult->method('fetch_assoc')->willReturn(null);
        $mockStmt->method('get_result')->willReturn($mockResult);
        $mockStmt->method('execute')->willReturn(true);
        $mockConn->method('prepare')->willReturn($mockStmt);

        $getPlaca = new GetPlaca($mockConn);
        $result = $getPlaca->getPlacaPorValor('XYZ-999');

        $this->assertNull($result);
    }
}
