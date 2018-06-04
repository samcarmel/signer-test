<?php
session_start();
if(isset($_SESSION["userId"]) and isset($_SESSION["companyId"]) and isset($_SESSION["role"])){
header("Location: dashboard");
}else{	
header("Location: login");
}
?>