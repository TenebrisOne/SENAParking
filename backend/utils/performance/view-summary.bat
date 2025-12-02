@echo off
REM ========================================
REM Ver Resumen de Pruebas de Rendimiento
REM ========================================

echo ========================================
echo Resumen de Pruebas de Rendimiento
echo ========================================
echo.

SET REPORT_FILE=backend\utils\performance\reports\summary.html

if not exist "%REPORT_FILE%" (
    echo ❌ No se encontró el archivo de resumen.
    echo.
    echo Ejecuta primero las pruebas con:
    echo   - test-login-load.bat
    echo   - test-vehicle-load.bat
    echo   - run-all-tests.bat
    echo.
    echo Luego genera el resumen con:
    echo   C:\xampp\php\php.exe backend\utils\performance\generate-summary.php
    echo.
    pause
    exit /b 1
)

echo Abriendo resumen en navegador...
start %REPORT_FILE%

echo.
echo ========================================
echo Resumen abierto en navegador
echo ========================================
echo.
echo Archivos de reporte disponibles:
dir /B backend\utils\performance\reports\*.txt
echo.
pause
