@echo off
cd /d "%~dp0"
cmd /k python "C:\xampp\htdocs\vmops\init.py" > initout.log
