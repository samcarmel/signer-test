<?php
include_once("../includes/global.php");
include '../includes/Suin/ImageResizer/ImageResizerInterface.php';
include '../includes/Suin/ImageResizer/ImageResizer.php';
error_reporting(E_ALL);

use \Suin\ImageResizer\ImageResizer;

$folder = $_SESSION["folder"];

if (isset($_POST["action"])) {
$action = $_POST["action"];

// save profile settings
if ($action == "profile") {

	// update avatar start

	// upload avatar start 
	if ($_FILES['avatar']['size'] == 0) {
	    $newAvatar = $conn->real_escape_string($_POST['oldAvatar']);
	}else {
    	$randcode = rand (10,100000);
    	$target_dir = "../uploads/avatar/";
    	$target_file = $target_dir.time().$randcode. basename($_FILES["avatar"]["name"]);
    	$newAvatar = time().$randcode. basename($_FILES["avatar"]["name"]);
    	$target_file = str_replace(" ", "_", $target_file);
    	$newAvatar = str_replace(" ", "_", $newAvatar);
    	
    	if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) { 
    		echo json_encode(array("status"=>2));
    		exit();
        }else{
    	    $resizer = new ImageResizer($target_file);
    	    $resizer->maxWidth(200)->maxHeight(200)->resize();
    		unlink("../uploads/avatar/".$_POST['oldAvatar']);
        }
	} 
	
	// update avatar end 
	$sql = "UPDATE users SET fname = '" . $conn->real_escape_string($_POST['fname']) . "', lname = '" . $conn->real_escape_string($_POST['lname']) . "', email = '" . $conn->real_escape_string($_POST['email']) . "', phone = '" . $conn->real_escape_string($_POST['phone']) . "', avatar = '$newAvatar' WHERE id = $userId"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}
}

// save company settings
if ($action == "company") {
	$sql = "UPDATE companies SET name = '" . $conn->real_escape_string($_POST['name']) . "', email = '" . $conn->real_escape_string($_POST['email']) . "', phone = '" . $conn->real_escape_string($_POST['phone']) . "' WHERE id = $companyId"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}
}

// save company settings
if ($action == "password") {
	$sql = "SELECT password FROM users WHERE id= $userId";
	$result = $conn->query($sql);
	if($result->num_rows == 1) {	
	$user = $result->fetch_assoc();
		if(hash('sha256', $_POST['currentPassword']) == $user['password']) {
			$sql = "UPDATE users SET password = '" . hash('sha256', $_POST['newPassword']) . "' WHERE id = $userId"; 
			if($run = mysqli_query($conn,$sql)){
			    	echo json_encode(array("status"=>1));
				exit();
			}else{
			    	echo json_encode(array("status"=>0));
				exit();
			}
		}else{
		    	echo json_encode(array("status"=>2));
			exit();
		}
	}

}

