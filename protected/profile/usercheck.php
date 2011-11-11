<?php
    include 'lang.php';
    $user=$_GET['user'];
    dbset();
    $ergebnis = mysql_query("SELECT * FROM profiles WHERE PRF_NICK='".$user."'");
    echo json_encode(mysql_num_rows($ergebnis));
    
?>