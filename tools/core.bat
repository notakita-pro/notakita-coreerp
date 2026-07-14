@echo off

REM =====================================
REM CoreERP CLI v1.1
REM =====================================

if "%1"=="ssh" goto ssh
if "%1"=="snapshot" goto snapshot
if "%1"=="sync" goto sync
if "%1"=="help" goto help

goto help

:ssh
ssh gaes
goto end

:snapshot
call "%~dp0snapshot.bat"
goto end

:sync
call "%~dp0core-sync.bat"
goto end

:help
echo.
echo =====================================
echo          CoreERP CLI
echo =====================================
echo.
echo Penggunaan:
echo.
echo    core ssh
echo    core snapshot
echo    core sync
echo    core help
echo.
echo =====================================

:end