// save system settings
if ($action == "system") {
	// current logo and favicon
	$sql = "SELECT * FROM settings WHERE id = 1";
	$result = $conn->query($sql);
	$system = $result->fetch_assoc();
	$currentLogo = $system['logo'];
	$currentFavicon = $system['favicon'];

	// upload logo start 
	if ($_FILES['logo']['size'] == 0){
	    $newLogo = $currentLogo;
	}else {
	list($width, $height) = getimagesize($_FILES["logo"]["tmp_name"]);
	if ($width == 541 and $height == 152) {
	$randcode = rand (10,100000);
	$target_dir = "../assets/images/";
	$target_file = $target_dir.time().$randcode. basename($_FILES["logo"]["name"]);
	$newLogo = time().$randcode. basename($_FILES["logo"]["name"]);
	$target_file = str_replace(" ", "_", $target_file);
	$newLogo = str_replace(" ", "_", $newLogo);

	if (!move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) { 
	    	echo json_encode(array("status"=>2));
		exit();
	}else{
		unlink("../assets/images/".$currentLogo);
	}

	 }else{  
		    	echo json_encode(array("status"=>3));
			exit();
	 } 
	}
	// end logo upload 

	// upload favicon start 
	if ($_FILES['favicon']['size'] == 0){
	    $newFavicon = $currentFavicon;
	}else {
	list($width, $height) = getimagesize($_FILES["favicon"]["tmp_name"]);
	if ($width == 152 and $height == 152) {
	$randcode = rand (10,100000);
	$target_dir = "../assets/images/";
	$target_file = $target_dir.time().$randcode. basename($_FILES["favicon"]["name"]);
	$newFavicon = time().$randcode. basename($_FILES["favicon"]["name"]);
	$target_file = str_replace(" ", "_", $target_file);
	$newFavicon = str_replace(" ", "_", $newFavicon);

	if (!move_uploaded_file($_FILES["favicon"]["tmp_name"], $target_file)) { 
	    	echo json_encode(array("status"=>2));
		exit();
	}else{
		unlink("../assets/images/".$currentFavicon);
	}

	 }else{  
	    	echo json_encode(array("status"=>5));
		exit();
	} 
	}
	// end logo upload 

	$sql = "UPDATE settings SET name = '" . $conn->real_escape_string($_POST['name']) . "', smtp_username = '" . $conn->real_escape_string($_POST['username']) . "', smtp_port = '" . $conn->real_escape_string($_POST['port']) . "', smtp_secure = '" . $conn->real_escape_string($_POST['secure']) . "', smtp_host = '" . $conn->real_escape_string($_POST['host']) . "', smtp_password = '" . $conn->real_escape_string($_POST['password']) . "', logo = '$newLogo', favicon = '$newFavicon' WHERE id = 1"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}
}

// Add team
if ($action == "addTeam") {


	// check if email already exists 
	$sql = "SELECT email FROM users WHERE email='" . $conn->real_escape_string($_POST['email']) . "'";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
	    	echo json_encode(array("status"=>5));
		exit();
	}

	// upload logo start 
	$randcode = rand (10,100000);
	$target_dir = "../uploads/avatar/";
	$target_file = $target_dir.time().$randcode. basename($_FILES["avatar"]["name"]);
	$avatar = time().$randcode. basename($_FILES["avatar"]["name"]);
	$target_file = str_replace(" ", "_", $target_file);
	$avatar = str_replace(" ", "_", $avatar);
	
    if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) { 
    	echo json_encode(array("status"=>2));
	    exit();
    }else{
	    $resizer = new ImageResizer($target_file);
	    $resizer->maxWidth(200)->maxHeight(200)->resize();
    }
	// end avatar upload 
	if(!isset($_POST['permissions'])) {
	    $_POST['permissions'] = array();
	}
	array_push($_POST['permissions'],"upload");
	
	$sql = "INSERT INTO users (fname, lname, email, phone, avatar, role, password, company, permissions) VALUES ('" . $conn->real_escape_string($_POST['fname']) . "','" . $conn->real_escape_string($_POST['lname']) . "','" . $conn->real_escape_string($_POST['email']) . "','" . $conn->real_escape_string($_POST['phone']) . "', '$avatar', 'staff', '" . hash('sha256', $_POST['password']) . "', '$companyId','".json_encode($_POST['permissions'])."')"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}
}



