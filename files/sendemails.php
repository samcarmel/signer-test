<?php
include_once("../includes/global.php");
error_reporting(E_ALL);

// system settings  
$sql = "SELECT * FROM settings WHERE id = 1";
$result = $conn->query($sql);
$system = $result->fetch_assoc();

// sender email 
$sql = "SELECT email FROM users WHERE id = $userId";
$result = $conn->query($sql);
$profile = $result->fetch_assoc();

if (empty($profile['avatar'])) {
    $profile['avatar'] = "avatar.png";
}

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

if (isset($_POST["action"])) {
$action = $_POST["action"];

    // send  a file via email
    if ($action == "sendfile") {
        
        include_once("../includes/emailtemplates/sendfile.php");
        
        $avatar = $siteUrl."/uploads/avatar/".$userAvatar;
        $logo = $siteUrl."/assets/images/".$system['logo'];
        
        $tags = array("[siteurl]","[avatar]","[fullname]","[systemname]","[logo]","[note]");
        $values   = array($siteUrl,$avatar,$fullName,$system['name'],$logo,$_POST['note']);
        
        $message = str_replace($tags, $values, $sendFileTemplate);
        
        $mail->addReplyTo($profile['email'], $fullName);
        $mail->addAddress($_POST['email']);
        $mail->isHTML(true);
        $mail->Subject = 'You have received a document from '.$fullName;
        $mail->Body = $message;
        $mail->addAttachment('../uploads/files/'.$_POST['filename']);     
        if (!$mail->send()) {
		    	echo json_encode(array("status"=>0));
			exit();
        } else {
		    	echo json_encode(array("status"=>1));
			exit();
        }

        
    }

    // send  a document signing request via email
    if ($action == "signrequest") {
        
        include_once("../includes/emailtemplates/sendrequest.php");
        


    	// singing key
    	function randomKey($length) {
    	    $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));
    
    	    for($i=0; $i < $length; $i++) {
    	        $key .= $pool[mt_rand(0, count($pool) - 1)];
    	    }
    	    return $key;
    	}
    	$signingKey = randomKey(20).time();
        
        $avatar = $siteUrl."/uploads/avatar/".$userAvatar;
        $logo = $siteUrl."/assets/images/".$system['logo'];
        $signlink = $siteUrl."/sign?key=".$conn->real_escape_string($_POST['sharingKey'])."&signingkey=".$signingKey;
    	
    	$activity = 'Signing request sent to <span class="text-primary">'.$conn->real_escape_string($_POST['email']) .'</span> by <span class="text-primary">'.$fullName.'</span>.';
	   	
    	$sql = "INSERT INTO requests (company, file, signingkey, restricted, positions, email, sender, user) VALUES ('$companyId','" . $conn->real_escape_string($_POST['sharingKey']) . "','".$signingKey."','" . $conn->real_escape_string($_POST['restricted']) . "','" . $conn->real_escape_string($_POST['positions']) . "','" . $conn->real_escape_string($_POST['email']) . "','".$fullName."', '".$userId."')"; 
    	if($run = mysqli_query($conn,$sql)){
			$sql = "INSERT INTO history (company, file, activity, type) VALUES ('$companyId','" . $conn->real_escape_string($_POST['sharingKey']) . "','".$conn->real_escape_string($activity)."','default' )"; 
			$run = mysqli_query($conn,$sql);
    			
    		$tags = array("[siteurl]","[avatar]","[fullname]","[systemname]","[logo]","[note]","[signlink]");
            $values   = array($siteUrl,$avatar,$fullName,$system['name'],$logo,$_POST['note'],$signlink);

            $message = str_replace($tags, $values, $sendSigningRequestTemplate);

            $mail->addReplyTo($profile['email'], $fullName);
            $mail->addAddress($_POST['email']);
            $mail->isHTML(true);
            $mail->Subject = $fullName.' has invited you to sign a document';
            $mail->Body = $message;   
            if (!$mail->send()) {
                	echo json_encode(array("status"=>0,"error"=>$mail->ErrorInfo));
            	exit();
            } else {
                	echo json_encode(array("status"=>1));
            	exit();
            }
    	}else{
    	    	echo json_encode(array("status"=>0));
    		exit();
    	}

        
    }

    // decline signing request
    if ($action == "decline") {
        include_once("../includes/emailtemplates/templatewithbutton.php");

        $avatar = $siteUrl."/uploads/avatar/".$userAvatar;
        $logo = $siteUrl."/assets/images/".$system['logo'];
        $title = $fullName.' has declined your signing invitation';
        $note = $fullName.' declined your signing invitation, click on the button below to view the document';
        $buttonlink = $siteUrl.'/sign?key='.$_POST['sharingKey'];
        $buttontext = "View Document";


        // update requests has declined
        $sql = "UPDATE requests SET status = '2' WHERE signingkey = '" . $conn->real_escape_string($_POST['signingKey']) . "' and file = '" . $conn->real_escape_string($_POST['sharingKey']) . "'"; 
        $run = mysqli_query($conn,$sql);

        // get request details 
        $sql = "SELECT * FROM requests WHERE signingkey = '" . $conn->real_escape_string($_POST['signingKey']) . "' and file = '" . $conn->real_escape_string($_POST['sharingKey']) . "'";
        $result = $conn->query($sql);
        $request = $result->fetch_assoc();

        // get receiver email
        $sql = "SELECT email FROM users WHERE id = ".$request['user'];
        $result = $conn->query($sql);
        $receiver = $result->fetch_assoc();

        // add document activity
        $activity = '<span class="text-primary">'.$fullName.'</span> declined to sign the document';
        $sql = "INSERT INTO history (company, file, activity, type) VALUES ('".$request['company']."','" . $conn->real_escape_string($_POST['sharingKey']) . "','".$conn->real_escape_string($activity)."','danger' )"; 
        $run = mysqli_query($conn,$sql);

        // notify the sender
        $notification = '<span class="text-primary">'.$fullName.'</span> declined to sign the <a href="'.$siteUrl.'/sign?key='.$_POST['sharingKey'].'"><span class="text-primary">document.</span></a>';
        $sql = "INSERT INTO notifications (company, user, message, type, time_) VALUES ('".$request['company']."','".$request['user']."','".$conn->real_escape_string($notification)."','decline','".date('Y-m-d H:i:s')."')"; 
        $run = mysqli_query($conn,$sql);

        // send email               
        $tags = array("[siteurl]","[avatar]","[title]","[systemname]","[logo]","[note]","[buttonlink]","[buttontext]");
        $values   = array($siteUrl,$avatar,$title,$system['name'],$logo,$note,$buttonlink,$buttontext);
        
        $message = str_replace($tags, $values, $templateWithButton);
        
        $mail->addReplyTo($userEmail, $fullName);
        $mail->addAddress($receiver['email']);
        $mail->isHTML(true);
        $mail->Subject = $fullName.' has declined your signing invitation';
        $mail->Body = $message;   
        if (!$mail->send()) {
                echo json_encode(array("status"=>0,"error"=>$mail->ErrorInfo));
            exit();
        } else {
                echo json_encode(array("status"=>1));
            exit();
        }
    }

    // remind signing request
    if ($action == "remindrequest") {
        include_once("../includes/emailtemplates/templatewithbutton.php");
        
        // get request details 
        $sql = "SELECT * FROM requests WHERE id = '" . $conn->real_escape_string($_POST['requestId']) . "' and company = '".$companyId."'";
        $result = $conn->query($sql);
        $request = $result->fetch_object();

        $avatar = $siteUrl."/uploads/avatar/".$userAvatar;
        $logo = $siteUrl."/assets/images/".$system['logo'];
        $title = 'Signing invitation reminder from '.$fullName;
        $buttonlink = $siteUrl.'/sign?key='.$request->file."&signingkey=".$request->signingkey;
        $buttontext = "Sign Now";

        // add document activity
        $activity = '<span class="text-primary">'.$fullName.'</span> sent a signing reminder to <span class="text-primary">'.$request->email.'</span>';
        $sql = "INSERT INTO history (company, file, activity, type) VALUES ('".$request->company."','" .$request->file. "','".$conn->real_escape_string($activity)."','default' )"; 
        $run = mysqli_query($conn,$sql);

        // send email               
        $tags = array("[siteurl]","[avatar]","[title]","[systemname]","[logo]","[note]","[buttonlink]","[buttontext]");
        $values   = array($siteUrl,$avatar,$title,$system['name'],$logo,$_POST['message'],$buttonlink,$buttontext);
        
        $message = str_replace($tags, $values, $templateWithButton);
        
        $mail->addReplyTo($userEmail, $fullName);
        $mail->addAddress($request->email);
        $mail->isHTML(true);
        $mail->Subject = 'Signing invitation reminder from '.$fullName;
        $mail->Body = $message;   
        if (!$mail->send()) {
                echo json_encode(array("status"=>0,"error"=>$mail->ErrorInfo));
            exit();
        } else {
                echo json_encode(array("status"=>1));
            exit();
        }
    }
}