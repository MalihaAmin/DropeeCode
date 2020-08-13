<?php
/* Database connection settings */
	$servername = "localhost";
    $username = "id14598184_root";		//put your phpmyadmin username.(default is "root")
    $password = "/tuH%wE{cvQoU!+4";			//if your phpmyadmin has a password put it here.(default is "root")
    $dbname = "id14598184_dropee_test";
    
	$conn = new mysqli($servername, $username, $password, $dbname);
	global $conn;
	if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }
?>