// delete team
if ($action == "deleteTeam") {  

	$sql = "SELECT avatar FROM users WHERE id = '$teamId' and company = $companyId";
	$result = $conn->query($sql);
	$team = $result->fetch_assoc();

	$teamId = $conn->real_escape_string($_POST['userId']);
	$sql = "DELETE FROM users WHERE id = '$teamId' and company = $companyId";
            if ($conn->query($sql) === TRUE) {
				unlink("../uploads/avatar/".$team['avatar']);
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}



// update team
if ($action == "editTeam") {
    $teamId = $conn->real_escape_string($_POST['teamId']);

	// check if email already exists 
	$sql = "SELECT email FROM users WHERE email='" . $conn->real_escape_string($_POST['email']) . "' and id != $teamId";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
	    	echo json_encode(array("status"=>5));
		exit();
	}

	// upload avatar start 
	if ($_FILES['avatar']['size'] == 0) {
	    $newAvatar = $conn->real_escape_string($_POST['oldAvatar']);
	}else {
    	$randcode = rand (10,100000);
    	$target_dir = "../uploads/avatar/";
    	$target_file = $target_dir.time().$randcode. basename($_FILES["avatar"]["name"]);
    	$newAvatar = time().$randcode. basename($_FILES["avatar"]["name"]);
    	$target_file = str_replace(" ", "_", $target_file);
    	$newAvatar = str_replace(" ", "_", $newAvatar);
    	
    	if (!move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) { 
        	echo json_encode(array("status"=>2));
    	    exit();
        }else{
    	    $resizer = new ImageResizer($target_file);
    	    $resizer->maxWidth(200)->maxHeight(200)->resize();
    		unlink("../uploads/avatar/".$_POST['oldAvatar']);
        }
	}
	$password = "";
	if (isset($_POST['password']) and $_POST['password'] != "") {
		$password = ", password = '" . hash('sha256', $_POST['password']) . "'";
	}
	// end avatar upload 
	
	if(!isset($_POST['permissions'])){
	    $_POST['permissions'] = array();
	}
	array_push($_POST['permissions'],"upload");
	
	$sql = "UPDATE users SET fname = '" . $conn->real_escape_string($_POST['fname']) . "', lname = '" . $conn->real_escape_string($_POST['lname']) . "', email = '" . $conn->real_escape_string($_POST['email']) . "', phone = '" . $conn->real_escape_string($_POST['phone']) . "', avatar = '$newAvatar'".$password." , permissions = '".json_encode($_POST['permissions'])."' WHERE id = $teamId and role = 'staff'"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}
}


// create folder
if ($action == "createFolder") {

	// check if folder already exists 
	$sql = "SELECT name FROM folders WHERE name='" . $conn->real_escape_string($_POST['name']) . "'";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
	    	echo json_encode(array("status"=>2));
		exit();
	} 

	$sql = "INSERT INTO folders (company, created_by, name, folder) VALUES ('$companyId','$userId','" . $conn->real_escape_string($_POST['name']) . "','$folder')"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}


// send chat message
if ($action == "sendchat") {

	$sql = "INSERT INTO chat (sender, message, file) VALUES ('$userId','" . $conn->real_escape_string($_POST['message']) . "','" . $conn->real_escape_string($_POST['sharingKey']) . "')"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1,"time"=>date("M d, Y h:ia")));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}

// rename folder
if ($action == "renameFolder") {
	$folderId = $conn->real_escape_string($_POST['folderId']);

	// check if folder already exists 
	$sql = "SELECT name FROM folders WHERE name='" . $conn->real_escape_string($_POST['name']) . "'";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
	    	echo json_encode(array("status"=>2));
		exit();
	} 

	$sql = "UPDATE folders SET name = '" . $conn->real_escape_string($_POST['name']) . "' WHERE id = $folderId"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}

// rename file
if ($action == "renameFile") {
	$fileId = $conn->real_escape_string($_POST['fileId']);

	$sql = "UPDATE files SET name = '" . $conn->real_escape_string($_POST['name']) . "' WHERE id = $fileId"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}

// rename file
if ($action == "countnotifications") {
    // signing requests notifications
    $sql = "SELECT * FROM requests where email = '".$userEmail."' and status = '0'";
    $result = $conn->query($sql);
    $requestCount = $result->num_rows;
    
    // count notifications
    $sql = "SELECT * FROM notifications where user = $userId and time_ > '$lastNotification'";
    $result = $conn->query($sql);
    $notificationsCount = $result->num_rows;
    
    $notificationsCount = $notificationsCount + $requestCount;

    echo json_encode(array("count"=>$notificationsCount));
	exit();

}

