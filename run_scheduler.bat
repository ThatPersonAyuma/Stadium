@echo off
cd /d C:\Homework\Belajar\Laravel\Stadium
php artisan schedule:run
@REM create task command: schtasks /create /sc daily /st 00:00 /tn "LaravelScheduler" /tr "C:\Homework\Belajar\Laravel\Stadium\run_scheduler.bat" /f
@REM schtasks /delete /tn "LaravelScheduler" /f
