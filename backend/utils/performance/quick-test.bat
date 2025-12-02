@echo off
REM ========================================
REM Prueba Rápida de Rendimiento (Quick Test)
REM ========================================

SET AB=C:\xampp\apache\bin\ab.exe
SET URL=http://localhost/SENAParking/backend/controllers/LoginController.php

echo ========================================
echo Prueba Rápida de Rendimiento - Login
echo ========================================
echo.
echo Esta es una prueba rápida con solo 50 requests
echo para verificar que todo funciona correctamente.
echo.

%AB% -n 50 -c 5 -p backend\utils\performance\payloads\login.txt -T "application/x-www-form-urlencoded" %URL%

echo.
echo ========================================
echo Prueba completada!
echo ========================================
pause
