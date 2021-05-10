<?php

/* DEV */
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "lo07";

$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);
// On utilise PDO pour la base de donnée
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password, $options);
/**
// set the PDO error mode to exception
$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 **/