// rename file
if ($action == "hideFile") {
	$fileId = $conn->real_escape_string($_POST['fileId']);
	
	$sql = "SELECT hiddenfiles FROM users WHERE id = $userId";
    $result = $conn->query($sql);
    $profile = $result->fetch_object();
    
    $hiddenFiles = array();
    if(!empty($profile->hiddenfiles)){
        $hiddenFiles = json_decode($profile->hiddenfiles);
    }
    
    array_push($hiddenFiles, $fileId);  

	$sql = "UPDATE users SET hiddenfiles = '" .json_encode($hiddenFiles). "' WHERE id = $userId"; 
	if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}

// delete folder
if ($action == "deleteFolder") {  
	$folderId = $conn->real_escape_string($_POST['folderId']);
	
	// select all folders under this folder
	$sql = "SELECT id FROM folders where folder = $folderId and  company = $companyId or  id = $folderId and  company = $companyId";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {   
			$foldersToDelete[] = $row['id'];
		}
	}
	
	// get file name
	$sql = "SELECT * FROM files where folder IN (".implode(',', $foldersToDelete).")";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {   
			$filename = $row['filename'];
			unlink("../uploads/files/".$filename);
			unlink("../uploads/files/original/".$filename);
		}
	}


	$sql = "DELETE FROM folders WHERE folder = '$folderId' and company = $companyId";
	$run = mysqli_query($conn,$sql);
	$sql = "DELETE FROM folders WHERE id = '$folderId' and company = $companyId";
            if ($conn->query($sql) === TRUE) {
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}

// create folder
if ($action == "uploadFile") {

	// sharing key
	function randomKey($length) {
	    $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));

	    for($i=0; $i < $length; $i++) {
	        $key .= $pool[mt_rand(0, count($pool) - 1)];
	    }
	    return $key;
	}
	$sharingKey = randomKey(20).time();

	// upload file start
	$randcode = rand (10,100000);
	$fileName = str_replace(" ", "_", time().$randcode. basename($_FILES["file"]["name"]));
	$target_file = "../uploads/files/".$fileName;
	$copy_file = "../uploads/files/original/".$fileName;

	if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) { 
		echo json_encode(array("status"=>2));
		exit();
	}
	copy($target_file,$copy_file);
	// upload file end 

	$activity = 'File Uploaded by <span class="text-primary">'.$fullName.'</span>.';

	$sql = "INSERT INTO files (company, uploaded_by, name, folder, filename, sharing_key) VALUES ('$companyId','$userId','" . $conn->real_escape_string($_POST['name']) . "','$folder','$fileName', '$sharingKey')"; 
	if($run = mysqli_query($conn,$sql)){
			$sql = "INSERT INTO history (company, file, activity, type) VALUES ('$companyId','$sharingKey','".$conn->real_escape_string($activity)."','default' )"; 
			$run = mysqli_query($conn,$sql);
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}


// delete file
if ($action == "deleteFile") {   
	$fileId = $conn->real_escape_string($_POST['fileId']);

	// get file name
	$sql = "SELECT * FROM files where id = $fileId and  company = $companyId";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {   
			$filename = $row['filename'];
		}
	}

	$sql = "DELETE FROM files WHERE id = '$fileId' and company = $companyId";
            if ($conn->query($sql) === TRUE) {
            		unlink("../uploads/files/".$filename);
            		unlink("../uploads/files/original/".$filename);
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}


// save text signature
if ($action == "saveTextSignature") {  
	// old signature 
	$sql = "SELECT signature FROM users WHERE id = $userId";
	$result = $conn->query($sql);
	$profile = $result->fetch_assoc();
	unlink("../uploads/signatures/".$profile['signature']);

	$imagedata = base64_decode($_POST['imgdata']);
	$filename = md5(uniqid(rand(), true));
	//path where you want to upload image
	$file = '../uploads/signatures/'.$filename.'.png';
	$imageurl  = $filename.'.png';
	$result = file_put_contents($file,$imagedata); 
	if($result === false) {
	    echo "Failed to save, try again.";
	} else {
	
	$sql = "UPDATE users SET signature = '" . $imageurl . "' WHERE id = $userId"; 
	$run = mysqli_query($conn,$sql);
	?>
		<img src="uploads/signatures/<?php echo $imageurl; ?>" class="img-responsive">
	<?php
	}
}

