@echo off
title Tienda Made
echo ============================================
echo   Tienda Made - Punto de venta
echo ============================================
echo.

REM Arranca MySQL de XAMPP si no esta corriendo
tasklist /FI "IMAGENAME eq mysqld.exe" | find /I "mysqld.exe" >nul
if errorlevel 1 (
    echo Iniciando MySQL...
    start "" /B C:\xampp\mysql\bin\mysqld.exe --defaults-file=C:\xampp\mysql\bin\my.ini
    timeout /t 5 /nobreak >nul
)

echo Iniciando el servidor en http://127.0.0.1:8001 ...
start "" http://127.0.0.1:8001
cd /d "%~dp0"
php artisan serve --port=8001
pause
