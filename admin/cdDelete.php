<?php
  include '../connect.php';

  $cdId=$_GET['cdId'];

  $stmt=$conn->prepare("delete from closuredate where cdId=?");
  $stmt->bindValue(1, $cdId);
  $stmt->execute();

  echo "<script>location='manageClosureDate.php'</script>";
 ?>
