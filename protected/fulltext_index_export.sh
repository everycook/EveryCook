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

#for some reason the dump use 0xE3 first byte for UTF-8 instat of 0xC3, so wee need to switch them

 $mysqlbin -uroot -p3veryC00k! -e "flush tables;"
$dumppath -c $datapath 1 | gawk -v language=EN_GB -v dbname=$dbname -f fulltext_index_export.awk > recipes_index_en.sql
cat recipes_index_en.sql |sed -e 's/\xE3/\xC3/' > recipes_index_en2.sql 
echo -e '\xEF\xBB\xBF' > recipes_index_en.sql 
cat recipes_index_en2.sql >> recipes_index_en.sql
$dumppath -c $datapath 2 | gawk -v language=DE_CH -v dbname=$dbname -f fulltext_index_export.awk > recipes_index_de.sql
cat recipes_index_de.sql |sed -e 's/\xE3/\xC3/' > recipes_index_de2.sql
echo -e '\xEF\xBB\xBF' > recipes_index_de.sql
cat recipes_index_de2.sql >> recipes_index_de.sql

 $mysqlbin -uroot -p3veryC00k! $dbname -e "TRUNCATE TABLE recipe_index_data;"
 $mysqlbin -uroot -p3veryC00k! --default-character-set=utf8 < recipes_index_en.sql
 $mysqlbin -uroot -p3veryC00k! --default-character-set=utf8 < recipes_index_de.sql