// save image signature
if ($action == "saveImageSignature") { 

	$randcode = rand (10,100000);
	$target_dir = "../uploads/signatures/";
	$target_file = $target_dir.time().$randcode. basename($_FILES["imgdata"]["name"]);
	$newSignature = time().$randcode. basename($_FILES["imgdata"]["name"]);
	$target_file = str_replace(" ", "_", $target_file);
	$newSignature = str_replace(" ", "_", $newSignature);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if (move_uploaded_file($_FILES["imgdata"]["tmp_name"], $target_file)) { 
		// old signature 
		$sql = "SELECT signature FROM users  WHERE id = $userId";
		$result = $conn->query($sql);
		$profile = $result->fetch_assoc();
		unlink("../uploads/signatures/".$profile['signature']);

		$sql = "UPDATE users SET signature = '" . $newSignature . "' WHERE id = $userId"; 
		$run = mysqli_query($conn,$sql);
	    ?>
		<img src="uploads/signatures/<?php echo $newSignature; ?>" class="img-responsive"> 
	<?php
	 }else{
	 	echo "Failed to upload, try again.";
	 }
 }



// delete team
if ($action == "sign") {
	require_once '../includes/TCPDF/tcpdf.php';
	require_once '../includes/TCPDF/tcpdi.php';

	// Uploads DIR
	$uploads_dir = str_replace('files', 'uploads/', dirname(__FILE__));

	// Post data
	$positions = json_decode($_POST['positions'], true);

	$sql = "SELECT filename FROM files  WHERE sharing_key = '{$conn->real_escape_string($_POST['documentKey'])}'";
	$file = $conn->query($sql)->fetch_object();
	
	// Filenames
	$full_path_to_input_file = $uploads_dir . 'files/' . $file->filename;
	$output_filename = uniqid('doc') . '.pdf';
	$full_path_to_output_file = $uploads_dir . 'files/' . $output_filename;

	$sql = "SELECT signature FROM users  WHERE id = {$userId}";
	$user = $conn->query($sql)->fetch_object();

	// Signature by user
	$signature = $uploads_dir . 'signatures/' . (empty($user->signature) ? 'demo.png' :  $user->signature);

	class PDF extends TCPDI {
	
		var $_tplIdx;
		var $numPages;
	
		function Header() {}
	
		function Footer() {}
	
	}
	// initiate PDF
	$pdf = new PDF(PDF_PAGE_ORIENTATION, 'px', PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->AddPage();
	$pdf->numPages = $pdf->setSourceFile($full_path_to_input_file);

	// Add pages while signing
	foreach(range(1, $pdf->numPages, 1) as $page) {

		if($page !== 1) {
			$pdf->AddPage();
		}

		// Add a page
		$pdf->_tplIdx = $pdf->importPage($page);
		$pdf->useTemplate($pdf->_tplIdx);

		foreach($positions as $position) {
			
			if(((int) $position['pageNumber']) === $page) {

				// Calculate signature center positioning
				$x_pos = (string) ($position['xPosition'] - 30);
				$y_pos = (string) ($position['yPosition'] - 25);

				// Add the signature
				$pdf->setJPEGQuality(90);
				$pdf->setImageScale(1);
				$pdf->Image($signature, $x_pos, $y_pos, 200, 0, '', '', 'T', false, 4800, '', false, false, 0, 'LT');

			}
			
		}
	}

	$pdf->Output($full_path_to_output_file, 'F');

	$activity = '<span class="text-primary">'.$fullName.'</span> signed the document.';


	$sql = "INSERT INTO history (company, file, activity, type) VALUES ('$companyId','".$_POST['documentKey']."','".$conn->real_escape_string($activity)."','success' )"; 
	$run = mysqli_query($conn,$sql);

	$sql = "UPDATE `files` SET `filename` = '{$output_filename}', `status` = 'signed' WHERE `sharing_key` = '{$conn->real_escape_string($_POST['documentKey'])}'";
	if($conn->query($sql)) {
		unlink($full_path_to_input_file);
	}
	
    // 	rename original file
    rename("../uploads/files/original/".$file->filename,"../uploads/files/original/".$output_filename);
    
	if ($_POST['signingMode'] == 'request') {
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

		include_once("../includes/emailtemplates/templatewithbutton.php");

		$avatar = $siteUrl."/uploads/avatar/".$userAvatar;
		$logo = $siteUrl."/assets/images/".$system['logo'];
		$title = $fullName.' has signed the document';
		$note = $fullName.' has signed the document had invited him/her to sign, click on the button below to view the signed document';
		$buttonlink = $siteUrl.'/sign?key='.$_POST['documentKey'];
		$buttontext = "View Document";


		// update requests has declined
		$sql = "UPDATE requests SET status = '1' WHERE signingkey = '" . $conn->real_escape_string($_POST['signingKey']) . "' and file = '" . $conn->real_escape_string($_POST['documentKey']) . "'"; 
		$run = mysqli_query($conn,$sql);

		// get request details 
		$sql = "SELECT * FROM requests WHERE signingkey = '" . $conn->real_escape_string($_POST['signingKey']) . "' and file = '" . $conn->real_escape_string($_POST['documentKey']) . "'";
		$result = $conn->query($sql);
		$request = $result->fetch_assoc();

		// get receiver email
		$sql = "SELECT email FROM users WHERE id = ".$request['user'];
		$result = $conn->query($sql);
		$receiver = $result->fetch_assoc();

		// notify the sender
		$notification = '<span class="text-primary">'.$fullName.'</span> accepted the invitation and signed the <a href="'.$siteUrl.'/sign?key='.$_POST['documentKey'].'"><span class="text-primary">document.</span></a>';
		$sql = "INSERT INTO notifications (company, user, message, type, time_) VALUES ('".$request['company']."','".$request['user']."','".$conn->real_escape_string($notification)."','accept', '".date('Y-m-d H:i:s')."' )"; 
		$run = mysqli_query($conn,$sql);

		// send email               
		$tags = array("[siteurl]","[avatar]","[title]","[systemname]","[logo]","[note]","[buttonlink]","[buttontext]");
		$values   = array($siteUrl,$avatar,$title,$system['name'],$logo,$note,$buttonlink,$buttontext);

		$message = str_replace($tags, $values, $templateWithButton);

		$mail->addReplyTo($userEmail, $fullName);
		$mail->addAddress($receiver['email']);
		$mail->isHTML(true);
		$mail->Subject = $fullName.' has signed the document';
		$mail->Body = $message;   
		if (!$mail->send()) {
		        echo json_encode(array("status"=>0,"error"=>$mail->ErrorInfo));
		    exit();
		} else {
		        echo json_encode(array("status"=>1));
		    exit();
		}
	}

	echo json_encode(array(
		'status' => 1
	));

	$conn->close();

	exit();
}

if ($action == "write") {
	require_once '../includes/TCPDF/tcpdf.php';
	require_once '../includes/TCPDF/tcpdi.php';

	// Uploads DIR
	$uploads_dir = str_replace('files', 'uploads/', dirname(__FILE__));

	// Post data
	$positions = json_decode($_POST['positions'], true);

	$sql = "SELECT filename FROM files  WHERE sharing_key = '{$conn->real_escape_string($_POST['documentKey'])}'";
	$file = $conn->query($sql)->fetch_object();
	
	// Filenames
	$full_path_to_input_file = $uploads_dir . 'files/' . $file->filename;
	$output_filename = uniqid('doc') . '.pdf';
	$full_path_to_output_file = $uploads_dir . 'files/' . $output_filename;

	class PDF extends TCPDI {
	
		var $_tplIdx;
		var $numPages;
	
		function Header() {}
	
		function Footer() {}
	
	}
	// initiate PDF
	$pdf = new PDF(PDF_PAGE_ORIENTATION, 'px', PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->AddPage();
	$pdf->numPages = $pdf->setSourceFile($full_path_to_input_file);

	// Add pages while signing
	foreach(range(1, $pdf->numPages, 1) as $page) {

		if($page !== 1) {
			$pdf->AddPage();
		}

		// Add a page
		$pdf->_tplIdx = $pdf->importPage($page);
		$pdf->useTemplate($pdf->_tplIdx);

		foreach($positions as $position) {
			
			if(((int) $position['pageNumber']) === $page) {

				// Calculate text center positioning
				$x_pos = (string) ($position['xPosition'] - 25);
				$y_pos = (string) ($position['yPosition'] - 15);

				// Add the text
				$pdf->SetFont('aealarabiya', '', 13);
				$pdf->SetTextColor(0, 0, 0);
				$pdf->SetXY($x_pos, $y_pos);
				$pdf->Write(0, $position['text']);

			}
			
		}
	}

	$pdf->Output($full_path_to_output_file, 'F');

	$activity = '<span class="text-primary">'.$fullName.'</span> wrote on the document.';


	$sql = "INSERT INTO history (company, file, activity, type) VALUES ('$companyId','".$_POST['documentKey']."','".$conn->real_escape_string($activity)."','success' )"; 
	$run = mysqli_query($conn,$sql);

	$sql = "UPDATE `files` SET `filename` = '{$output_filename}', `status` = 'signed' WHERE `sharing_key` = '{$conn->real_escape_string($_POST['documentKey'])}'";
	if($conn->query($sql)) {
		unlink($full_path_to_input_file);
	}
	
    // 	rename original file
    rename("../uploads/files/original/".$file->filename,"../uploads/files/original/".$output_filename);


	echo json_encode(array(
		'status' => 1
	));

	$conn->close();

	exit();
}

// delete notification
if ($action == "deletenotification") {  

	$notificationId = $conn->real_escape_string($_POST['notificationId']);
	$sql = "DELETE FROM notifications WHERE id = '$notificationId' and user = $userId";
   if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}

// delete signing request
if ($action == "deleterequest") {  

	$requestId = $conn->real_escape_string($_POST['requestId']);
	$sql = "DELETE FROM requests WHERE id = '".$requestId."' and company = ".$companyId;
   if($run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}

// cancel signing request
if ($action == "cancelrequest") {  
	$requestId = $conn->real_escape_string($_POST['requestId']);
	
    $sqlRequest = "SELECT email, file FROM requests where id = ".$requestId;
    $requestResult = $conn->query($sqlRequest);
    $request = $requestResult->fetch_object();

	$sql = "UPDATE requests SET status = '3' WHERE id = '".$requestId."' and company = ".$companyId;
   if($run = mysqli_query($conn,$sql)){
       
       		// add to document history
        	$activity = '<span class="text-primary">'.$fullName.'</span> <span class="text-danger">cancelled</span> a signing request sent to <span class="text-primary">'.$request->email.'</span>.';
        	$sql = "INSERT INTO history (company, file, activity, type) VALUES ('$companyId','".$request->file."','".$conn->real_escape_string($activity)."','danger' )"; 
        	$run = mysqli_query($conn,$sql);
    		
	    	echo json_encode(array("status"=>1));
		exit();
	}else{
	    	echo json_encode(array("status"=>0));
		exit();
	}

}

// cancel signing request
if ($action == "savereminders") {  
    // update remiders settings
    $sql = "UPDATE companies SET reminders = '".$conn->real_escape_string($_POST['enable_reminders'])."' WHERE id = ".$companyId;
    $run = mysqli_query($conn,$sql);
    
    // delete existing reminders
    $sql = "DELETE FROM reminders WHERE company = ".$companyId;
    $run = mysqli_query($conn,$sql);
    
    $counts = $_POST['count'];
    $subject = $_POST['subject'];
    $days = $_POST['days'];
    $message = $_POST['message'];
    
    // save new reminders
    foreach ($counts as $count => $key) {
      	$sql = "INSERT INTO reminders (company, subject, days, message) VALUES ('$companyId','".$conn->real_escape_string($subject[$count])."','".$conn->real_escape_string($days[$count])."','".$conn->real_escape_string($message[$count])."' )"; 
        if(!$run = mysqli_query($conn,$sql)){
	    	echo json_encode(array("status"=>0));
    		exit();
    	}
    }
    echo json_encode(array("status"=>1));
	exit();
}

// duplicate files
if ($action == "duplicatefile") {
    $fileId = $conn->real_escape_string($_POST['fileId']);
    
    	// sharing key
	function randomKey($length) {
	    $pool = array_merge(range(0,9), range('a', 'z'),range('A', 'Z'));

	    for($i=0; $i < $length; $i++) {
	        $key .= $pool[mt_rand(0, count($pool) - 1)];
	    }
	    return $key;
	}
	$sharingKey = randomKey(20).time();
    
    $sqlFile = "SELECT name, filename, folder FROM files where id = ".$fileId;
    $resultFile = $conn->query($sqlFile);
    $originalFile = $resultFile->fetch_object();
    
    copy("../uploads/files/".$originalFile->filename,"../uploads/files/".$originalFile->filename.time());	
    copy("../uploads/files/original/".$originalFile->filename,"../uploads/files/original/".$originalFile->filename.time());	
    
    $sql = "INSERT INTO files (company, uploaded_by, name, folder, filename, sharing_key) VALUES ('$companyId','$userId','" .$originalFile->name."-copy','".$originalFile->folder."','".$originalFile->filename.time()."', '$sharingKey')"; 
	$run = mysqli_query($conn,$sql);
	
   	// add to document history
	$activity = 'This file was duplicated from  <span class="text-primary">'.$originalFile->name.'</span> by <span class="text-primary">'.$fullName.'</span>.';
	$sql = "INSERT INTO history (company, file, activity, type) VALUES ('$companyId','".$sharingKey."','".$conn->real_escape_string($activity)."','default' )"; 
	$run = mysqli_query($conn,$sql);
    		
	
    echo json_encode(array("status"=>1));
	exit();
}

// replace a file
if ($action == "replaceFile") {
    $fileId = $conn->real_escape_string($_POST['fileId']);
    
    $sqlFile = "SELECT name, filename, folder, sharing_key FROM files where id = ".$fileId;
    $resultFile = $conn->query($sqlFile);
    $originalFile = $resultFile->fetch_object();
    
    	// upload file start
	$randcode = rand (10,100000);
	$fileName = str_replace(" ", "_", time().$randcode. basename($_FILES["file"]["name"]));
	$target_file = "../uploads/files/".$fileName;
	$copy_file = "../uploads/files/original/".$fileName;

	if (!move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) { 
		echo json_encode(array("status"=>2));
		exit();
	}
	copy($target_file,$copy_file);
	
    // 	delete old files from disk
	unlink("../uploads/files/".$originalFile->filename);
	unlink("../uploads/files/original/".$originalFile->filename);
	// upload file end 
	
   	// add to document history
	$activity = 'This file was replaced with a new one by <span class="text-primary">'.$fullName.'</span>.';
	$sql = "INSERT INTO history (company, file, activity, type) VALUES ('$companyId','".$originalFile->sharing_key."','".$conn->real_escape_string($activity)."','default' )"; 
	$run = mysqli_query($conn,$sql);
	
	$sql = "UPDATE files SET filename = '".$fileName."' WHERE id = $fileId and company = ".$companyId;
	$run = mysqli_query($conn,$sql);
    
    echo json_encode(array("status"=>1));
	exit();
}

} //end of action


?>