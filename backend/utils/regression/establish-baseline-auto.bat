@echo off
REM ========================================
REM Establecer Baseline (Sin Interacción)
REM Para uso en CI/CD o scripts automatizados
REM ========================================

SET BASELINE_DIR=backend\utils\regression\baselines\current

echo Estableciendo baseline automaticamente...

REM Limpiar baseline anterior
if exist "%BASELINE_DIR%" (
    rmdir /S /Q "%BASELINE_DIR%"
)

mkdir "%BASELINE_DIR%"

REM Ejecutar tests
cd backend\utils
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.unit.xml --log-junit ..\..\%BASELINE_DIR%\unit-baseline.xml --testdox > ..\..\%BASELINE_DIR%\unit-baseline.txt 2>&1
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.integration.xml --log-junit ..\..\%BASELINE_DIR%\integration-baseline.xml --testdox > ..\..\%BASELINE_DIR%\integration-baseline.txt 2>&1
C:\xampp\php\php.exe ..\..\vendor\bin\phpunit --configuration phpunit.e2e.xml --log-junit ..\..\%BASELINE_DIR%\e2e-baseline.xml --testdox > ..\..\%BASELINE_DIR%\e2e-baseline.txt 2>&1
cd ..\..

REM Guardar metadata
C:\xampp\php\php.exe backend\utils\regression\save-baseline-metadata.php

echo Baseline establecida en: %BASELINE_DIR%
exit /b 0
