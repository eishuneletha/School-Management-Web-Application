<?php
    include('../connect.php');
    if (isset($_REQUEST["qacIdToDelete"])) {
    $qacIdToDelete=$_REQUEST["qacIdToDelete"];
    $deletestmt=$conn->prepare("DELETE from qac where qacId=?");
    $deletestmt->bindParam(1,$qacIdToDelete);
    if ($deletestmt->execute()) {
    echo "<script>alert('Successfully deleted.')</script>";
    echo "<script>location='qac_main.php'</script>";
    }else{
    echo "<script>alert('delete failed!')</script>";
    }
    }
  
?>