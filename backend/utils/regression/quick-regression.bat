@echo off
REM ========================================
REM Prueba Rápida de Regresión
REM Solo ejecuta tests unitarios para verificación rápida
REM ========================================

echo ========================================
echo PRUEBA RAPIDA DE REGRESION
echo ========================================
echo.
echo Esta es una prueba rápida que solo ejecuta
echo los tests unitarios (más rápidos).
echo.
echo Para una prueba completa, usa:
echo   run-regression-suite.bat
echo.

SET TIMESTAMP=%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
SET TIMESTAMP=%TIMESTAMP: =0%
SET REPORT_DIR=backend\utils\regression\reports\quick_%TIMESTAMP%

if not exist "%REPORT_DIR%" mkdir "%REPORT_DIR%"

echo Ejecutando tests unitarios...
cd backend\utils
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.unit.xml --log-junit ..\..\%REPORT_DIR%\unit-results.xml --testdox
SET EXIT_CODE=%ERRORLEVEL%
cd ..\..

echo.
if %EXIT_CODE% EQU 0 (
    echo ========================================
    echo ✅ TESTS UNITARIOS PASARON
    echo ========================================
    echo.
    echo No se detectaron regresiones en tests unitarios.
) else (
    echo ========================================
    echo ❌ SE DETECTARON REGRESIONES
    echo ========================================
    echo.
    echo Algunos tests unitarios fallaron.
    echo Ejecuta run-regression-suite.bat para análisis completo.
)

echo.
pause
exit /b %EXIT_CODE%
