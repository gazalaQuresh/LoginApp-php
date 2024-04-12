<?php

$host = 'localhost'; // MySQL server host
$dbname = 'signup-app'; // Database name
$username = 'root'; // Database username
$password = ''; // Database password

// Connect to the database using MySQLi
$mysqli = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Display success message
echo "Connected to the database successfully!";
