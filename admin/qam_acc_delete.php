<?php
  include '../connect.php';

  $id=$_GET['qamIdToDelete'];

  $stmt=$conn->prepare("delete from qam where qamId=?");
  $stmt->bindValue(1,$id);
  $stmt->execute();

  echo "<script>location='qam_manage.php'</script>";
 ?>
