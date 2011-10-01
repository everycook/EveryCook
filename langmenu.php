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
	$db_server = 'localhost';
	$db_name = '30608_everycook';
	$db_user = 'root';
	$db_passwort = 'test';
	$db = @ mysql_connect ( $db_server, $db_user, $db_passwort );
	$db_select = @ mysql_select_db( $db_name );
	mysql_query("SET NAMES 'utf8'");
	$sql = 'SELECT * FROM interface_menu WHERE IME_LANG = "'.$language.'"';
	$ergebnis = mysql_query( $sql );
	$row=mysql_fetch_assoc($ergebnis);
	echo 'fields["IME_LOGIN"]="'.addcslashes ( $row['IME_LOGIN'], '"' ).'";';
	echo 'fields["IME_SETTINGS"]="'.addcslashes ( $row['IME_SETTINGS'], '"' ).'";';
	echo 'fields["IME_LANGSEL"]="'.addcslashes ( $row['IME_LANGSEL'], '"' ).'";';
?>