<?php
include_once("../includes/global.php"); 


header("Content-type:application/pdf");

// It will be called downloaded.pdf
header("Content-Disposition:attachment;filename='downloaded.pdf'");

// The PDF source is in original.pdf
readfile("../uploads/files/doc59db4f55b98e9.pdf");

// if(isset($_REQUEST['f'])){
//     header('Content-Type: application/octet-stream');
//     header('Content-Disposition: attachment; filename='.$_REQUEST['f']);
//     if($_REQUEST['f'] == 'signed'){
//         // readfile('../uploads/files/'.$_REQUEST['f']); 
//         readfile('../uploads/files/doc59db4f55b98e9.pdf'); 
//     }else if($_REQUEST['f'] == 'unsigned'){
//         // readfile('../uploads/files/original/'.$_REQUEST['f']);
//         readfile('../uploads/files/doc59db4f55b98e9.pdf');
//     }else{
//         exit;
//     }
//     exit;
// }else{
//     exit;
// }
?>