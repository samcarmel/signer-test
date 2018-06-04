<?php
/**
 * This example shows making an SMTP connection with authentication.
 */

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require 'PHPMailerAutoload.php';

//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "simcycreative.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->SMTPSecure = 'ssl'; 
$mail->Port = 465;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "demo@simcycreative.com";
//Password to use for SMTP authentication
$mail->Password = "Simcy@#101";
//Set who the message is to be sent from
$mail->setFrom('demo@simcycreative.com', 'Signer');
//Set an alternative reply-to address
$mail->addReplyTo('demo@simcycreative.com', 'Signer');
//Set who the message is to be sent to
$mail->addAddress('kimelidaniel13@gmail.com', 'Daniel Kimeli');
//Set the subject line
$mail->Subject = 'You have received a file';
//Replace the plain text body with one created manually
$mail->Body = 'Hello daniel, someone has sent you a sign request';

//send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}


