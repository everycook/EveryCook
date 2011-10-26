<?php
	include 'lang.php';
	$user=$_GET['user'];
	$pass=$_GET['pass'];
	dbset();
	$sql = '';
	echo $sql;
	$ergebnis = mysql_query("SELECT * FROM profiles WHERE PRF_NICK='".$user."' AND PRF_PW='".$pass."'");
	echo json_encode(mysql_num_rows($ergebnis));
	
?>