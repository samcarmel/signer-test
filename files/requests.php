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
    <title>Signing requests | Sign documents online</title>
    
    <!--datatables-->
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
		<h3>Signing requests</h3>
		<p>All pending, complete and declined signing requests</p>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="light-card table-responsive p-b-3em">
				<table id="signer-datatable" class="table display companies-list">
					<thead>
						<tr>						
							<th></th>
							<th class="text-center w-70"></th>
							<th>Name</th>
							<th>File name</th>
							<th>Date</th>
							<th class="text-center">Status</th>
							<th class="text-center w-70">Action</th>
						</tr>
					</thead>
					<tbody>
<?php 
$i = 1;
$avatar = "avatar.png";
$sql = "SELECT * FROM requests where company = ".$companyId." ORDER BY id DESC";
$companyResult = $conn->query($sql);
if ($companyResult->num_rows > 0) {
	while($request = $companyResult->fetch_object()) {

		// sender name
		$sqlSender = "SELECT fname, lname, avatar FROM users where company = ".$companyId;
		$senderResult = $conn->query($sqlSender);
        if ($senderResult->num_rows > 0) {
		    $sender = $senderResult->fetch_object();
		    $senderName = $sender->fname." ".$sender->lname;
        }else{
            $senderName = $request->sender;
        }
        
		// receiver name
		$receiverSql = "SELECT fname, lname, avatar FROM users where email = '".$request->email."'";
		$receiverResult = $conn->query($receiverSql);
        if ($receiverResult->num_rows > 0) {
		    $receiver = $receiverResult->fetch_object();
		    $receiverName = $receiver->fname." ".$receiver->lname;

            if (!empty($receiver->avatar)) {
                $avatar = $receiver->avatar;
            }
        }else{
            $receiverName = $request->email;
        }
        
		// file name
		$fileSql = "SELECT name FROM files where sharing_key = '".$request->file."'";
		$fileResult = $conn->query($fileSql);
		$file = $fileResult->fetch_object();

?>

						<tr data-id="<?=$request->id;?>">
							<td class="text-center"><?=$i;?></td>
							<td class="text-center"><img src="uploads/avatar/<?php echo $avatar; ?>" class="img-circle request-avatar"></td>
							<td><strong><?=$receiverName;?></strong><br>Sent by:<?=$senderName?></td>
							<td><?=$file->name;?></td>
							<td><strong>Requested: </strong><?=date("F j, Y", strtotime($request->time_));?><br>
							    <?php if($request->status == 1){ ?>
							        <strong>Signed: </strong><?=date("F j, Y", strtotime($request->actiontime_));?>
							    <?php } else if ($request->status == 2) { ?>
							        <strong>Declined: </strong><?=date("F j, Y", strtotime($request->actiontime_));?>
							    <?php } else if ($request->status == 3) { ?>
							        <strong>Cancelled: </strong><?=date("F j, Y", strtotime($request->actiontime_));?>
							    <?php } ?>
							</td>
							<td class="text-center status">
							    <?php if($request->status == 1){ ?>
							        <span class="label label-success">Signed</span>
							    <?php } else if ($request->status == 2) { ?>
							        <span class="label label-danger">Declined</span>
							    <?php } else if ($request->status == 3) { ?>
							        <span class="label label-warning">Cancelled</span>
							    <?php } else { ?>
							        <span class="label label-info">pending</span>
							    <?php } ?>
							</td>
							
							<td class="text-center">
								<div class="dropdown">
									<span class="company-action dropdown-toggle"  data-toggle="dropdown"><i class="ion-ios-more"></i></span>
								    <ul class="dropdown-menu" role="menu">
        							    <?php if($request->status == 0){ ?>
								      <li role="presentation" class="hide-on-cancel">
								      		<a class="cancel-request" href="">Cancel</a>
								      </li>
								      <li role="presentation" class="hide-on-cancel">
								      		<a class="remind-request" href="">Remind</a>
								      </li>
        							    <?php } ?>
								      <li role="presentation">
								      		<a class="delete-request" href="">Delete</a>
								      </li>
								    </ul>
							  </div>
							</td>
						</tr>
<?php 
$i++;
	}
}else{ ?>
<tr class="center-notify">
	<td colspan="7">
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

  <!-- remind modal -->
  <div class="modal fade" id="remind" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Send reminder</h4>
        </div>
          <form class="remind-form">
            <div class="modal-body">
        	  <div class="form-group">
        	  <div class="col-md-12 p-lr-o">
        	    <label>Message to receiver</label>
        	    <input type="hidden" name="action" value="remindrequest">
        	    <input type="hidden" name="requestId">
<textarea class="form-control" name="message" rows="8" required>Hello there,

I hope you are doing well.
I am writing to remind you about the signing request I had sent earlier.

Cheers!
<?=$fullName;?>
</textarea>
        	  </div>
        	  </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Send Reminder</button>
            </div>
	</form>
      </div>
      
    </div>
  </div>

<!-- footer -->
<?php include_once("../includes/footer.php"); ?>
    <div class="loading-overlay"><i class="ion-loading-c"></i></div>

    <!-- scripts -->
    <script src="//code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
    <!--datatables-->
    <script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
    <script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>
    <!--libraries-->
   <script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/libs/switchery/switchery.min.js"></script>
   <script src="assets/libs/toastr/toastr.min.js"></script>
   <script src="assets/js/parsley.min.js"></script>
   <script src="assets/libs/sweetalert/sweetalert.min.js"></script>
   
   <!-- custom scripts -->
   <script src="assets/js/app.js"></script>
   <script>
        $(document).ready(function() {
        	$('#signer-datatable').DataTable( {
        		dom: 'Bfrtip',
        		buttons: [
        			'copyHtml5',
        			'excelHtml5',
        			'csvHtml5'
        		]
        	} );
        } );
    </script>
</body>
</html>