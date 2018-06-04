<?php
include_once("../includes/global.php");

if (isset($_GET['key'])) { 
    $fp = fopen("chat.txt","w");
    $sharingKey = $_GET['key'];
    
    $sql = "SELECT chat.message, users.fname, users.lname, chat.time_ FROM chat LEFT JOIN users ON users.id = chat.sender WHERE chat.file = '$sharingKey'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) { 
          fputcsv($fp, $row);
      }
    }
    
    fclose($fp);

    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('chat.txt'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('chat.txt'));
    readfile('chat.txt');
    unlink('chat.txt');

}else{
  exit("This link is broken");
}


