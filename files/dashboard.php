<?php
// Include Global files
include_once("../includes/global.php"); 
// get signature 
$sql = "SELECT signature FROM users where id = $userId";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
                    $signature = $row['signature'];
                }
            }
if($signature == ""){
    $signature = "demo.png";
}

$_SESSION["folder"] = 1;
$_SESSION["filterTime"] = "";
$_SESSION["filterStatus"] = "";
$_SESSION["filterType"] = "";
$_SESSION["search"] = "";

// count team members
$sql = "SELECT * FROM users where company = $companyId";
$result = $conn->query($sql);
$teamCount = $result->num_rows;

// count folders
$sql = "SELECT * FROM folders where company = $companyId and id > 1";
$result = $conn->query($sql);
$foldersCount = $result->num_rows;

// count signing requests
$sql = "SELECT * FROM requests where company = $companyId and status = '0'";
$result = $conn->query($sql);
$signingRequestCount = $result->num_rows;

// count signed files
$sql = "SELECT * FROM files where company = $companyId and status = 'signed'";
$result = $conn->query($sql);
$signedFilesCount = $result->num_rows;

// count unsigned files
$sql = "SELECT * FROM files where company = $companyId and status = 'unsigned'";
$result = $conn->query($sql);
$unSignedFilesCount = $result->num_rows;

$totalFiles = $unSignedFilesCount + $signedFilesCount;
if ($totalFiles == 0) {
	$signedPercentage = 0;
}else{
	$signedPercentage = round(($signedFilesCount / $totalFiles) * 100);
}

// check user permissions
$sql = "SELECT permissions FROM users where id = $userId";
$result = $conn->query($sql);
$permission = $result->fetch_object();

$permissions = json_decode($permission->permissions);

$canDelete = $canSign = false;

