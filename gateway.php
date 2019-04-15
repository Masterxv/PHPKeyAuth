<?php
require "auth.php";

$servername = "localhost";
$username = "root";
$password = "";
$database = "customers";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
/* 
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

} */

$auth = new Auth($conn);
 

// -- Start

/* 
    #1 parse url params
    #2 first time setup check

    #3 ban hwid if needed
    #4 check if hwid is banned
    #5 check if client exists
    #6 getTimeLeft
    #7 if time over send false response
    #8 else 

 */
if (isset($_GET["key"]) && isset($_GET["hwid"]) && isset($_GET["ban"]))
{
    $auth->getData($_GET["key"], $_GET["hwid"], $_GET["ban"]);

    if (!$auth->userBanned())
    {
        $auth->firstTimeSetup();

        // -- 0 : DOES_EXIST, 1 : DOESNT_EXIST, 2 : BANNED
        $result = $auth->Validate();

        if ($result == 1)
        {
            if ($_GET["ban"] == 0)
            {
                if (!$auth->timeExpired())
                {
                    // send authenticated!
                    die("authenticated\n");
                }
                else
                    die("time_expired\n");
                    // send time expired response
            }
            else
            {
                $auth->banHWID($auth->getHWID());
                $auth->banReason($auth->getHWID(), "Tampering");
                die("user_banned\n");
            }
        } 
        else if ($result == 0) die("user_doesnt_exist\n");
        else if ($result == 2) die("user_banned_sharing\n");
        else if ($result == 3) die("user_invalid_key\n");
    }
    else die("user_banned\n");
}
// -- End


// echo "Connected successfully!\n";

$conn->close();
?>