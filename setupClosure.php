<?php
include('connect.php');
$create=$conn->prepare(
    "CREATE table closureDate
    (
    cdId int not null AUTO_INCREMENT,
    academicYear varchar(50) UNIQUE,
    closureDate date,
    finalClosureDate date,
    PRIMARY KEY(cdId)
    )
     ");
if($create->execute()){
    echo "success";
}else{
    echo "fail";
}
?>