if(in_array("delete", $permissions)){
    $canDelete = true;
}
if(in_array("sign", $permissions)){
    $canSign = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/<?php echo $systemFavicon; ?>">
    <title>Signer | Sign documents online</title>
    <!-- Ion icons -->
    <link href="assets/fonts/ionicons/css/ionicons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/libs/switchery/switchery.min.css" rel="stylesheet">
    <link href="assets/libs/toastr/toastr.min.css" rel="stylesheet">
    <link href="assets/libs/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Signer CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<!-- header start -->
<?php include_once("../includes/header.php"); ?>

<!-- leftbar -->
<?php include_once("../includes/leftbar.php"); ?>

<div class="content">
	<div class="page-title">
		<div class="pull-right page-actions">
			<a href="signature" class="btn btn-primary-ish"><i class="ion-edit"></i> Signature</a>
			<a href="documents" class="btn btn-primary"><i class="ion-document-text"></i> Documents</a>
		</div>
		<h3>Dashboard</h3>
	</div>
	<div class="row">
		<!-- Widget knob -->
		<div class="col-md-4">
			<div class="light-card widget">
			<h5>Signed percentage</h5>
			<div class="text-center widget-knob">
				<input type="text" value="<?php echo $signedPercentage; ?>" class="dial" data-linecap="round" data-fgColor="#3DA4FF" readonly>
			</div>
				<div class="widget-knob-extra">
					<p class="pull-left">
						<span class="text-primary"><i class="ion-ios-circle-filled"></i></span>
						<span class="count"> <?php echo $signedFilesCount; ?> </span>
						<span class="text-xs">Signed</span>
					</p>
					<p class="pull-right">
						<span class="text-danger"><i class="ion-ios-circle-filled"></i></span>
						<span class="count"> <?php echo $unSignedFilesCount; ?> </span>
						<span class="text-xs">Un-Signed</span>
					</p>
				</div>
			</div>
		</div>
		<!-- End widget knob -->
		<!-- awaiting signing -->
		<div class="col-md-4">
			<div class="light-card widget-count">
				<span class="text-warning"><i class="ion-edit"></i></span>
				<h4><?php echo $signingRequestCount; ?></h4>
				<p>Awaiting signing</p>
			</div>
		</div>
		<!-- awaiting signing -->
		<div class="col-md-4">
			<div class="light-card widget-signature">
				<img src="uploads/signatures/<?php echo $signature; ?>" class="img-responsive">
				<p>Your Signature</p>
			</div>
		</div>
		<!-- awaiting signing -->
		<div class="col-md-4">
			<div class="light-card widget-count">
				<span class="text-primary"><i class="ion-folder"></i></span>
				<h4><?php echo $foldersCount; ?></h4>
				<p>Folders</p>
			</div>
		</div>
		<!-- awaiting signing -->
		<div class="col-md-4">
			<div class="light-card widget-count">
				<span class="text-success"><i class="ion-ios-people"></i></span>
				<h4><?php echo $teamCount; ?></h4>
				<p>Team members</p>
			</div>
		</div>
		<!-- End team member -->
	</div>
	<div class="row">
		<div class="col-md-12">	
		<div class="page-title documents-page">
			<div class="pull-right page-actions">
			<a href="documents" class="btn btn-primary"><i class="ion-document-text"></i> Documents</a>
			</div>
			<div class="pull-left">
				<h3 class="pull-left">Documents</h3>
				<button href="" class="btn btn-default go-back"><i class="ion-ios-arrow-back"></i> Back</button> 
			</div>

		</div>
		</div>
		<div class="col-md-12">
			<div class="documents-filter light-card hidden-xs">
				<div class="light-card-title">
					<h4>Filter</h4>
				</div>
				<div class="documents-filter-form">
					          <form>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio1" type="radio" name="status" value="" checked><span class="outer"><span class="inner"></span></span>All</label>
						  </div>
						  </div>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio1" type="radio" name="status" value="signed"><span class="outer"><span class="inner"></span></span>Signed</label>
						  </div>
						  </div>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio2" type="radio" name="status" value="unsigned"><span class="outer"><span class="inner"></span></span>Un-Signed</label>
						  </div>
						  </div>
						  <div class="divider"></div>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio1" type="radio" name="type" value="" checked><span class="outer"><span class="inner"></span></span>All</label>
						  </div>
						  </div>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio1" type="radio" name="type" value="files"><span class="outer"><span class="inner"></span></span>Files</label>
						  </div>
						  </div>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio2" type="radio" name="type" value="folders"><span class="outer"><span class="inner"></span></span>Folders</label>
						  </div>
						  </div>
						  <div class="divider"></div>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio1" type="radio" name="time" checked><span class="outer"><span class="inner"></span></span>All</label>
						  </div>
						  </div>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio1" type="radio" name="time" value="today"><span class="outer"><span class="inner"></span></span>Today</label>
						  </div>
						  </div>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio1" type="radio" name="time" value="week"><span class="outer"><span class="inner"></span></span>This week</label>
						  </div>
						  </div>
						  <div class="form-group">
						  <div class="col-md-12 p-lr-o">
						  <label class="radio"><input id="radio1" type="radio" name="time" value="month"><span class="outer"><span class="inner"></span></span>This Month</label>
						  </div>
						  </div>
						  </form>
				</div>
			</div>
			<div class="row documents-grid">
				<div class="center-notify fetching">
					<i class="ion-loading-c"></i>
				</div>
				<div class="col-md-12 content-list">
				</div>
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php include_once("../includes/footer.php"); ?>





  <!-- folder right click -->
    <div id="folder-menu" class="dropdown clearfix">
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
      <li><a tabindex="-1" class="open-folder" href="javascript:void(0)">Open</a>
      </li>
      <li><a tabindex="-1" class="rename-folder" href="javascript:void(0)">Rename</a>
      </li>
      <?php if($canDelete){ ?>
      <li class="divider"></li>
      <li><a tabindex="-1" class="delete-folder" href="javascript:void(0)">Delete</a>
      </li>
      <?php } ?>
    </ul>
  </div>
  <!--  file right click -->
    <div id="file-menu" class="dropdown clearfix">
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
      <li><a tabindex="-1" class="open-file" href="javascript:void(0)">Open</a>
      </li>
      <?php if($canSign){ ?>
      <li><a tabindex="-1" class="sign-file" href="javascript:void(0)">Sign</a>
      </li>
      <?php } ?>
      <li><a tabindex="-1" class="rename-file" href="javascript:void(0)">Rename</a>
      </li>
      <li><a tabindex="-1" class="duplicate-file" href="javascript:void(0)">Duplicate</a>
      </li>
      <li><a tabindex="-1" href="" class="share-file" data-toggle="modal" data-target="#share">Share</a>
      </li>
      <li><a tabindex="-1" href="" class="download-file" download>Download</a>
      </li>
      <?php if($canDelete){ ?>
      <li class="divider"></li>
      <li><a tabindex="-1" class="delete-file" href="javascript:void(0)">Delete</a>
      </li>
      <?php } ?>
    </ul>
  </div>
  
  
  <!--  file right click -->
    <div id="file-menu-2" class="dropdown clearfix">
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu" style="display:block;position:static;margin-bottom:5px;">
      <li><a tabindex="-1" class="open-file-2" href="javascript:void(0)">Open</a>
      </li>
      <li><a tabindex="-1" class="sign-file-2" href="javascript:void(0)">Sign</a>
      </li>
      <li><a tabindex="-1" href="" class="download-file" download>Download</a>
      </li>
      <li class="divider"></li>
      <li><a tabindex="-1" class="delete-file-2" href="javascript:void(0)">Delete</a>
      </li>
    </ul>
  </div>


  <!-- Rename folder Modal -->
  <div class="modal fade" id="renamefolder" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Rename folder</h4>
        </div>
          <form class="rename-folder-form" action="files/ajaxProcesses.php" method="post"  data-parsley-validate="">
        <div class="modal-body">
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Folder name</label>
	    <input type="text" class="form-control folder-name" name="name" placeholder="Folder name"  data-parsley-required="true">
	    <input type="hidden" name="action" value="renameFolder">
	    <input type="hidden" name="folderId" class="folder-id">
	  </div>
	  </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
	</form>
      </div>
      
    </div>
  </div>
    <!-- Share Modal -->
  <div class="modal fade shareFile" id="share" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Share</h4>
        </div>
        <div class="modal-body">
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Sharing link</label>
	    <input type="text" id="foo" class="form-control" value="https://signer.simcycreative.com/open/dshbd7yr7dnindiqy3" placeholder="Sharing link" readonly="readonly">
	  </div>
	  </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary copy-link"  data-clipboard-action="copy" data-clipboard-target="#foo">Copy Link</button>
        </div>
      </div>
      
    </div>
  </div>
  
  <!-- Rename file Modal -->
  <div class="modal fade" id="renamefile" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Rename file</h4>
        </div>
          <form class="rename-file-form" action="files/ajaxProcesses.php" method="post"  data-parsley-validate="">
        <div class="modal-body">
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Folder name</label>
	    <input type="text" class="form-control file-name" name="name" placeholder="File name"  data-parsley-required="true">
	    <input type="hidden" name="action" value="renameFile">
	    <input type="hidden" name="fileId" class="file-id">
	  </div>
	  </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button class="btn btn-primary" type="submit">Save Changes</button>
        </div>
	</form>
      </div>
      
    </div>
  </div>
  
  <div class="loading-overlay"><i class="ion-loading-c"></i></div>
  
   <!-- scripts -->
   <script src="assets/js/jquery-3.2.1.min.js"></script>
   <script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/libs/switchery/switchery.min.js"></script>
   <script src="assets/libs/sweetalert/sweetalert.min.js"></script>
   <script src="assets/libs/toastr/toastr.min.js"></script>
   <script src="assets/js/parsley.min.js"></script>
   <script src="assets/libs/knob/jquery.knob.min.js"></script>
   <script src="assets/libs/clipboard/clipboard.min.js"></script>

   <!-- custom scripts -->
   <script src="assets/js/app.js"></script>
   <script>
    $(function() {
        $(".dial").knob();
    });

    $(document).ready(function () {
	    var clipboard = new Clipboard('.copy-link');

	    clipboard.on('success', function(e) {
	    	$('#share').modal('hide');
	        	toastr.success("Link copied to clipboard.", "Copied!");
	    });

	    clipboard.on('error', function(e) {
	        	toastr.error("Failed to copy, please try again.", "Oops!");
	    });

		getContent();
	});
</script>
</body>
</html>