<?php
    include 'lang.php';
    $fname=$_GET['fname'];
    $lname=$_GET['lname'];
    $uname=$_GET['uname'];
    $email=$_GET['email'];
    $pass=$_GET['pass'];
    dbset();
    $sql= "INSERT INTO profiles (PRF_FIRSTNAME, PRF_LASTNAME, PRF_NICK, PRF_EMAIL, PRF_PW) VALUES ('$fname', '$lname', '$uname', '$email', '".md5($pass)."')";
    $save = mysql_query($sql);
    echo json_encode($save);
?>