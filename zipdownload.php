	
<?php

function myFunction(){
ob_start();
$zip = new ZipArchive; 
$zip->open('Documention of Universtiry.zip',  ZipArchive::CREATE);
$srcDir = "C:\\xampp\\htdocs\\EWSDProject\uploads"; 
$files= scandir($srcDir);
print_r($files); 

unset($files[0],$files[1]);
foreach ($files as $file) {
    $zip->addFile($srcDir.'\\'.$file, $file);
    echo "bhandari";
}
$zip->close();

$file='Documention of Universtiry.zip';
if (headers_sent()) {
    echo 'HTTP header already sent';
} else {
    if (!is_file($file)) {
        header($_SERVER['SERVER_PROTOCOL'].' 404 Not Found');
        echo 'File not found';
    } else if (!is_readable($file)) {
        header($_SERVER['SERVER_PROTOCOL'].' 403 Forbidden');
        echo 'File not readable';
    } else {
        header($_SERVER['SERVER_PROTOCOL'].' 200 OK');
        header("Content-Type: application/zip");
        header("Content-Transfer-Encoding: Binary");
        header("Content-Length: ".filesize($file));
        header("Content-Disposition: attachment; filename=\"".basename($file)."\"");
        while (ob_get_level()) {
            ob_end_clean();
          }
        readfile($file);
        exit;
    }
    header('content-type:application/octet-stream');
				header("content-disposition: attchment; filename-$zip_name");
				readfile($zip_name);
}

}

?>
			
				

<?php 
include('connect.php');
$sth = $conn->prepare("SELECT closureDate FROM closuredate");
$sth->execute();

$checkcount=$sth->rowCount();

  $info = array();
    if($checkcount>0){
      while ($fetch=$sth->fetch(PDO::FETCH_ASSOC)) {
      $info[]= $fetch;
       $c_date=implode("",$info[0]);
       

       $clousure_date = strtotime($c_date);
       $nowdate = strtotime(date("Y/m/d"));

     

    if($nowdate > $clousure_date || $nowdate == $clousure_date){
  
          myFunction();

      } 
      else{

        echo "<script>window.alert('Please check your Date and Closure Date')</script>";
        echo "<script>location='UserDashboard.php'</script>";
    }
  }
}
  
    ?>

