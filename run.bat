@echo ON

start /wait "" "%~dp0/unzip" "%~dp0/php.zip"

:start /wait "" "%~dp0/php.exe" "%~dp0/hallo.php"

:start /wait "" %~dp0/php.exe %~dp0/decrypt.php %1 %2

%~dp0\php.exe %~dp0\decrypt.php %1 %2