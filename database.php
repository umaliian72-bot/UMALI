<?php

$host = "localhost";       
$username = "root";        
$password = "";             
$database = "student_db";   // Database name we created

$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>