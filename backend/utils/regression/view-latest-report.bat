@echo off
REM ========================================
REM Ver Último Reporte de Regresión
REM ========================================

SET REPORTS_DIR=backend\utils\regression\reports

echo ========================================
echo REPORTES DE REGRESION
echo ========================================
echo.

REM Buscar el reporte más reciente
for /f "delims=" %%i in ('dir /b /ad /o-d "%REPORTS_DIR%" 2^>nul') do (
    set LATEST=%%i
    goto :found
)

:notfound
echo ❌ No se encontraron reportes.
echo.
echo Ejecuta run-regression-suite.bat primero.
echo.
pause
exit /b 1

:found
echo Último reporte: %LATEST%
echo.

SET REPORT_FILE=%REPORTS_DIR%\%LATEST%\regression-report.html

if exist "%REPORT_FILE%" (
    echo Abriendo reporte en navegador...
    start %REPORT_FILE%
) else (
    echo ⚠️  No se encontró regression-report.html
    echo.
    echo Archivos disponibles:
    dir /B "%REPORTS_DIR%\%LATEST%"
)

echo.
pause
