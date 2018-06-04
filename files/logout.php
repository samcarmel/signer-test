<?php
error_reporting(0);
session_start();
session_unset("userId");
session_unset("companyId");
session_unset("role");
header("Location: login?e=logout");
?>
