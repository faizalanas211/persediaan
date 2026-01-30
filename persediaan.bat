@echo off
title Persediaan - Laravel

:: Jalankan Laragon (service auto start)
start "" "E:\laragon\laragon.exe"

:: Tunggu lebih lama biar Apache siap
timeout /t 8 >nul

:: Buka web
start http://persediaan.test

exit
