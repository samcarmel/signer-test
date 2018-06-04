<?php
include_once("../includes/global.php");
error_reporting(E_ALL);

$hiddenFiles = array('0');
$sqlProfile = "SELECT hiddenfiles FROM users WHERE id = $userId";
$resultProfile = $conn->query($sqlProfile);
$profile = $resultProfile->fetch_object();
if(!empty($profile->hiddenfiles)){
    $hiddenFiles = json_decode($profile->hiddenfiles);
}

$hiddenFiles = "'".implode("','", $hiddenFiles)."'";

// change folder 
if (isset($_POST['folderId']) and $_POST['folderId'] != '') {
	$_SESSION["folder"] = $_POST['folderId'];
    	echo json_encode(array("status"=>1));
	exit();
}

// folder query 
$folderQuery = " and folder = ".$_SESSION["folder"];

// Filter sessions
if (isset($_POST['filterStatus']) and isset($_POST['filterTime']) and isset($_POST['filterType'])) {
	$_SESSION["filterTime"] = $_POST['filterTime'];
	$_SESSION["filterStatus"] = $_POST['filterStatus'];
	$_SESSION["filterType"] = $_POST['filterType'];
    	echo json_encode(array("status"=>1));
	exit();
}

// search sessions
if (isset($_POST['search'])) {
	$_SESSION["search"] = $_POST['search'];
    	echo json_encode(array("status"=>1));
	exit();
}

// search sessions
if (isset($_POST['action'])) {
	$action = $_POST['action'];

	// check current folder
	if ($action == 'currentfolder') {
    	echo json_encode(array("folder"=>$_SESSION["folder"]));
	exit();
	}

	// go to parent folder
	if ($action == 'parentfolder') {
		$sql = "SELECT folder FROM folders where company = $companyId and id = ".$_SESSION["folder"];
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$_SESSION["folder"] = $row['folder'];
			}
		}
    	echo json_encode(array("status"=>1));
		exit();
	}
}

// status query
$statusQuery = "";
if ($_SESSION["filterStatus"] != "") {
	$_SESSION["filterType"] = "files";
	$statusQuery = " and status = '".$_SESSION["filterStatus"]."'";
}

// search query
$searchQuery = "";
if ($_SESSION["search"] != "") {
	$searchQuery = " and name LIKE '%".$_SESSION["search"]."%'";
	$_SESSION["search"] = "";
	$folderQuery = "";
}
	

// time query
$timeFileQuery = "";
$timeFolderQuery = "";
if ($_SESSION["filterTime"] == "month") {
	$timeFileQuery = " and MONTH(uploaded_on) = '".date("m")."'";
	$timeFolderQuery = " and MONTH(created_on) = '".date("m")."'";
}elseif ($_SESSION["filterTime"] == "week") {
	// SET DATEFIRST 1;
	$timeFileQuery = " and WEEK (uploaded_on) = WEEK( current_date ) and YEAR(uploaded_on) = YEAR( current_date )";
	$timeFolderQuery = "  and WEEK (created_on) = WEEK( current_date ) and YEAR(created_on) = YEAR( current_date )";
}elseif ($_SESSION["filterTime"] == "today") {
	$timeFileQuery = " and DATE(uploaded_on)=CURDATE()";
	$timeFolderQuery = " and DATE(created_on)=CURDATE()";
}



$filesEmpty = true;
$foldersEmpty = true;


// files that need signing
if($_SESSION["filterType"] == "files" or $_SESSION["filterType"] == "") {
// check if there is any signing request
$filesNeedsSigning = array();
$sql = "SELECT * FROM requests where email = '".$userEmail."' and status = '0' and signingkey NOT IN (".$hiddenFiles.") ORDER BY id DESC";
$companyResult = $conn->query($sql);
if ($companyResult->num_rows > 0) {  ?>
<div class="row">
    <div class="col-md-12">
        <div class="needs-signing">
        <p class="title">Pending signing requests</p>
<?php
	while($request = $companyResult->fetch_object()) {
    // get files on signing requests
    $sql = "SELECT * FROM files where sharing_key = '".$request->file."'";
    $result = $conn->query($sql);
        if ($result->num_rows > 0) {
        	$filesEmpty = false;
        	while($row = $result->fetch_assoc()) {
        ?>
        
        	<!-- folder -->
        	<div class="folder data-file to-sign" signing-key="<?=$request->signingkey?>" data-toggle="tooltip" data-placement="top" download-link="<?php echo $siteUrl; ?>/uploads/files/<?php echo $row['filename']; ?>" title="<?php echo $row['name']; ?>" data-sharing-link="<?php echo $siteUrl."/open/".$row['sharing_key']; ?>" data-type="file" data-key="<?php echo $row['sharing_key']; ?>" data-id="<?php echo $row['id']; ?>">
        		<img src="assets/images/pdf.png" class="img-responsive">
        		<p class="text-ellipsis"><?php echo $row['name']; ?></p>
        	</div>
        <?php 
        	}
        }
    } ?>
        </div>
    </div>
</div>
    <?php
    }
}


