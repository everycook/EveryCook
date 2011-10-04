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
	$view = $_GET['view'];
	dbx();
	$sql = 'SELECT * FROM interface_textes WHERE ITE_LANG = "'.$language.'" AND ITE_VIEW = "'.$view.'"';
	$ergebnis = mysql_query( $sql );
	$row=mysql_fetch_assoc($ergebnis);
	echo 'fields["ITE_BOTL"]="'.addcslashes ( $row['ITE_BOTL'], '"' ).'";';
	echo 'fields["ITE_BOTM"]="'.addcslashes ( $row['ITE_BOTM'], '"' ).'";';
	echo 'fields["ITE_BOTR"]="'.addcslashes ( $row['ITE_BOTR'], '"' ).'";';
	echo 'fields["ITE_MIDL"]="'.addcslashes ( $row['ITE_MIDL'], '"' ).'";';
	echo 'fields["ITE_MIDR"]="'.addcslashes ( $row['ITE_MIDR'], '"' ).'";';

?>
