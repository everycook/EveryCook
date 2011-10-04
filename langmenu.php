<?php
	include 'includes/db.php';
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
	dbx();
	$sql = 'SELECT * FROM interface_menu WHERE IME_LANG = "'.$language.'"';
	$ergebnis = mysql_query( $sql );
	$row=mysql_fetch_assoc($ergebnis);
	echo 'fields["IME_LOGIN"]="'.addcslashes ( $row['IME_LOGIN'], '"' ).'";';
	echo 'fields["IME_SETTINGS"]="'.addcslashes ( $row['IME_SETTINGS'], '"' ).'";';
	echo 'fields["IME_LANGSEL"]="'.addcslashes ( $row['IME_LANGSEL'], '"' ).'";';
?>