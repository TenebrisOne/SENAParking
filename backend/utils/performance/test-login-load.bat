@echo off
REM ========================================
REM Pruebas de Carga - Login Endpoint
REM ========================================

SET AB=C:\xampp\apache\bin\ab.exe
SET URL=http://localhost/SENAParking/backend/controllers/LoginController.php
SET REPORT_DIR=backend\utils\performance\reports

echo ========================================
echo Pruebas de Carga - Login Endpoint
echo ========================================
echo.

REM Crear directorio de reportes si no existe
if not exist "%REPORT_DIR%" mkdir "%REPORT_DIR%"

echo [1/4] Carga Baja: 100 requests, 10 concurrentes
%AB% -n 100 -c 10 -p backend\utils\performance\payloads\login.txt -T "application/x-www-form-urlencoded" %URL% > %REPORT_DIR%\login_low.txt
echo Completado. Reporte: %REPORT_DIR%\login_low.txt
echo.

echo [2/4] Carga Media: 500 requests, 50 concurrentes
%AB% -n 500 -c 50 -p backend\utils\performance\payloads\login.txt -T "application/x-www-form-urlencoded" %URL% > %REPORT_DIR%\login_medium.txt
echo Completado. Reporte: %REPORT_DIR%\login_medium.txt
echo.

echo [3/4] Carga Alta: 1000 requests, 100 concurrentes
%AB% -n 1000 -c 100 -p backend\utils\performance\payloads\login.txt -T "application/x-www-form-urlencoded" %URL% > %REPORT_DIR%\login_high.txt
echo Completado. Reporte: %REPORT_DIR%\login_high.txt
echo.

echo [4/4] Estrés: 2000 requests, 200 concurrentes
%AB% -n 2000 -c 200 -p backend\utils\performance\payloads\login.txt -T "application/x-www-form-urlencoded" %URL% > %REPORT_DIR%\login_stress.txt
echo Completado. Reporte: %REPORT_DIR%\login_stress.txt
echo.

echo ========================================
echo Todas las pruebas completadas!
echo Reportes guardados en: %REPORT_DIR%
echo ========================================
pause
