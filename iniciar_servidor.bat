@echo off
setlocal
chcp 65001 >nul
title Sistema de Alerta de Faltas - Servidor Local

set "PROJECT_DIR=%~dp0"
set "PHP_EXE=%PROJECT_DIR%php\php.exe"
set "HOST=127.0.0.1"
set "PORT=8000"

if not exist "%PHP_EXE%" (
    echo.
    echo [ERRO] PHP portatil nao encontrado em:
    echo %PHP_EXE%
    echo.
    pause
    exit /b 1
)

echo.
echo ================================================
echo   Sistema de Alerta de Faltas Escolares
echo ================================================
echo.
echo Servidor: http://localhost:%PORT%
echo Para encerrar, pressione CTRL+C ou feche esta janela.
echo.

start "" "http://localhost:%PORT%"
"%PHP_EXE%" -S %HOST%:%PORT% -t "%PROJECT_DIR%."

echo.
echo O servidor foi encerrado.
pause
endlocal
