<?php
    include 'lang.php';																		//includes the database connection!
    $email=$_GET['email'];																	//get the email adress!
    dbset();																				//function to connect the database!
    $ergebnis = mysql_query("SELECT * FROM profiles WHERE PRF_EMAIL='".$email."'");			//db query looks for the email adress in the database!
    echo json_encode(mysql_num_rows($ergebnis));											//"returns": 1 for exists, 0 for doesnt exist!
?>