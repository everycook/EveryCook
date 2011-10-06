<?php
	include 'includes/db.php';
	$language=$_GET['language'];
	if (strlen($language)==2){
	$view = $_GET['view'];
	dbx();
	$sql = 'SELECT * FROM interface_textes WHERE ITE_LANG = "'.$language.'" AND ITE_VIEW = "'.$view.'"';
	$ergebnis = mysql_query( $sql );
	$row=mysql_fetch_assoc($ergebnis);
	echo 'fields["ITE_BOTL_CONTENT"]="'.addcslashes ( $row['ITE_BOTL_CONTENT'], '"' ).'";';
	echo 'fields["ITE_BOTM_CONTENT"]="'.addcslashes ( $row['ITE_BOTM_CONTENT'], '"' ).'";';
	echo 'fields["ITE_BOTR_CONTENT"]="'.addcslashes ( $row['ITE_BOTR_CONTENT'], '"' ).'";';
	echo 'fields["ITE_MIDL"]="'.addcslashes ( $row['ITE_MIDL'], '"' ).'";';
	echo 'fields["ITE_MIDR"]="'.addcslashes ( $row['ITE_MIDR'], '"' ).'";';
	$sql = 'SELECT * FROM interface_menu WHERE IME_LANG = "'.$language.'"';
	$ergebnis = mysql_query( $sql );
	$row=mysql_fetch_assoc($ergebnis);
	echo 'fields["IME_LOGIN"]="'.addcslashes ( $row['IME_LOGIN'], '"' ).'";';
	echo 'fields["IME_SETTINGS"]="'.addcslashes ( $row['IME_SETTINGS'], '"' ).'";';
	echo 'fields["IME_LANGSEL"]="'.addcslashes ( $row['IME_LANGSEL'], '"' ).'";';
	}
?>
