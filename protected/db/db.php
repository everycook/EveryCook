<?php
function dbset() {
        
    $db_server = 'localhost';
    $db_name = '30608_everycook';
    $db_user = '30608_everycook';
    $db_passwort = '3veryC00k';
    $db = @ mysql_connect ($db_server,$db_user,$db_passwort);
    $db_select = @ mysql_select_db($db_name);
    mysql_query("SET NAMES 'utf8'");
}

function langlist() {
/*
    $connection=new CDbConnection('mysql:host=localhost;dbname=30608_everycook','30608_everycook','3veryC00k');
    $connection->active=true;
    $sql = 'SELECT * FROM interface_menu';
    $command=$connection->createCommand($sql);
    foreach($reader as $row){
        echo $row[1];
    }
    */


    $lang_array;            
    dbset();
    $sql = 'SELECT * FROM interface_menu';
    $ergebnis = mysql_query($sql);
    while ($row=mysql_fetch_assoc($ergebnis)){
        echo '<a href="#" onClick="getlang(\''.addslashes($row['IME_LANG']).'\');">';
        echo $row['IME_LANGNAME'];
        echo '</a>';
        echo "<br>";
    }
    
}
?>
