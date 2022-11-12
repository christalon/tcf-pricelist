<?php


//require_once realpath(__DIR__ . "/vendor/autoload.php");

//use Dotenv\Dotenv;

//$dotenv = Dotenv::createImmutable(__DIR__);
//$dotenv->load();

//DATABASE CONNECTION VARIABLES
$host = "localhost";//getenv("DB_HOST"); // Host name
$username = "root"; //getenv("DB_USERNAME"); // Mysql username
$password = "";//getenv("DB_PASSWORD"); // Mysql password
$db_name = "tcfpricelist";//getenv("DB_NAME"); // Database name

// Create connection
$conn = new mysqli($host, $username, $password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
   }