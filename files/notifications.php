<?php
// Include Global files
include_once("../includes/global.php"); 
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
    <title>Notifications | Sign documents online</title>
    <!-- Ion icons -->
    <link href="assets/fonts/ionicons/css/ionicons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/libs/switchery/switchery.min.css" rel="stylesheet">
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
		<h3>Notifications</h3>
	</div>
	<div class="row">
		<!-- Notification start -->
		<div class="col-md-12 notifications-holder">

<?php
// signing requests notifications
$sql = "SELECT * FROM requests where email = '".$userEmail."' and status = '0'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) { ?>
			<div class="light-card notification-item unread">
				<div class="notification-item-image bg-warning btn-round">
					<span><i class="ion-ios-bell-outline"></i></span>
				</div>
				<span class="label label-warning">Important!</span>
				<p><strong><?php echo $row['sender']; ?></strong> has invited you to sign a <a href="<?php echo $siteUrl."/sign?key=".$row['file']."&signingkey=".$row['signingkey']; ?>"><span class="text-primary">document.</span></a>.</p>
			</div>
      <?php
  }
}
?>

<?php
// notifications
$sql = "SELECT * FROM notifications where user = '".$userId."' ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) { ?>
			<div class="light-card notification-item <?php if ($row['time_'] > $lastNotification) { ?> unread<?php } ?>" data-id="<?php echo $row['id']; ?>">
				<?php if ($row['type'] == 'accept') { ?>
				<div class="notification-item-image bg-success btn-round">
					<span><i class="ion-ios-checkmark"></i></span>
				</div>
				<?php }elseif($row['type'] == 'decline'){ ?>
				<div class="notification-item-image bg-danger btn-round">
					<span><i class="ion-ios-close"></i></span>
				</div>
				<?php }else{ ?>
				<div class="notification-item-image bg-warning btn-round">
					<span><i class="ion-ios-bell-outline"></i></span>
				</div>
				<?php } ?>
				<div class="pull-right">
					<span class="delete-notification"><i class="ion-close-round"></i></span>
				</div>
				<?php if ($row['time_'] > $lastNotification) { ?>
				<span class="label label-success">New!</span>
				<?php } ?>
				<p><?php echo $row['message']; ?></p>
			</div>
      <?php
  }
}else{ ?>
<div class="center-notify">
	<i class="ion-ios-information-outline"></i>
 	<h3>It's empty here!</h3>
</div>

<?php }
?>
		</div>
	</div>
</div>

<?php
	// update last notification time
	$sql = "UPDATE users SET lastnotification = '".date('Y-m-d H:i:s')."' WHERE id = $userId"; 
	$run = mysqli_query($conn,$sql);
?>

<!-- footer -->
<?php include_once("../includes/footer.php"); ?>

   <!-- scripts -->
   <script src="assets/js/jquery-3.2.1.min.js"></script>
   <script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/libs/switchery/switchery.min.js"></script>
   <script src="assets/libs/toastr/toastr.min.js"></script>
   <script src="assets/libs/sweetalert/sweetalert.min.js"></script>

   <!-- custom scripts -->
   <script src="assets/js/app.js"></script>
   <script>
        $(document).ready(function() {
            $(".bubble").hide()
        });
   </script>
</body>
</html>