<?php
include('connect.php');
$dropstmt=$conn->prepare("DROP table staff");
$dropstmt->execute();

$createstmt=$conn->prepare(
    "CREATE table staff
    (
        staffId int not null AUTO_INCREMENT,
        staffName varchar(50),
        staffPhone varchar(20),
        staffEmail varchar(100),
        staffPassword text,
        staffAddress text,
        status boolean,
        deptId int,
        PRIMARY KEY(staffId),
        FOREIGN KEY(deptId) REFERENCES department(deptId)
    )"
);
$createstmt->execute();

?>