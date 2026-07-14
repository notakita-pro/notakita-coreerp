@echo off
title CoreERP Sync v1.1
color 0A

REM =====================================
REM CoreERP Sync v1.1
REM =====================================

set PROJECT=C:\github\notakita-github

cls
echo.
echo ==========================================
echo          CoreERP Sync v1.1
echo ==========================================
echo.

cd /d "%PROJECT%"

echo Repository :
echo %PROJECT%
echo.

echo Branch :
git branch --show-current
echo.

echo ------------------------------------------
echo Changed Files
echo ------------------------------------------

git status --short

echo.

for /f %%i in ('git status --porcelain ^| find /c /v ""') do set COUNT=%%i

if "%COUNT%"=="0" (
    echo ==========================================
    echo Repository sudah sinkron.
    echo ==========================================
    echo.
    pause
    exit /b
)

echo.
set /p MSG=Commit Message : 

if "%MSG%"=="" (
    echo.
    echo Commit dibatalkan.
    pause
    exit /b
)

echo.
echo ==========================================
echo Adding files...
echo ==========================================
git add .

echo.
echo ==========================================
echo Commit...
echo ==========================================
git commit -m "%MSG%"

if errorlevel 1 (
    echo.
    echo Commit gagal.
    pause
    exit /b
)

echo.
echo ==========================================
echo Push Github...
echo ==========================================
git push

if errorlevel 1 (
    echo.
    echo Push gagal.
    pause
    exit /b
)

echo.
echo ==========================================
echo      CoreERP berhasil disinkronkan
echo ==========================================
echo.

pause