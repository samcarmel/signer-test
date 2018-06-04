<?php
// Which tab to show, login, forgot password or reset password
if (isset($_GET["action"])) {
	$action = $_GET["action"];
}else{
	$action = "login";
}

// Define functions
function hash_compare($a, $b) { 
	if (!is_string($a) || !is_string($b)) { 
		return false; 
	} 
	
	$len = strlen($a); 
	if ($len !== strlen($b)) { 
		return false; 
	} 

	$status = 0; 
	for ($i = 0; $i < $len; $i++) { 
		$status |= ord($a[$i]) ^ ord($b[$i]); 
	} 
	return $status === 0; 
}

// Include site configuration
include_once("../config.php");

//Start Session
session_start();

// Handle already logged in agents
if(isset($_SESSION['userId']) && isset($_SESSION['companyId'])) {
	header('Location: dashboard');
}

// Handle incoming Login Messages
$msg = array();
if (isset($_GET['e'])) {
	$msg[$_GET['e']] = true;
}

// Log in users
if(isset($_POST['login'])) {
	 $sql = "SELECT id, role, company, password FROM users WHERE email='" . $conn->real_escape_string($_POST['email']) . "'";
	$result = $conn->query($sql);
	if($result->num_rows == 1) {													// If an agent is found
		$user = $result->fetch_assoc();
		if(hash_compare(hash('sha256', $_POST['password']), $user['password'])) {
			$_SESSION['userId'] = $user['id'];
			$_SESSION['role'] = $user['role'];
			$_SESSION['companyId'] = $user['company'];
			if(isset($_COOKIE['redirect'])) {
				$redirect = $_COOKIE['redirect'];
				unset($_COOKIE['redirect']);
				setcookie('redirect', null, -1, '/');
				header('Location: '.$redirect);
			}else{
				header('Location: dashboard');
			}
		}else {
			header('HTTP/1.0 401 Unauthorized');
			$msg['incorrect'] = true;
		}
	}else if($result->num_rows == 0) {
		// Not found on users table
		header('HTTP/1.0 401 Unauthorized');
		$msg['incorrect'] = true;
	}else {																	
	            // If there exist more than one agent or the query failed
		header('HTTP/1.0 500 Internal Server Error');
		$msg['server'] = true;
	}
}

