<?php
include('connect.php');
$create=$conn->prepare("CREATE table rating_info
                (
                    rating_info_id int not null AUTO_INCREMENT,
                    ideaId int,
                    adminId int,
                    qamId int,
                    qacId int,
                    staffId int,
                    rating_action varchar(10),
                    comment text,
                    PRIMARY KEY(rating_info_id),
                    FOREIGN KEY(ideaId) REFERENCES idea(ideaId),
                    FOREIGN KEY(adminId) REFERENCES admin(adminId),
                    FOREIGN KEY(qamId) REFERENCES qam(qamId),
                    FOREIGN KEY(qacId) REFERENCES qac(qacId),
                    FOREIGN KEY(staffId) REFERENCES staff(staffId)
                )
            ");
$create->execute();
?>