<?php
	include '../db/db.php';
	function langmenu($language) {
		dbset();
		$sql = 'SELECT * FROM interface_menu WHERE IME_LANG = "'.$language.'"';
		$ergebnis = mysql_query($sql);
		$row = mysql_fetch_row($ergebnis);
		foreach($row as $value) {
			$value = addcslashes($value,"\"");
		}
		return $row;
	}
	function langview($language, $view){
		dbset();
		$sql = 'SELECT * FROM interface_textes WHERE ITE_LANG = "'.$language.'" AND ITE_VIEW = "'.$view.'"';
		$ergebnis = mysql_query($sql);
		$row = mysql_fetch_row($ergebnis);
		foreach($row as $value){
			$value = addcslashes($value,"\"");
		}
		return $row;
	}
?>
