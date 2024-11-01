<?php
    $host="localhost";
    $username="root";
    $password="";
    $dbname="ewsd_db";
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password); 
    // if ($conn) {
    //     echo "successfully connected";
    // }else{
    //     echo "Cannot connect";
    // }
?>