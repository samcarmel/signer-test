<?php
session_start();

// Include Global files
include_once("../config.php"); 

if (isset($_GET['key']) and $_GET['key'] != "") {
  $sharingKey = $_GET['key'];
  
  $sql = "SELECT * FROM files where sharing_key = '{$sharingKey}'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $file = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $siteUrl; ?>/assets/images/<?php echo $systemFavicon; ?>">
    <title><?php echo $file['name']; ?> | Sign documents online</title>
    <!-- Ion icons -->
    <link href="<?php echo $siteUrl; ?>/assets/fonts/ionicons/css/ionicons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="<?php echo $siteUrl; ?>/assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="<?php echo $siteUrl; ?>/assets/libs/switchery/switchery.min.css" rel="stylesheet">
    <link href="<?php echo $siteUrl; ?>/assets/libs/sweetalert/sweetalert.css" rel="stylesheet">
    <link href="<?php echo $siteUrl; ?>/assets/libs/toastr/toastr.min.css" rel="stylesheet">
    <link href="<?php echo $siteUrl; ?>/assets/libs/jquery-ui/jquery-ui.min.css" rel="stylesheet">
    <!-- Signer CSS -->
    <link href="<?php echo $siteUrl; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body class="signing-page full-footer">

<!-- header start -->
<header>
  <!-- Hambager -->
  <div class="humbager">
    <i class="ion-navicon-round"></i>
  </div>
  <!-- logo -->
  <div class="logo">
    <a href="<?php echo $siteUrl; ?>">
      <img src="<?php echo $siteUrl; ?>/assets/images/<?php echo $systemLogo; ?>" class="img-responsive">
    </a>
  </div>


  <!-- top right -->
  <ul class="nav header-links pull-right">
    <li>
      <?php
      if(isset($_SESSION["userId"]) and isset($_SESSION["companyId"]) and isset($_SESSION["role"])){ ?>
            <a href="<?php echo $siteUrl; ?>/dashboard" class="btn btn-primary-ish">Go to Dashboard</a>
      <?php }else{ ?>
            <a href="<?php echo $siteUrl; ?>/login" class="btn btn-primary-ish">Sign In</a>
      <?php } ?>
    </li>
    <li>
      <a href="<?php echo $siteUrl; ?>/uploads/files/<?php echo $file['filename']; ?>" class="btn btn-primary" download>Download</a>
    </li>
  </ul>
</header>


<div class="content">
	<div class="page-title">
		<h3><?php echo $file['name']; ?></h3>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="light-card document">

<div class="document-pagination">
	<div class="pull-left">
		  <button id="prev" class="btn btn-primary btn-round"><i class="ion-ios-arrow-left"></i></button>
  		<button id="next" class="btn btn-primary btn-round"><i class="ion-ios-arrow-right"></i></button>
	</div>
	<div class="pull-right">
		<span>Page: <span id="page_num"></span> / <span id="page_count"></span></span>
	</div>
</div>
<div class="document-load">
	<i class="ion-loading-c"></i>
</div>
<div class="text-center">
				<div class="document-map"></div>
				<canvas id="the-canvas"></canvas></div>
			</div>
		</div>


	</div>
</div>

<!-- footer -->
<?php include_once("../includes/footer.php"); ?> 

   <!-- scripts -->
   <script type="text/javascript">
    var url = '<?php echo $siteUrl; ?>/uploads/files/<?php echo $file['filename']; ?>';
   </script>
   <script src="<?php echo $siteUrl; ?>/assets/js/jquery-3.2.1.min.js"></script>
   <script src="<?php echo $siteUrl; ?>/assets/libs/bootstrap/js/bootstrap.min.js"></script>
   <script src="<?php echo $siteUrl; ?>/assets/libs/switchery/switchery.min.js"></script>
   <script src="<?php echo $siteUrl; ?>/assets/libs/toastr/toastr.min.js"></script>
   <script src="<?php echo $siteUrl; ?>/assets/libs/clipboard/clipboard.min.js"></script>
   <script src="<?php echo $siteUrl; ?>/assets/libs/sweetalert/sweetalert.min.js"></script>
   <script src="<?php echo $siteUrl; ?>/assets/js/undo.js"></script>
   <script src="<?php echo $siteUrl; ?>/assets/libs/jquery-ui/jquery-ui.min.js"></script>
   <!-- <script src="<?php echo $siteUrl; ?>/assets/js/pdf.js"></script> -->
   <script src="//mozilla.github.io/pdf.js/build/pdf.js"></script>

   <!-- custom scripts -->
   <script src="<?php echo $siteUrl; ?>/assets/js/app.js"></script>
   <script src="<?php echo $siteUrl; ?>/assets/js/signer.js"></script>
   <script src="<?php echo $siteUrl; ?>/assets/js/pdf-render.js"></script>
</body>
</html>

<?php
  }else{
    echo "This file nologer exists.";
    exit();
  }
}else{
	echo "This link is broken.";
	exit();
}

?>
