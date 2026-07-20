@echo off
cd /d "%~dp0"
start "" php spark serve --port 8080
timeout /t 3 >nul
start "" http://localhost:8080/index.php/dashboard