// files that need signing
if($_SESSION["filterType"] == "files" or $_SESSION["filterType"] == "") {
// check if there is any signing request
$filesNeedsSigning = array();
$sql = "SELECT * FROM requests where email = '".$userEmail."' and status != '0' and signingkey NOT IN (".$hiddenFiles.") ORDER BY id DESC";
$companyResult = $conn->query($sql);
if ($companyResult->num_rows > 0) {  ?>
<div class="row">
    <div class="col-md-12">
        <div class="needs-signing">
        <p class="title">Shared with me</p>
<?php
	while($request = $companyResult->fetch_object()) {
    // get files on signing requests
    $sql = "SELECT * FROM files where sharing_key = '".$request->file."'";
    $result = $conn->query($sql);
        if ($result->num_rows > 0) {
        	$filesEmpty = false;
        	while($row = $result->fetch_assoc()) {
        ?>
        
        	<!-- folder -->
        	<div class="folder data-file responded" signing-key="<?=$request->signingkey?>" data-toggle="tooltip" data-placement="top" download-link="<?php echo $siteUrl; ?>/uploads/files/<?php echo $row['filename']; ?>" title="<?php echo $row['name']; ?>" data-sharing-link="<?php echo $siteUrl."/open/".$row['sharing_key']; ?>" data-type="file" data-key="<?php echo $row['sharing_key']; ?>" data-id="<?php echo $row['id']; ?>">
        		<img src="assets/images/pdf.png" class="img-responsive">
        		<p class="text-ellipsis"><?php echo $row['name']; ?></p>
        	</div>
        <?php 
        	}
        }
    } ?>
        </div>
    </div>
</div>
    <?php
    }
}

if($_SESSION["filterType"] == "folders" or $_SESSION["filterType"] == "") {
// get folders
$sql = "SELECT * FROM folders where company = $companyId and id != 1 ".$folderQuery.$timeFolderQuery.$searchQuery." ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$foldersEmpty = false;
	while($row = $result->fetch_assoc()) {
?>

	<!-- folder -->
	<div class="folder data-folder" data-toggle="tooltip" data-placement="top" title="<?php echo $row['name']; ?>" data-type="folder" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>">
		<img src="assets/images/folder.png" class="img-responsive">
		<p class="text-ellipsis"><?php echo $row['name']; ?></p>
	</div>
<?php 
	}
}
}

if($_SESSION["filterType"] == "files" or $_SESSION["filterType"] == "") {
// get files 
$sql = "SELECT * FROM files where company = $companyId ".$folderQuery.$statusQuery.$timeFileQuery.$searchQuery." ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	$filesEmpty = false;
	while($row = $result->fetch_assoc()) {
?>

	<!-- folder -->
	<div class="folder data-file" data-toggle="tooltip" data-placement="top" download-link="<?php echo $siteUrl; ?>/uploads/files/<?php echo $row['filename']; ?>" title="<?php echo $row['name']; ?>" data-sharing-link="<?php echo $siteUrl."/open/".$row['sharing_key']; ?>" data-type="file" data-key="<?php echo $row['sharing_key']; ?>" data-id="<?php echo $row['id']; ?>">
		<img src="assets/images/pdf.png" class="img-responsive">
		<p class="text-ellipsis"><?php echo $row['name']; ?></p>
	</div>
<?php 
	}
}
}


if($foldersEmpty == true and $filesEmpty == true){
 ?>
<div class="center-notify">
	<i class="ion-ios-information-outline"></i>
 	<h3>No files or folders found!</h3>
</div>

<?php } ?>
