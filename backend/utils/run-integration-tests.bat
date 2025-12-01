@echo off
echo ========================================
echo Configurando Base de Datos de Prueba
echo ========================================

REM Crear base de datos de prueba
C:\xampp\mysql\bin\mysql.exe -u root < integration\setup_test_db.sql

if %ERRORLEVEL% NEQ 0 (
    echo Error al crear base de datos de prueba
    pause
    exit /b 1
)

echo Base de datos creada exitosamente!
echo.

echo ========================================
echo Ejecutando Pruebas de Integración
echo ========================================
echo Running Integration Tests...
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.integration.xml --testdox
if %errorlevel% neq 0 (
    echo Tests failed!
    exit /b %errorlevel%
)
echo ========================================
echo Pruebas completadas!
echo ========================================
pause
