<?php
$siteUrl = "https://play.simcycreative.com/signer";
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "your_database_name";

// errors switch 
error_reporting(0);


//////////////////////////////////////////////
/////////// END OF EDITTING/////////////////
//////////////////////////////////////////////

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get system settings
$sql = "SELECT name, logo, favicon FROM settings where id = 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
                    $systemName= $row['name'];
                    $systemLogo= $row['logo'];
                    $systemFavicon= $row['favicon'];
                }
            }else{
            	echo "System Settings missing!";
            	exit();
            }
?>