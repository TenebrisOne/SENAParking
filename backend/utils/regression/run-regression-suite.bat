@echo off
REM ========================================
REM Suite Completa de Regresión
REM Ejecuta TODOS los tests del proyecto
REM ========================================

SET TIMESTAMP=%date:~-4%%date:~3,2%%date:~0,2%_%time:~0,2%%time:~3,2%%time:~6,2%
SET TIMESTAMP=%TIMESTAMP: =0%
SET REPORT_DIR=backend\utils\regression\reports\%TIMESTAMP%

echo ========================================
echo SUITE DE REGRESION - SENAParking
echo ========================================
echo.
echo Timestamp: %TIMESTAMP%
echo.
echo Esta suite ejecutará TODOS los tests:
echo   - Unitarios (34 tests)
echo   - Integración (15 tests)
echo   - E2E (7 tests)
echo   - Total: 56 tests
echo.
echo Esto puede tomar varios minutos...
echo.
pause

REM Crear directorio de reportes
if not exist "%REPORT_DIR%" mkdir "%REPORT_DIR%"

echo.
echo ========================================
echo [1/3] Ejecutando Pruebas Unitarias
echo ========================================
cd backend\utils
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.unit.xml --log-junit ..\..\%REPORT_DIR%\unit-results.xml --testdox > ..\..\%REPORT_DIR%\unit-output.txt 2>&1
SET UNIT_EXIT=%ERRORLEVEL%
cd ..\..

if %UNIT_EXIT% EQU 0 (
    echo ✅ Pruebas Unitarias: PASADAS
) else (
    echo ❌ Pruebas Unitarias: FALLIDAS
)

echo.
echo ========================================
echo [2/3] Ejecutando Pruebas de Integración
echo ========================================
cd backend\utils
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.integration.xml --log-junit ..\..\%REPORT_DIR%\integration-results.xml --testdox > ..\..\%REPORT_DIR%\integration-output.txt 2>&1
SET INTEGRATION_EXIT=%ERRORLEVEL%
cd ..\..

if %INTEGRATION_EXIT% EQU 0 (
    echo ✅ Pruebas de Integración: PASADAS
) else (
    echo ❌ Pruebas de Integración: FALLIDAS
)

echo.
echo ========================================
echo [3/3] Ejecutando Pruebas E2E
echo ========================================
cd backend\utils
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.e2e.xml --log-junit ..\..\%REPORT_DIR%\e2e-results.xml --testdox > ..\..\%REPORT_DIR%\e2e-output.txt 2>&1
SET E2E_EXIT=%ERRORLEVEL%
cd ..\..

if %E2E_EXIT% EQU 0 (
    echo ✅ Pruebas E2E: PASADAS
) else (
    echo ❌ Pruebas E2E: FALLIDAS
)

echo.
echo ========================================
echo Generando Reporte de Regresión
echo ========================================
C:\xampp\php\php.exe backend\utils\regression\generate-report.php %REPORT_DIR%

echo.
echo ========================================
echo RESUMEN DE EJECUCIÓN
echo ========================================
echo.
echo Unitarias:    %UNIT_EXIT%
echo Integración:  %INTEGRATION_EXIT%
echo E2E:          %E2E_EXIT%
echo.
echo Reportes guardados en: %REPORT_DIR%
echo.

REM Calcular exit code total
SET /A TOTAL_EXIT=%UNIT_EXIT%+%INTEGRATION_EXIT%+%E2E_EXIT%

if %TOTAL_EXIT% EQU 0 (
    echo ========================================
    echo ✅ TODAS LAS PRUEBAS PASARON
    echo ========================================
    echo.
    echo No se detectaron regresiones.
) else (
    echo ========================================
    echo ❌ SE DETECTARON REGRESIONES
    echo ========================================
    echo.
    echo Revisa los reportes para más detalles.
)

echo.
pause
exit /b %TOTAL_EXIT%
