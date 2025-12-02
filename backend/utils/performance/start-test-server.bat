@echo off
REM ========================================
REM Servidor PHP para Pruebas de Rendimiento
REM ========================================

echo Iniciando servidor PHP en puerto 8080...
echo URL: http://localhost:8080
echo.
echo Presiona Ctrl+C para detener el servidor
echo.

cd ..\..\..
C:\xampp\php\php.exe -S localhost:8080 -t .
