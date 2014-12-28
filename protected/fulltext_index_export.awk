BEGIN {
	print "use " dbname ";"
	print "SET NAMES 'utf8' COLLATE 'utf8_general_ci';"
	print "INSERT INTO recipe_index_data (RID_LANG,RID_COUNT,RID_TEXT) VALUES"
}
{
	print (NR == 1?"":",") "('" language "'," $1 ",'" $3 "')"
}
END {
	print ";"
}