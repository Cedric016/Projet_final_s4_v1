@echo off
cd /d "%~dp0"
start "" php -S 127.0.0.1:8081 -t public public/index.php
timeout /t 3 >nul
start "" http://127.0.0.1:8081/index.php/dashboard
