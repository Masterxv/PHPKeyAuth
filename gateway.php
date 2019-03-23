<?php
require "auth.php"

$servername = "localhost";
$username = "username";
$password = "password";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// create database
$sql = "CREATE TABLE Users ( 
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
    token VARCHAR(10) NOT NULL, 
    hwid VARCHAR(10) NOT NULL,  
    ban INT(1), 
    date TIMESTAMP 
    )"; 

// check if the database was successfully created
if ($conn->query($sql) === TRUE){
    echo "Table Users created successfully!";
}else{
    echo "Error creating Users" . $conn->error;

}

Auth auth($conn);


echo "Connected successfully";

$conn->close();
?>