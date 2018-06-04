<?php
// cron jobs

// Include Global files
include_once("config.php"); 
include_once("includes/emailtemplates/templatewithbutton.php");

// date today
$today = date("Y-m-d");

// system settings  
$sql = "SELECT * FROM settings WHERE id = 1";
$result = $conn->query($sql);
$system = $result->fetch_object();

// mailer variables
date_default_timezone_set('Etc/UTC');

require 'includes/mailbot/PHPMailerAutoload.php';




// select all companies with reminders turned on
$sqlCompany = "SELECT id FROM companies where reminders = 1";
$resultCompany = $conn->query($sqlCompany);
if ($resultCompany->num_rows > 0) {
	while($company = $resultCompany->fetch_object()) {

	   // select remiders details for this company
	    $sqlReminder = "SELECT * FROM reminders where company = ".$company->id;
        $resultReminder = $conn->query($sqlReminder);
        if ($resultReminder->num_rows > 0) {
        	while($reminder = $resultReminder->fetch_object()) {
        	    $controlDate = date('Y-m-d', strtotime($today. ' - '.$reminder->days.' days'));
        	    
        	   // select requests that need to send reminders
        	    $sqlRequest = "SELECT * FROM `requests` WHERE time_ >= '".$controlDate."' and time_ <= '".$controlDate." 23:59:59.999'";
                $resultRequest = $conn->query($sqlRequest);
                if ($resultRequest->num_rows > 0) {
                	while($request = $resultRequest->fetch_object()) {
        	            // send email            
        	            $buttonlink = $siteUrl.'/sign?key='.$request->file."&signingkey=".$request->signingkey;
                        $tags = array("[siteurl]","[avatar]","[title]","[systemname]","[logo]","[note]","[buttonlink]","[buttontext]");
                        $values   = array($siteUrl,$siteUrl."/uploads/avatar/avatar.png",$reminder->subject,$systemName,$siteUrl."/assets/images/".$systemLogo,$reminder->message,$buttonlink,"Sign Now");
                        
                        $message = str_replace($tags, $values, $templateWithButton);
                        
                        $mail = new PHPMailer;
                        $mail->isSMTP();
                        $mail->SMTPDebug = 0;
                        $mail->Debugoutput = 'html';
                        $mail->Host = $system->smtp_host;
                        $mail->SMTPSecure = $system->smtp_secure; 
                        $mail->Port = $system->smtp_port;
                        $mail->SMTPAuth = true;
                        $mail->Username = $system->smtp_username;
                        $mail->Password = $system->smtp_password;
                        $mail->setFrom($system->smtp_username, $system->name);
                        $mail->addReplyTo($system->smtp_username, $system->name);
                        $mail->addAddress($request->email);
                        $mail->isHTML(true);
                        $mail->Subject = $reminder->subject;
                        $mail->Body = $message;   
                        if (!$mail->send()) {
                            // failed
                        } else {
                            // sent
                            echo json_encode(array("status"=>1));
                        }
                	}
                }else{
                    // if there is no requests, skip this company
                    continue;
                }
        	}
        }else{
            // if there is no reminders, skip this company
            continue;
        }
	    
	}
}

