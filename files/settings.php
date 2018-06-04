<?php
// Include Global files
include_once("../includes/global.php"); 

// profile settings fields 
$sql = "SELECT * FROM users WHERE id = $userId";
$result = $conn->query($sql);
$profile = $result->fetch_assoc();

// company settings fields 
$sql = "SELECT * FROM companies WHERE id = $companyId";
$result = $conn->query($sql); 
$company = $result->fetch_assoc();

// system settings fields 
$sql = "SELECT * FROM settings WHERE id = 1";
$result = $conn->query($sql);
$system = $result->fetch_assoc();

if (empty($profile['avatar'])) {
    $profile['avatar'] = "avatar.png";
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
    <title>Settings | Sign documents online</title>
    <!-- Ion icons -->
    <link href="assets/fonts/ionicons/css/ionicons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/libs/dropify/css/dropify.css" rel="stylesheet">
    <link href="assets/libs/switchery/switchery.min.css" rel="stylesheet">
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
		<h3>Settings</h3>
	</div>
	<div class="light-card settings-card">
		<div class="settings-menu">
			<ul>
				<li class="active"><a  data-toggle="tab" href="#profile">Profile</a></li>
				<?php if($role == 'admin' or $role == 'superadmin'){ ?>
				<li><a  data-toggle="tab" href="#company">Company</a></li>
				<li><a  data-toggle="tab" href="#reminders">Reminders</a></li>
				<?php } ?>
				<?php if($role == 'superadmin'){ ?>
				<li><a  data-toggle="tab" href="#system">System</a></li>
				<?php } ?>
				<li><a  data-toggle="tab" href="#password">Password</a></li>
			</ul>
		</div>
		<div class="settings-forms">
			<div class="col-md-5 tab-content">
			<div class="alert alert-info alert-dismissable text-center saving" style="display: none;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<i class="ion-loading-c"></i>  Saving...
			</div>
			<!-- Profile start -->
				<div id="profile" class="tab-pane fade in active">
		<h4>Profile</h4>
			<form class="profile-form" action="files/ajaxProcesses.php" enctype="multipart/form-data">

				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>Profile picture <span class="text-muted text-xs"> Atleast 200x200</span></label>
				    <input type="file" name="avatar" class="dropify" data-default-file="uploads/avatar/<?php echo $profile['avatar']; ?>" data-min-width="200" data-min-height="200" data-allowed-file-extensions="png jpg">
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>First name</label>
				    <input type="text" class="form-control profile-first-name" name="fname" value="<?php echo $profile['fname']; ?>" placeholder="First name" required>
				    <input type="hidden" name="action" value="profile">
				    <input type="hidden" name="oldAvatar" value="<?php echo $profile['avatar']; ?>">
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>Last name</label>
				    <input type="text" class="form-control profile-last-name" name="lname" value="<?php echo $profile['lname']; ?>" placeholder="Last name" required>
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>Email address</label>
				    <input type="email" class="form-control profile-email" name="email" value="<?php echo $profile['email']; ?>" placeholder="Email address" required>
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>Phone number</label>
				    <input type="text" class="form-control profile-phone" name="phone" value="<?php echo $profile['phone']; ?>" placeholder="Phone number">
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o text-right">
				    <button class="btn btn-primary" type="submit">Save Changes</button>
				  </div>
				  </div>
			</form>
					
				</div>
				<!-- profile end -->
				<?php if($role == 'admin' or $role == 'superadmin'){ ?>
			<!-- Company start -->
				<div id="reminders" class="tab-pane fade">
		<h4>Reminders</h4>
		<p>Reminders are emails sent to someone when no action has been taken by them after a signing request had been sent. 
		The emails will be sent after the sent number of days</p>
			<form class="reminders-form" action="files/ajaxProcesses.php" data-parsley-validate="">
			    <input type="hidden" name="action" value="savereminders">
				<div class="form-group">
					<div class="col-md-12 p-lr-o">
						<input type="checkbox" id="enable-reminders" class="js-switch" name="enable_reminders" value="1" <?php if($company['reminders'] == 1){ ?>checked<?php } ?> />
						<label for="enable-reminders">Enable reminders</label>
					</div>
				</div>
	<div class="panel-group reminders-holder" id="accordion">
<?php 
$i = 1;
$sql = "SELECT * FROM reminders where company = ".$companyId." ORDER BY id ASC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($reminder = $result->fetch_object()) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">
			    <?php if($i > 1){ ?><span class="delete-reminder" data-toggle="tooltip" title="Remove reminder"><i class="ion-ios-trash"></i></span><?php } ?>
				<h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" href="#collapse<?=$i;?>">Reminder #<span><?=$i;?></span></a></h4>
			</div>
			<div class="panel-collapse collapse <?php if($i == 1){ ?>in<?php } ?>" id="collapse<?=$i;?>">
				<div class="panel-body">
					<div class="remider-item">
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
							    <input type="hidden" name="count[]" value="1">
								<label>Email subject</label> <input class="form-control" name="subject[]" placeholder="Email subject" required type="text" value="<?=$reminder->subject;?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
								<label>Days after request is sent</label> <input class="form-control" name="days[]" min="1" placeholder="Days after request is sent" required type="number" value="<?=$reminder->days;?>">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
								<label>Message</label> 
<textarea class="form-control" name="message[]" required rows="9"><?=$reminder->message;?></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?
	    $i++;
	}
	}else{ ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" href="#collapse1">Reminder #<span>1</span></a></h4>
			</div>
			<div class="panel-collapse collapse in" id="collapse1">
				<div class="panel-body">
					<div class="remider-item">
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
							    <input type="hidden" name="count[]" value="1">
								<label>Email subject</label> <input class="form-control" name="subject[]" placeholder="Email subject" required type="text" value="Signing invitation reminder">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
								<label>Days after request is sent</label> <input class="form-control" name="days[]" min="1" placeholder="Days after request is sent" required type="number" value="7">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
								<label>Message</label> 
								<textarea class="form-control" name="message[]" required rows="8">Hello there,

I hope you are doing well.
I am writing to remind you about the signing request I had sent earlier.

Cheers!
<?=$fullName;?>
</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
			    <span class="delete-reminder" data-toggle="tooltip" title="Remove reminder"><i class="ion-ios-trash"></i></span>
				<h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" href="#collapse2">Reminder #<span>2</span></a></h4>
			</div>
			<div class="panel-collapse collapse" id="collapse2">
				<div class="panel-body">
					<div class="remider-item">
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
							    <input type="hidden" name="count[]" value="1">
								<label>Email subject</label> <input class="form-control" name="subject[]" placeholder="Email subject" required type="text" value="Signing invitation reminder">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
								<label>Days after request is sent</label> <input class="form-control" name="days[]" min="1" placeholder="Days after request is sent" required type="number" value="7">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
								<label>Message</label> 
								<textarea class="form-control" name="message[]" required rows="8">Hello there,

I hope you are doing well.
I am writing to remind you about the signing request I had sent earlier.

Cheers!
<?=$fullName;?>
</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
			    <span class="delete-reminder" data-toggle="tooltip" title="Remove reminder"><i class="ion-ios-trash"></i></span>
				<h4 class="panel-title"><a data-parent="#accordion" data-toggle="collapse" href="#collapse3">Reminder #<span>3</span></a></h4>
			</div>
			<div class="panel-collapse collapse" id="collapse3">
				<div class="panel-body">
					<div class="remider-item">
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
							    <input type="hidden" name="count[]" value="1">
								<label>Email subject</label> <input class="form-control" name="subject[]" placeholder="Email subject" required type="text" value="Signing invitation reminder">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
								<label>Days after request is sent</label> <input class="form-control" name="days[]" min="1" placeholder="Days after request is sent" required type="number" value="7">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-12 p-lr-o">
								<label>Message</label> 
								<textarea class="form-control" name="message[]" required rows="8">Hello there,

I hope you are doing well.
I am writing to remind you about the signing request I had sent earlier.

Cheers!
<?=$fullName;?>
</textarea>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php } ?>
	</div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o text-right">
				    <button class="btn btn-default add-reminder" type="button">Add reminder</button>
				    <button class="btn btn-primary" type="submit">Save Changes</button>
				  </div>
				  </div>
			</form>
					
				</div>
				<!-- Company end -->
			<!-- Company start -->
				<div id="company" class="tab-pane fade">
		<h4>Company</h4>
			<form class="company-form" action="files/ajaxProcesses.php">
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>Company name</label>
				    <input type="text" class="form-control company-name" placeholder="Company name" value="<?php echo $company['name']; ?>" required>
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>Email address</label>
				    <input type="email" class="form-control company-email" placeholder="Email address" value="<?php echo $company['email']; ?>" required>
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>Phone number</label>
				    <input type="text" class="form-control company-phone" placeholder="Phone number" value="<?php echo $company['phone']; ?>">
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o text-right">
				    <button class="btn btn-primary" type="submit">Save Changes</button>
				  </div>
				  </div>
			</form>
					
				</div>
				<!-- Company end -->
				<?php } ?>
				<?php if($role == 'superadmin'){ ?>
			<!-- System start -->
				<div id="system" class="tab-pane fade">
		<h4>System</h4>
			<form class="system-form" action="files/ajaxProcesses.php" enctype="multipart/form-data">
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>System name</label>
				    <input type="text" class="form-control system-name" placeholder="System name" name="name" value="<?php echo $system['name']; ?>" required>
				  </div>
				  </div>
				  <div class="form-group system-logo">
				  <div class="col-md-12 p-lr-o">
				    <label>System Logo <span class="text-muted text-xs">541x152</span></label>
				    <input type="file" name="logo" class="dropify" data-default-file="assets/images/<?php echo $system['logo']; ?>" data-allowed-file-extensions="png">
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>System favicon/icon <span class="text-muted text-xs">152x152</span></label>
				    <input type="file" name="favicon" class="dropify" data-default-file="assets/images/<?php echo $system['favicon']; ?>" data-allowed-file-extensions="png">
				  </div>
				  </div>
				  <div class="divider"></div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>SMTP username</label>
				    <input type="text" class="form-control system-username" name="username" placeholder="SMTP username" value="<?php echo $system['smtp_username']; ?>" required>
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>SMTP host</label>
				    <input type="text" class="form-control system-host" placeholder="SMTP host" name="host" value="<?php echo $system['smtp_host']; ?>" required>
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>SMTP port</label>
				    <input type="text" class="form-control system-port" placeholder="SMTP port" name="port" value="<?php echo $system['smtp_port']; ?>" required>
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>SMTP password</label>
				    <input type="password" class="form-control system-password" placeholder="SMTP password" name="password" value="<?php echo $system['smtp_password']; ?>" required>
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>SMTP secure</label>
				    <input type="text" class="form-control system-secure" placeholder="SMTP secure" name="secure" value="<?php echo $system['smtp_secure']; ?>" required>
				    <input type="hidden" name="action" value="system">
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o text-right">
				    <button class="btn btn-primary" type="submit">Save Changes</button>
				  </div>
				  </div>
			</form>
					
				</div>
				<!-- system end -->
				<?php } ?>
			<!-- password start -->
				<div id="password" class="tab-pane fade">
		<h4>Password</h4>
			<form  class="password-form" action="files/ajaxProcesses.php" data-parsley-validate="">
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>Current password</label>
				    <input type="password" class="form-control password-current" required placeholder="Current password">
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>New password</label>
				    <input type="password" class="form-control password-new" data-parsley-required="true" data-parsley-minlength="6" data-parsley-error-message="Password is too short!" id="newPassword" placeholder="New password">
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o">
				    <label>Confirm password</label>
				    <input type="password" class="form-control password-confirm" data-parsley-required="true" data-parsley-equalto="#newPassword" data-parsley-error-message="Passwords don't Match!" placeholder="Confirm password">
				  </div>
				  </div>
				  <div class="form-group">
				  <div class="col-md-12 p-lr-o text-right">
				    <button class="btn btn-primary" type="submit">Save Changes</button>
				  </div>
				  </div>
			</form>
					
				</div>
				<!-- password end -->
			</div>
		</div>
	</div>
</div>

<!-- footer -->
<?php include_once("../includes/footer.php"); ?>
<div class="loading-overlay"><i class="ion-loading-c"></i></div>

   <!-- scripts -->
   <script>
       var fullName = "<?=$fullName;?>";
   </script>
   <script src="assets/js/jquery-3.2.1.min.js"></script>
   <script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/libs/dropify/js/dropify.min.js"></script>
   <script src="assets/js/parsley.min.js"></script>
   <script src="assets/libs/switchery/switchery.min.js"></script>
   <script src="assets/libs/toastr/toastr.min.js"></script>

   <!-- custom scripts -->
   <script src="assets/js/app.js"></script>

   <script type="text/javascript">
   		// Dropify
	$('.dropify').dropify({
    error: {
        'minWidth': 'The image width is too small ({{ value }}}px min).',
        'minHeight': 'The image height is too small ({{ value }}}px min).',
        'imageFormat': 'The image format is not allowed ({{ value }} only).'
    }
});
   </script>
</body>
</html>