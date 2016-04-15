@ECHO OFF
@TITLE test

for /f "tokens=1,2 delims==" %%G in (settings.ini) do set %%G=%%H
:Menu
echo 1 - Proxy update
echo 2 - Spam
echo 3 - Settings
set /p "auswahl=Bitte auswahl treffen :"
set /a num=%auswahl%

if %num%==1 (
 goto Update
) else if %num%==2 (
 goto Vote
) else if %num%==3 (
 goto Settings
) else (
 echo fail
)
pause

:Settings
cls
set /p "phppath=Bitte Pfad zur php.exe angeben:"

for %%S in (phppath) do (
	setlocal enabledelayedexpansion
	type settings.ini | find /v "%%S=">settings.tmp
	move /y settings.tmp settings.ini >nul
	echo %%S=!%%S!>>settings.ini
type settings.ini
)
goto Menu
:Update
cls
echo Updating proxys...
%phppath% update_proxies.php
goto Menu
:Vote
cls
set /p "proxies=Use proxies? 0/1: "
set /a option=%proxies%
set /p "votes=Please enter amount of votes: "
set /a amount=%votes%
%phppath% spam.php %proxies% %amount%
pause 