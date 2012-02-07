<?php
function dbset() {
        
    $db_server = 'localhost';
    $db_name = '***';
    $db_user = '***';
    $db_passwort = '***';
    $db = @ mysql_connect ($db_server,$db_user,$db_passwort);
    $db_select = @ mysql_select_db($db_name);
    mysql_query("SET NAMES 'utf8'");
}

function langlist() {
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
