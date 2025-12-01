@echo off
echo ========================================
echo Ejecutando Pruebas Unitarias
echo ========================================

C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.unit.xml --testdox

if %ERRORLEVEL% NEQ 0 (
    echo Error en las pruebas unitarias
    pause
    exit /b 1
)

echo.
echo ========================================
echo Pruebas Unitarias completadas!
echo ========================================
pause
