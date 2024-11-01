<?php
include('connect.php');

$selectdept=$conn->prepare("select department from department d");
$selectdept->execute();
$dataofdept=$selectdept->fetchAll();
foreach ($dataofdept as $dept) {
    $department=$dept['department'];
    // Find total idea count uploaded from Art Department
    $selectstmt=$conn->prepare("select count(*) from idea i, qac qc, department d
    where i.qacId=qc.qacId and qc.deptId=d.deptId and d.department='$department'");
    $selectstmt->execute();
    $countdata_art1=$selectstmt->fetch();

    $selectstmt=$conn->prepare("select count(*) from idea i, staff s, department d
    where i.staffId=s.staffId and s.deptId=d.deptId and d.department='$department'");
    $selectstmt->execute();
    $countdata_art2=$selectstmt->fetch();

    $total=$countdata_art1[0]+$countdata_art2[0];
    //
    echo $department."->".$total."<br>";
}

echo "<hr>";

?>