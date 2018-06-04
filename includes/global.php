<?php
error_reporting(0);
include("../config.php");  


// check if logged in
session_start();
if(isset($_SESSION["userId"]) and isset($_SESSION["companyId"]) and isset($_SESSION["role"])){
	$userId = $_SESSION["userId"];
	$companyId = $_SESSION["companyId"];
	$role = $_SESSION["role"];
}else{
	echo '<script type="text/javascript">	window.location = "login?e=login" </script>';
	exit();
}

// Get user details
$sql = "SELECT fname, lname, avatar, email, lastnotification FROM users where id = $userId";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
                    $firstName= $row['fname'];
                    $fullName= $row['fname']." ".$row['lname'];
                    $userAvatar= $row['avatar'];
                    $userEmail= $row['email'];
                    $lastNotification= $row['lastnotification'];
                }
            }else{
            	echo "Something went wrong!";
            	exit();
            }

if (empty($userAvatar)) {
    $userAvatar = "avatar.png";
}
?>