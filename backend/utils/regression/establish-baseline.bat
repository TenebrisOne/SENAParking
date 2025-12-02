@echo off
REM ========================================
REM Establecer Baseline de Regresión
REM ========================================

echo ========================================
echo ESTABLECER BASELINE DE REGRESION
echo ========================================
echo.
echo Este script ejecutará todos los tests y guardará
echo los resultados como la "línea base" (baseline).
echo.
echo Los futuros tests se compararán contra esta baseline
echo para detectar regresiones.
echo.
echo IMPORTANTE: Solo ejecuta esto cuando el código
echo esté en un estado estable y todos los tests pasen.
echo.
pause

SET BASELINE_DIR=backend\utils\regression\baselines\current

REM Limpiar baseline anterior
if exist "%BASELINE_DIR%" (
    echo Eliminando baseline anterior...
    rmdir /S /Q "%BASELINE_DIR%"
)

REM Crear directorio
mkdir "%BASELINE_DIR%"

echo.
echo ========================================
echo Ejecutando Suite Completa
echo ========================================

REM Ejecutar unitarias
echo [1/3] Pruebas Unitarias...
cd backend\utils
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.unit.xml --log-junit ..\..\%BASELINE_DIR%\unit-baseline.xml --testdox > ..\..\%BASELINE_DIR%\unit-baseline.txt 2>&1
cd ..\..

REM Ejecutar integración
echo [2/3] Pruebas de Integración...
cd backend\utils
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.integration.xml --log-junit ..\..\%BASELINE_DIR%\integration-baseline.xml --testdox > ..\..\%BASELINE_DIR%\integration-baseline.txt 2>&1
cd ..\..

REM Ejecutar E2E
echo [3/3] Pruebas E2E...
cd backend\utils
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.e2e.xml --log-junit ..\..\%BASELINE_DIR%\e2e-baseline.xml --testdox > ..\..\%BASELINE_DIR%\e2e-baseline.txt 2>&1
cd ..\..

REM Guardar metadata
echo Generando metadata...
C:\xampp\php\php.exe backend\utils\regression\save-baseline-metadata.php

echo.
echo ========================================
echo ✅ BASELINE ESTABLECIDA
echo ========================================
echo.
echo Baseline guardada en: %BASELINE_DIR%
echo.
echo Ahora puedes ejecutar pruebas de regresión con:
echo   run-regression-suite.bat
echo.
pause
