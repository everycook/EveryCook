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

:: for some reason the dump use 0xE3 first byte for UTF-8 instat of 0xC3, so wee need to switch them
call jEval.cmd "String.fromCharCode(0xe3)" > hex_e3
call jEval.cmd "String.fromCharCode(0xc3)" > hex_c3
call jEval.cmd "String.fromCharCode(0xEF,0xBB,0xBF)" > utf8_bom
set /p hex_e3=<hex_e3
set /p hex_c3=<hex_c3

%mysqlbin% -uroot -e "flush tables;"
%dumppath% -c %datapath% 1 | gawk -v language=EN_GB -v dbname=%dbname% -f fulltext_index_export.awk > recipes_index_en.sql
cat recipes_index_en.sql | sed "s/%hex_e3%/%hex_c3%/" > recipes_index_en2.sql 
cat utf8_bom > recipes_index_en.sql 
cat recipes_index_en2.sql >> recipes_index_en.sql
%dumppath% -c %datapath% 2 | gawk -v language=DE_CH -v dbname=%dbname% -f fulltext_index_export.awk > recipes_index_de.sql
cat recipes_index_de.sql | sed "s/%hex_e3%/%hex_c3%/" > recipes_index_de2.sql 
cat utf8_bom > recipes_index_de.sql 
cat recipes_index_de2.sql >> recipes_index_de.sql

%mysqlbin% -uroot %dbname% -e "TRUNCATE TABLE `recipe_index_data`;"
%mysqlbin% -uroot --default-character-set=utf8 < recipes_index_en.sql
%mysqlbin% -uroot --default-character-set=utf8 < recipes_index_de.sql

