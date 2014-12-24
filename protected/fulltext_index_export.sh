#/bin/sh
dbname=$1
if [ "$dbname" == "" ]; then
	echo please specify dbname as param.
	exit 1;
fi
mysqlpath=/usr/bin
dumppath=$mysqlpath/myisam_ftdump
datapath=/var/lib/mysql/$dbname/recipes
mysqlbin=$mysqlpath/mysql

$mysqlbin -uec -e "flush tables;"
$dumppath -c $datapath 1 | gawk -v language=EN_GB -v dbname=$dbname -f fulltext_index_export.awk > recipes_index_en.sql
$dumppath -c $datapath 2 | gawk -v language=DE_CH -v dbname=$dbname -f fulltext_index_export.awk > recipes_index_de.sql

$mysqlbin -uec $dbname -e "TRUNCATE TABLE recipe_index_data;"
$mysqlbin -uec < recipes_index_en.sql
$mysqlbin -uec < recipes_index_de.sql