// reset action
if(isset($_GET['action']) and $_GET['action'] == "reset") {
$sql = "SELECT token FROM users where token = '" . $conn->real_escape_string($_GET['token']) . "'";
$result = $conn->query($sql);
if ($result->num_rows < 1) {
                $msg['invalidToken'] = true;
        }else{

}
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
    <title>Login | Sign documents online</title>
    <!-- Ion icons -->
    <link href="assets/fonts/ionicons/css/ionicons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/libs/toastr/toastr.min.css" rel="stylesheet">
    <link href="assets/libs/sweetalert/sweetalert.css" rel="stylesheet">
    <!-- Signer CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-84926915-3"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-84926915-3');
</script>

</head>
<body>

<div class="login-card">
<img src="assets/images/<?php echo $systemFavicon; ?>" class="img-responsive">
	<div class="sign-in" <?php if($action == "reset" or $action == "forgot"){ ?> style="display: none;" <?php } ?>>
		<h5>Sign in to your account</h5>
		<!-- Messages -->
		<?php if(isset($msg['signupsuccessful'])) { ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Registration Successful, login.
			</div>
		<?php } if(isset($msg['server'])) { ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Something went wrong, Please try again. 
			</div>
		<?php } if(isset($msg['incorrect'])) { ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Incorrect Email or Password.
			</div>
		<?php } if(isset($msg['login'])) { ?>
			<div class="alert alert-warning alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Please Log in to continue.
			</div>
		<?php } if(isset($msg['logout'])) { ?>
			<div class="alert alert-info alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				You have been logged out.
			</div>
		<?php } if(isset($msg['reset'])) { ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Password successfully reset.
			</div>
		<?php } ?>
          <form class="text-left" action="" method="post">
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Email address</label>
	    <input type="email" class="form-control" name="email" value="demo@simcycreative.com" placeholder="Email address" required>
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Password</label>
	    <input type="Password" class="form-control" name="password" value="passqw" placeholder="Password" required>
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	  <p class="pull-left m-t-5"><a  href="javascript:void(0)" target="forgot-password">Forgot password?</a></p>
	    <button class="btn btn-primary pull-right" type="submit" name="login">Sign In</button>
	  </div>
	  </div>
	</form>
	</div>
	<div class="sign-up" style="display: none;">
		<h5>Create a free account</h5>
		<!-- Messages -->
		<?php if(isset($msg['signupsuccessful'])) { ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Registration Successful, login.
			</div>
		<?php } if(isset($msg['server'])) { ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Something went wrong, Please try again. 
			</div>
		<?php } if(isset($msg['incorrect'])) { ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Incorrect Email or Password.
			</div>
		<?php } if(isset($msg['login'])) { ?>
			<div class="alert alert-warning alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Please Log in to continue.
			</div>
		<?php } if(isset($msg['logout'])) { ?>
		<?php } if(isset($msg['reset'])) { ?>
			<div class="alert alert-success alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Password successfully reset.
			</div>
		<?php } ?>

			<div class="alert alert-info alert-dismissable sign-up-msg" style="display: none;">
				Please wait....
			</div>
          <form class="text-left sign-up-form" action="" method="post" data-parsley-validate="">
	  <div class="form-group">
	  <div class="col-md-6 p-l-o">
	    <label>First name</label>
	    <input type="text" class="form-control" name="fname" placeholder="First name" required>
	  </div>
	  <div class="col-md-6 p-r-o">
	    <label>Last name</label>
	    <input type="text" class="form-control" name="lname" placeholder="Last name" required>
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Company</label>
	    <input type="text" class="form-control" name="company" placeholder="Company" required>
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Email address</label>
	    <input type="hidden" name="action" value="signup">
	    <input type="email" class="form-control" name="email" placeholder="Email address" required>
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-6 p-l-o">
	    <label>New Password</label>
	    <input type="Password" class="form-control" name="password" data-parsley-required="true" data-parsley-minlength="6" data-parsley-error-message="Password is too short!" id="password" placeholder="New Password">
	  </div>
	  <div class="col-md-6 p-r-o">
	    <label>Confirm Password</label>
	    <input type="Password" class="form-control" data-parsley-required="true" data-parsley-equalto="#password" data-parsley-error-message="Passwords don't Match!" placeholder="Confirm Password">
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	  <p class="pull-left m-t-5"><a  href="javascript:void(0)" target="sign-in">Sign In?</a></p>
	    <button class="btn btn-primary pull-right" type="submit">Create account</button>
	  </div>
	  </div>
	</form>
	</div>
	<div class="forgot-password" <?php if($action != "forgot"){ ?> style="display: none;" <?php } ?>>
		<h5>Forgot password? don't worry, we'll <br>send your a reset link.</h5>
          <form class="text-left forgot-form" action="" method="post" data-parsley-validate="">
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Email address</label>
	    <input type="text" class="form-control" name="email" placeholder="Email address" required>
	    <input type="hidden" name="action" value="forgotpassword">
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	  <p class="pull-left m-t-5"><a href="javascript:void(0)" target="sign-in">Sign In?</a></p>
	    <button class="btn btn-primary pull-right" type="submit">Send Email</button>
	  </div>
	  </div>
	</form>
	</div>
	<div class="reset-password" <?php if($action != "reset"){ ?> style="display: none;" <?php } ?>>
		<h5>Enter your new password.</h5>
		<?php if(isset($msg['invalidToken'])) { ?>
			<div class="alert alert-danger alert-dismissable">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				Oops! Your token has expired. <a href="javascript:void(0)" class="underline" target="forgot-password">Send again!</a>
			</div>
		<?php } ?>
          <form class="text-left reset-form" action="" method="post"  data-parsley-validate="">
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>New Password</label>
	    <input type="Password" class="form-control" name="password" data-parsley-required="true" data-parsley-minlength="6" data-parsley-error-message="Password is too short!" id="new-password" placeholder="New Password">
	    <input type="hidden" name="action" value="passwordreset">
	    <input type="hidden" name="token" value="<?php if(isset($_GET['token'])){ echo $_GET['token']; } ?>">
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	    <label>Confirm Password</label>
	    <input type="Password" class="form-control" data-parsley-required="true" data-parsley-equalto="#new-password" data-parsley-error-message="Passwords don't Match!" placeholder="Confirm Password">
	  </div>
	  </div>
	  <div class="form-group">
	  <div class="col-md-12 p-lr-o">
	  <p class="pull-left m-t-5"><a href="javascript:void(0)" target="sign-in">Sign In?</a></p>
	    <button class="btn btn-primary pull-right" type="submit" name="reset">Reset password</button>
	  </div>
	  </div>
	</form>
	</div>
	<div class="m-t-5">
		<a class="btn btn-block btn-primary-ish m-t-50 sign-up-btn"  href="javascript:void(0)" target="sign-up">Create an account</a>
	</div>
	<div class="copyright">
		<p class="text-center"><?php echo date("Y"); ?> &copy; <?php echo $systemName; ?> | All Rights Reserved.</p>
	</div>
</div>

   <!-- scripts -->
   <script src="assets/js/jquery-3.2.1.min.js"></script>
   <script src="assets/js/parsley.min.js"></script>
   <script src="assets/libs/toastr/toastr.min.js"></script>
   <script src="assets/libs/sweetalert/sweetalert.min.js"></script>
   <script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>

   <!-- custom scripts -->
   <script src="assets/js/app.js"></script>
</body>
</html>