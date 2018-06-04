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
    <title>Documents | Sign documents online</title>
    <!-- Ion icons -->
    <link href="assets/fonts/ionicons/css/ionicons.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/libs/dropify/css/dropify.css" rel="stylesheet">
    <link href="pad/css/dd_signature_pad.css" rel="stylesheet">
    <!-- Signer CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Berkshire+Swash|Dr+Sugiyama|Great+Vibes|League+Script|Meie+Script|Miss+Fajardose|Niconne|Petit+Formal+Script|Rochester|Sacramento|Tangerine" rel="stylesheet">
    <style>

    </style>
</head>
<body>

<!-- header start -->
<?php include_once("../includes/header.php"); ?>

<!-- leftbar -->
<?php include_once("../includes/leftbar.php"); ?>

<div class="content">
	<div class="page-title">
		<h3>Signature</h3>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="light-card signature-card text-center">
			<h2>This is your signature, you can <br>update it anytime.</h2>
			<div class="signature-holder">
				<img src="uploads/signatures/<?php echo $signature; ?>" class="img-responsive">
			</div>
			<div class="signature-btn-holder">
				<button class="btn btn-primary-ish btn-block"  data-toggle="modal" data-target="#updateSignature"> <i class="ion-edit"></i> Update Signature</button>
			</div>
			</div>
		</div>

	</div>
</div>

<!-- footer -->
<?php include_once("../includes/footer.php"); ?>


  <!-- Upload file Modal -->
  <div class="modal fade" id="updateSignature" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
      <ul class="head-links">
      	<li class="active"><a data-toggle="tab" href="#text">Text</a></li>
      	<li><a data-toggle="tab" href="#upload">Upload</a></li>
      	<li><a data-toggle="tab" href="#draw">Draw</a></li>
      </ul>
        <div class="modal-body">
		<div class="tab-content">
			<div id="text" class="tab-pane fade in active">
          		      <form>
                          <div class="form-group">
                            <div class="col-md-6">
                              <label>Type your signature</label>
                              <input type="text" class="form-control signature-input" name="" placeholder="Type your signature" maxlength="10" value="Your Name">
                            </div>
                            <div class="col-md-6">
                              <label>Select font</label>
                              <select class="form-control signature-font" name="">
                                  <option value="Lato">Lato</option>
                                  <option value="Miss Fajardose">Miss Fajardose</option>
                                  <option value="Meie Script">Meie Script</option>
                                  <option value="Petit Formal Script">Petit Formal Script</option>
                                  <option value="Niconne">Niconne</option>
                                  <option value="Rochester">Rochester</option>
                                  <option value="Tangerine">Tangerine</option>
                                  <option value="Great Vibes">Great Vibes</option>
                                  <option value="Berkshire Swash">Berkshire Swash</option>
                                  <option value="Sacramento">Sacramento</option>
                                  <option value="Dr Sugiyama">Dr Sugiyama</option>
                                  <option value="League Script">League Script</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-md-4">
                              <label>Weight</label>
                              <select class="form-control signature-weight" name="">
                                  <option value="normal">Regular</option>
                                  <option value="bold">Bold</option>
                                  <option value="bolder">Bolder</option>
                                  <option value="lighter">Lighter</option>
                              </select>
                            </div>
                            <div class="col-md-4">
                              <label>Color</label>
                              <input type="color" class="form-control signature-color" name="" placeholder="Color">
                            </div>
                            <div class="col-md-4">
                              <label>Style</label>
                              <select class="form-control signature-style" name="">
                                  <option value="normal">Regular</option>
                                  <option value="italic">Italic</option>
                              </select>
                            </div>
                          </div>
                      </form>
                      <div class="divider"></div>
                      <h4 class="text-center">Preview</h4>
                      <div class="text-signature-preview">
                          <div class="text-signature" id="text-signature">Your Name</div>
                      </div>

			</div>
			<div id="upload" class="tab-pane fade">
          			<p>Only PNG 400x150px images allowed. </p>
		          <form class="image-signature-form" action="files/ajaxProcesses.php" method="post"  enctype="multipart/form-data" data-parsley-validate="">
			  <div class="form-group">
			  <div class="col-md-12 p-lr-o">
			    <label>Upload your signature</label>
			    <input type="file" name="imgdata" class="dropify image-signature" data-min-width="399.9"  data-min-height="149.9" data-max-width="400.1"  data-max-height="150.1" data-allowed-file-extensions="jpg png" data-parsley-required="true">
                      <input type="hidden" name="action" value="saveImageSignature">
			  </div>
			  </div>
			</form>
			</div>
			<div id="draw" class="tab-pane fade">
          			<p>Draw your signature.</p>
                          <div id="signatureSet">
                              <div id="dd_signaturePadWrapper"></div>
                        </div>
			</div>
		</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary save-signature-text">Save Signature</button>
        </div>
      </div>
      
    </div>
  </div>

  <div class="loading-overlay"><i class="ion-loading-c"></i></div>

   <!-- scripts -->
   <script src="assets/js/jquery-3.2.1.min.js"></script>
   <script src="assets/libs/bootstrap/js/bootstrap.min.js"></script>
   <script src="assets/libs/dropify/js/dropify.min.js"></script>
   <script src="assets/js/parsley.min.js"></script>
   <script src="assets/libs/toastr/toastr.min.js"></script>
   <script src="assets/libs/html2canvas/html2canvas.js"></script>
   <script src="pad/js/dd_signature_pad.js"></script>

   <!-- custom scripts -->
   <script src="assets/js/app.js"></script>
   <script type="text/javascript">
   	// Dropify
	$('.dropify').dropify(   { error: {
        'minWidth': 'Signature should be 400px * 150px.',
        'maxWidth': 'Signature should be 400px * 150px.',
        'minHeight': 'Signature should be 400px * 150px.',
        'maxHeight': 'Signature should be 400px * 150px.',
    }});

   </script>
</body>
</html>