@echo off
REM ============================================================================
REM Script para iniciar el servidor de desarrollo de GestiLimpieza
REM ============================================================================

echo.
echo ====================================================================
echo          GestiLimpieza - Iniciando Servidor
echo ====================================================================
echo.

cd /d C:\laragon\www\proyecto_personal_laravel\GestiLimpieza

REM Ejecutar servidor en puerto 8000
echo Iniciando servidor en http://localhost:8000
echo Presiona Ctrl+C para detener
echo.

php -S localhost:8000 -t public

pause
