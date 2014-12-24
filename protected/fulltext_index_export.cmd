@echo off
set dbname=%1
if "%dbname%" == "" (
	echo please specify dbname as param.
	exit /b 1
)
set mysqlpath=C:\Programme\xampp\mysql
set dumppath=%mysqlpath%\bin\myisam_ftdump
set datapath=%mysqlpath%\data\%dbname%\recipes
set mysqlbin=%mysqlpath%\bin\mysql

%mysqlbin% -uroot -e "flush tables;"
%dumppath% -c %datapath% 1 | gawk -v language=EN_GB -v dbname=%dbname% -f fulltext_index_export.awk > recipes_index_en.sql
%dumppath% -c %datapath% 2 | gawk -v language=DE_CH -v dbname=%dbname% -f fulltext_index_export.awk > recipes_index_de.sql


%mysqlbin% -uroot %dbname% -e "TRUNCATE TABLE `recipe_index_data`;"
%mysqlbin% -uroot < recipes_index_en.sql
%mysqlbin% -uroot < recipes_index_de.sql

