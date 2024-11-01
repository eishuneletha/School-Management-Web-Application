<?php
include('connect.php');
$dropstmt=$conn->prepare("DROP table comment");
$dropstmt->execute();

$createstmt=$conn->prepare(
    "CREATE table comment
    (
        commentId int not null AUTO_INCREMENT,
        comment text,
        ideaId int,
        adminId int,
        qamId int,
        qacId int,
        staffId int,
        PRIMARY KEY(commentId),
        FOREIGN KEY(ideaId) REFERENCES idea(ideaId),
        FOREIGN KEY(adminId) REFERENCES admin(adminId),
        FOREIGN KEY(qamId) REFERENCES qam(qamId),
        FOREIGN KEY(qacId) REFERENCES qac(qacId),
        FOREIGN KEY(staffId) REFERENCES staff(staffId)
    )"
);
$createstmt->execute();

?>