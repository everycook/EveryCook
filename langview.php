<?php
$language=$_GET['language'];
if($language=='English'){
	$language = "EN";
}
else if($language=='Deutsch'){
	$language = "DE";
}
else if($language=='Français'){
	$language = "FR";
}

$view = $_GET['view'];

/* Datenbankserver - In der Regel die IP */
$db_server = 'localhost';
/* Datenbankname */
$db_name = '30608_everycook';
/* Datenbankuser */
$db_user = 'root';
/* Datenbankpasswort */
$db_passwort = 'test';
         
/* Erstellt Connect zu Datenbank */
$db = @ mysql_connect ( $db_server, $db_user, $db_passwort );

$db_select = @ mysql_select_db( $db_name );
mysql_query("SET NAMES 'utf8'");

$sql = 'SELECT * FROM interface_textes WHERE ITE_LANG = "'.$language.'" AND ITE_VIEW = "'.$view.'"';

$ergebnis = mysql_query( $sql );

$row=mysql_fetch_assoc($ergebnis);

echo 'fields["ITE_BOTL"]="'.addcslashes ( $row['ITE_BOTL'], '"' ).'";';
echo 'fields["ITE_BOTM"]="'.addcslashes ( $row['ITE_BOTM'], '"' ).'";';
echo 'fields["ITE_BOTR"]="'.addcslashes ( $row['ITE_BOTR'], '"' ).'";';
echo 'fields["ITE_MIDL"]="'.addcslashes ( $row['ITE_MIDL'], '"' ).'";';
echo 'fields["ITE_MIDR"]="'.addcslashes ( $row['ITE_MIDR'], '"' ).'";';

?>
