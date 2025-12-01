@echo off
echo ========================================
echo Ejecutando Pruebas End-to-End (Sistema)
echo ========================================
echo Running E2E Tests...
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.e2e.xml --testdox
if %errorlevel% neq 0 (
    echo Tests failed!
    exit /b %errorlevel%
)
echo ========================================
echo Pruebas E2E completadas!
echo ========================================
pause
