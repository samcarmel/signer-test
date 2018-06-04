<?php
// Include Global files
include_once("../includes/global.php");

if($role == 'staff'){
    exit("Access denied");
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
    <title>Team | Sign documents online</title>
    <!-- Ion icons -->
    <link href="assets/fonts/ionicons/css/ionicons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/libs/switchery/switchery.min.css" rel="stylesheet">
    <link href="assets/libs/dropify/css/dropify.css" rel="stylesheet">
    <link href="assets/libs/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="assets/libs/toastr/toastr.min.css" rel="stylesheet">
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
			<button class="btn btn-primary" data-toggle="modal" data-target="#addTeam"><i class="ion-plus-round"></i> Add Team</button>
		</div>
		<h3>Team</h3>
	</div>
	<div class="row">
<?php 
$sql = "SELECT * FROM users where company = $companyId and role = 'staff' ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$permissions = json_decode($row['permissions']);
?><!-- Team member -->
		<div class="col-md-4">
			<div class="light-card team-card-info text-center">
				<img src="uploads/avatar/<?php echo $row['avatar']; ?>" class="img-circle">
				<h4><?php echo $row['fname']." ".$row['lname']; ?></h4>
				<p><?php echo $row['email']; ?></p>
				<div class="team-card-extra">
					<p class="pull-left">
					    <?php if(in_array("delete", $permissions)){ ?>
						<span class="text-danger"  data-toggle="tooltip" data-placement="top" title="Can Delete"><i class="ion-ios-circle-filled"></i></span>
						<?php } if(in_array("upload", $permissions)){ ?>
						<span class="text-success"  data-toggle="tooltip" data-placement="top" title="Can Upload"><i class="ion-ios-circle-filled"></i></span>
						<?php } if(in_array("sign", $permissions)){ ?>
						<span class="text-primary" data-toggle="tooltip" data-placement="top" title="Can Sign"><i class="ion-ios-circle-filled" ></i></span>
						<?php } ?>
					</p>
					<div class="dropup">
					<span class="team-action dropdown-toggle"  data-toggle="dropdown"><i class="ion-ios-more-outline"></i></span>
					    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
					      <li role="presentation"><a role="menuitem" class="edit-team" member-id="<?php echo $row['id']; ?>"   data-toggle="modal" data-target="#editTeam">Edit</a></li>
					      <li role="presentation" class="divider"></li>
					      <li role="presentation"><a role="menuitem" id="<?php echo $row['id']; ?>" delete-item="<?php echo $row['fname']; ?>" class="delete-team" tabindex="-1" href="#">Delete</a></li>
					    </ul>
					  </div>
				</div>
			</div>
		</div>
		<!-- End team member -->
<?php 
	}
}else{ ?>
<div class="center-notify">
	<i class="ion-ios-information-outline"></i>
 	<h3>No teams added yet!</h3>
</div>

<?php }
?>
	</div>
</div>

<!-- footer -->
<?php include_once("../includes/footer.php"); ?>

<!-- Modals -->

<!-- add team modal -->
  <!-- Modal -->
  <div class="modal fade" id="addTeam" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add Team Member</h4>
        </div>
          <form  class="add-team-form" action="files/ajaxProcesses.php" method="post" enctype="multipart/form-data" data-parsley-validate="">
        <div class="modal-body">
	<div class="alert alert-info alert-dismissable text-center saving" style="display: none;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="ion-loading-c"></i>  Saving...
	</div>
          <p>Fill in team member's details, the member will receive <br>an email with account information.</p>
	  <div class="form-group">
	  <div class="col-md-6 p-l-o">
	    <label>First name</label>
	    <input type="text" class="form-control" name="fname" placeholder="First name" data-parsley-required="true">
	    <input type="hidden" name="action" value="addTeam">
	  </div>
	  <div class="col-md-6 p-r-o">
	    <label>Last name</label>
	    <input type="text" class="form-control" name="lname"  placeholder="Last name" data-parsley-required="true">
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-6 p-l-o">
	    <label>Email address</label>
	    <input type="email" class="form-control" name="email" placeholder="Email address" data-parsley-required="true">
	  </div>
	  <div class="col-md-6 p-r-o">
	    <label>Phone number</label>
	    <input type="text" class="form-control" name="phone" placeholder="Phone number">
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-6 p-l-o">
	    <label>Password</label>
	    <input type="password" class="form-control" name="password" id="password" placeholder="Password" data-parsley-required="true" data-parsley-minlength="6" data-parsley-error-message="Password is too short!">
	  </div>
	  <div class="col-md-6 p-r-o">
	    <label>Confirm password</label>
	    <input type="password" class="form-control" placeholder="Confirm password" data-parsley-required="true" data-parsley-equalto="#password" data-parsley-error-message="Passwords don't Match!">
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Profile picture <span class="text-muted text-xs">Atleast 200x200</span></label>
	    <input type="file" id="input-file-now-custom-1" name="avatar" class="dropify"  data-parsley-required="true" data-min-width="200" data-min-height="200" data-allowed-file-extensions="png jpg">
	  </div>
	  </div>
	  <div class="form-group permissions">
	  <div class="col-md-12 p-l-o">
	  <label>Permissions</label>
	  </div>
	  <div class="col-md-4 p-l-o">
	    <input type="checkbox" class="js-switch" name="permissions[]" value="upload" checked readonly/>  Can upload
	  </div>
	  <div class="col-md-4">
	    <input type="checkbox" class="js-switch" name="permissions[]" value="sign" checked  /> Can sign
	  </div>
	  <div class="col-md-4 p-r-o">
	    <input type="checkbox" class="js-switch" name="permissions[]" value="delete" checked  /> Can delete
	  </div>
	  </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Team Member</button>
        </div>
	</form>
      </div>
      
    </div>
  </div>

<!-- add team modal -->
  <!-- Modal -->
  <div class="modal fade" id="editTeam" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Edit account information </h4>
        </div>
          <form  class="edit-team-form" action="files/ajaxProcesses.php" method="post" enctype="multipart/form-data" data-parsley-validate="">
        <div class="modal-body">

          <p>Update in team member's details.</p>

	<div class="alert alert-info alert-dismissable text-center saving" style="display: none;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="ion-loading-c"></i>  Saving...
	</div>
<div class="center-notify">
	<i class="ion-loading-c"></i>
</div>
<div class="edit-fields"></div>

        </div>
        <div class="modal-footer" style="display: none;">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
	</form>
      </div>
      
    </div>
  </div>


   <!-- scripts -->
   <script src="assets/js/jquery-3.2.1.min.js"></script>
   <script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/libs/dropify/js/dropify.min.js"></script>
   <script src="assets/libs/switchery/switchery.min.js"></script>
   <script src="assets/libs/toastr/toastr.min.js"></script>
   <script src="assets/js/parsley.min.js"></script>
   <script src="assets/libs/sweetalert/sweetalert.min.js"></script>

   <!-- custom scripts -->
   <script src="assets/js/app.js"></script>
   <script type="text/javascript">
   		// Dropify
	$('.dropify').dropify();
   </script>
</body>
</html>