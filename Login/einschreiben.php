<?php
    $dbuser = 'root';
    $dbpass = '';
    $dbname = 'silvester';
    $host ='localhost';

    try{
    $mysql = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpass);
    } catch (PDOException $e){
        echo "SQL Error: " . $e->getMessage();
    }
?>