<?php

$servername = 'localhost';
$user = 'root';
$password = '';
$db_name = 'admin';

try{
    $conn = mysqli_connect($servername, $user, $password, $db_name,);
    echo "Connected to database";
}catch (mysqli_sql_exception $e) {
   die("Sorry connection failed: ".mysqli_connect_error());
}

?>