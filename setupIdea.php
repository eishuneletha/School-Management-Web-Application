<?php
include('connect.php');
$dropstmt=$conn->prepare("DROP table idea");
$dropstmt->execute();

$createstmt=$conn->prepare(
                    "CREATE table idea
                    (
                        ideaId int not null AUTO_INCREMENT,
                        qamId int,
                        qacId int,
                        staffId int,
                        adminId int,    
                        categoryId int,
                        idea text,
                        document text,
                        anonymousStatus boolean,
                        uploadDate date,
                        uploadTime time,
                        thumbUp int,
                        thumbDown int,
                        PRIMARY KEY(ideaId),
                        FOREIGN KEY(qamId) REFERENCES qam(qamId),
                        FOREIGN KEY(qacId) REFERENCES qac(qacId),
                        FOREIGN KEY(staffId) REFERENCES staff(staffId),
                        FOREIGN KEY(adminId) REFERENCES admin(adminId),
                        FOREIGN KEY(categoryId) REFERENCES category(categoryId)
                    )"
                );

if ($createstmt->execute()) {
    echo "success";
}else{
    echo "fail";
}


?>
