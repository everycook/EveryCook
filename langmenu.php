<?php
$language=$_GET['language'];
if($language=='English'){
	$language = "EN";
}
else if($language=='Deutsch'){
	$language = "DE";
}
else if($language=='Francais'){
	$language = "FR";
}

//echo "alert(\"test\");";
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

$sql = 'SELECT * FROM interface_menu WHERE IME_LANG = "'.$language.'"';

$ergebnis = mysql_query( $sql );

$row=mysql_fetch_assoc($ergebnis);

echo 'fields["IME_LOGIN"]="'.addcslashes ( $row['IME_LOGIN'], '"' ).'";';
echo 'fields["IME_SETTINGS"]="'.addcslashes ( $row['IME_SETTINGS'], '"' ).'";';
echo 'fields["IME_LANGSEL"]="'.addcslashes ( $row['IME_LANGSEL'], '"' ).'";';

?>