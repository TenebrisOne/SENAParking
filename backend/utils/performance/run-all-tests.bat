@echo off
REM ========================================
REM Ejecutar TODAS las Pruebas de Carga
REM ========================================

echo ========================================
echo Suite Completa de Pruebas de Rendimiento
echo ========================================
echo.
echo Este script ejecutará pruebas de carga en todos los endpoints:
echo - Login
echo - Registro de Vehículos
echo - Registro de Accesos
echo.
echo ADVERTENCIA: Esto puede tomar varios minutos y generar
echo miles de peticiones a la base de datos.
echo.
pause

echo.
echo ========================================
echo [1/3] Probando Login Endpoint
echo ========================================
call backend\utils\performance\test-login-load.bat

echo.
echo ========================================
echo [2/3] Probando Vehicle Registration
echo ========================================
call backend\utils\performance\test-vehicle-load.bat

echo.
echo ========================================
echo [3/3] Generando Resumen
echo ========================================
C:\xampp\php\php.exe backend\utils\performance\generate-summary.php

echo.
echo ========================================
echo Suite de Pruebas Completada!
echo ========================================
echo.
echo Revisa los reportes en: backend\utils\performance\reports\
echo.
pause
