<?php
/* Database connection settings */
	$servername = "192.168.1.100";
    $username = "root";	
    $password = "";			
    $dbname = "SchoolSecuritySystem";
    
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }
?>