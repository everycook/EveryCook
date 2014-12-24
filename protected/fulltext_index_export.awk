BEGIN {
	print "use " dbname ";"
	print "INSERT INTO recipe_index_data (RID_LANG,RID_COUNT,RID_TEXT) VALUES"
}
{
	print (NR == 1?"":",") "('" language "'," $1 ",'" $3 "')"
}
END {
	print ";"
}