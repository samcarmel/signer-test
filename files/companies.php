<?php
// Include Global files
include_once("../includes/global.php"); 

if($role == 'admin' or $role == 'staff'){
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
    <title>Companies | Sign documents online</title>
    
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" />
    
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
		<h3>Companies</h3>
		<p>These are companies that have created an account via signup page</p>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="light-card table-responsive p-b-3em">
				<table class="table display companies-list" id ="example">
					<thead>
						<tr>						
							<th class="text-center w-70"></th>
							<th>Company</th>
							<th>Admin</th>
							<th class="text-center">Folders</th>
							<th class="text-center">Files</th>
							<th class="text-center">Staff</th>
							<th class="text-center w-70"></th>
						</tr>
					</thead>
					<tbody>
<?php 
$backgroundColors = array("bg-danger","bg-success","bg-warning","bg-purple");
$sql = "SELECT * FROM companies where id > 1 ORDER BY id DESC";
$companyResult = $conn->query($sql);
if ($companyResult->num_rows > 0) {
	while($row = $companyResult->fetch_assoc()) {

		// count files 
		$sql = "SELECT id FROM files where company = ".$row['id'];
		$result = $conn->query($sql);
		$files = $result->num_rows;

		// count folders 
		$sql = "SELECT id FROM folders where company = ".$row['id'];
		$result = $conn->query($sql);
		$folders = $result->num_rows;

		// count staff 
		$sql = "SELECT id FROM users where company = ".$row['id']." and role = 'staff'";
		$result = $conn->query($sql);
		$staff = $result->num_rows;

		// admin
		$sql = "SELECT fname, lname, email FROM users where company = ".$row['id']." and role = 'admin'";
		$result = $conn->query($sql);
		$owner = $result->fetch_assoc();


?>

						<tr data-id="<?php echo $row['id']; ?>">
							<td class="text-center"><div class="<?php echo $backgroundColors[array_rand($backgroundColors)];; ?> campany-icon"><?php echo mb_substr($row['name'], 0, 1, 'utf-8'); ?></div></td>
							<td><strong><?php echo $row['name']; ?></strong><br><?php echo $row['email']; ?></td>
							<td><strong><?php echo $owner['fname']." ".$owner['lname']; ?></strong><br><?php echo $owner['email']; ?></td>
							<td class="text-center"><?php echo $folders; ?></td>
							<td class="text-center"><?php echo $files; ?></td>
							<td class="text-center"><?php echo $staff; ?></td>
							<td class="text-center">
								<div class="dropdown">
									<span class="company-action dropdown-toggle"  data-toggle="dropdown"><i class="ion-ios-more"></i></span>
								    <ul class="dropdown-menu" role="menu">
								      <li role="presentation">
								      		<a class="delete-company" href="">Delete</a>
								      </li>
								    </ul>
							  </div>
							</td>
						</tr>
<?php 
	}
}else{ ?>
<tr class="center-notify">
	<td colspan="6">
	<i class="ion-ios-information-outline"></i>
 	<h3>It's empty here!</h3>
	</td>
</tr>

<?php }
?>
					</tbody>
				</table>
			</div>
		</div>

	</div>
</div>

<!-- footer -->
<?php include_once("../includes/footer.php"); ?>

    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    
    <script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>
    
<script>
                $(document).ready(function() {
                	$('#example').DataTable( {
                		dom: 'Bfrtip',
                		buttons: [
                			'copyHtml5',
                			'excelHtml5',
                			'csvHtml5'
                // 			'pdfHtml5'
                		]
                	} );
                } );
</script>


   <!-- scripts -->
   
   <script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/libs/switchery/switchery.min.js"></script>
   <script src="assets/libs/toastr/toastr.min.js"></script>
   <script src="assets/js/parsley.min.js"></script>
   <script src="assets/libs/sweetalert/sweetalert.min.js"></script>

   <!-- custom scripts -->
   <script src="assets/js/app.js"></script>
</body>
</html>