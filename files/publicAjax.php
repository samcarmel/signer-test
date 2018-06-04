<?php
include_once("../config.php");
// ini_set('display_errors', 'ON');
// error_reporting(E_ALL);
session_start();

if (isset($_POST["action"])) {
$action = $_POST["action"];


// sign up
if ($action == "signup") {


	// check if email already exists 
	$sql = "SELECT email FROM users WHERE email='" . $conn->real_escape_string($_POST['email']) . "'";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
	    	echo json_encode(array("status"=>2));
		exit();
	}

	$sql = "INSERT INTO companies (name, email) VALUES ('" . $conn->real_escape_string($_POST['company']) . "','" . $conn->real_escape_string($_POST['email']) . "')"; 
	if(!$run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>0));
		exit();
	}else{
		$newCompanyId = $conn ->insert_id;
	}

	
	$sql = "INSERT INTO users (fname, lname, email, role, password, company) VALUES ('" . $conn->real_escape_string($_POST['fname']) . "','" . $conn->real_escape_string($_POST['lname']) . "','" . $conn->real_escape_string($_POST['email']) . "','admin', '" . hash('sha256', $_POST['password']) . "', '$newCompanyId')"; 
	if($run = mysqli_query($conn,$sql)){
			$_SESSION['userId'] = $conn ->insert_id;
			$_SESSION['role'] = 'admin';
			$_SESSION['companyId'] = $newCompanyId;

			if(isset($_COOKIE['redirect'])) {
				$url = $_COOKIE['redirect'];
				unset($_COOKIE['redirect']);
				setcookie('redirect', null, -1, '/');
			}else{
				$url = "dashboard";
			}
	    	echo json_encode(array("status"=>1,"url"=>$url));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}
}
// send password reset link
if ($action == "forgotpassword") {
	// system settings  
	$sql = "SELECT * FROM settings WHERE id = 1";
	$result = $conn->query($sql);
	$system = $result->fetch_assoc();

	// mailer variables
	date_default_timezone_set('Etc/UTC');

	require '../includes/mailbot/PHPMailerAutoload.php';

	$mail = new PHPMailer;
	$mail->isSMTP();
	$mail->SMTPDebug = 0;
	$mail->Debugoutput = 'html';
	$mail->Host = $system['smtp_host'];
	$mail->SMTPSecure = $system['smtp_secure']; 
	$mail->Port = $system['smtp_port'];
	$mail->SMTPAuth = true;
	$mail->Username = $system['smtp_username'];
	$mail->Password = $system['smtp_password'];
	$mail->setFrom($system['smtp_username'], $system['name']);

     // Generate token
    function randomKey($length) {
        $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));

        for($i=0; $i < $length; $i++) {
            $key .= $pool[mt_rand(0, count($pool) - 1)];
        }
        return $key;
    }
    $token = randomKey(20).time();

    include_once("../includes/emailtemplates/forgotpassword.php");

    // find email
    $sql = "SELECT email FROM users WHERE email='" . $conn->real_escape_string($_POST['email']) . "'";
    $result = $conn->query($sql);
    if($result->num_rows == 1) {

		// sender email 
		$sql = "SELECT email, fname FROM users WHERE email = '" . $conn->real_escape_string($_POST['email']) . "'";
		$result = $conn->query($sql);
		$profile = $result->fetch_assoc();

		if (empty($profile['avatar'])) {
		    $profile['avatar'] = "avatar.png";
		}


    
		$avatar = $siteUrl."/uploads/avatar/".$profile['avatar'];
		$logo = $siteUrl."/assets/images/".$system['logo'];
		$resetlink = $siteUrl."/login?action=reset&token=".$token;

		$tags = array("[siteurl]","[avatar]","[fname]","[systemname]","[logo]","[resetlink]");
		$values   = array($siteUrl,$avatar,$profile['fname'],$system['name'],$logo,$resetlink);

		$message = str_replace($tags, $values, $forgotPasswordTemplate);


        // If a user is found
      $sql = "UPDATE users SET token ='$token' WHERE email = '" . $conn->real_escape_string($_POST['email']) . "'";
      $run = mysqli_query($conn,$sql);

		// send email
		$mail->addReplyTo($system['smtp_username'], $system['name']);
		$mail->addAddress($_POST['email']);
		$mail->isHTML(true);
		$mail->Subject = $system['name'].' password reset link';
		$mail->Body = $message;    
		if (!$mail->send()) {
		        echo json_encode(array("status"=>0));
		    exit();
		} else {
		        echo json_encode(array("status"=>1));
		    exit();
		}

    }else if($result->num_rows == 0) {
        // Not found on users table
            echo json_encode(array("status"=>2));
        exit();
    } 
}

// Update password on reset
if ($action == "passwordreset") {
	$sql = "SELECT token FROM users where token = '" . $conn->real_escape_string($_POST['token']) . "'";
	$result = $conn->query($sql);
	if ($result->num_rows < 1) {
            echo json_encode(array("status"=>2));
        exit();
	}

	$newToken = "";
	$newPassword = hash('sha256', $_POST['password']);
	$sql = "UPDATE users SET token ='$newToken', password = '$newPassword' WHERE token = '" . $conn->real_escape_string($_POST['token']) . "'";
		if ($run = mysqli_query($conn,$sql)) {
		        echo json_encode(array("status"=>1));
		    exit();
		} else {
		        echo json_encode(array("status"=>0));
		    exit();
		}

}
}