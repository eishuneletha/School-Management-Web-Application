<?php
include('../connect.php');
$staffIdToDelete = 0;
if (isset($_REQUEST['staffIdToDelete'])) {
    $staffIdToDelete = $_REQUEST['staffIdToDelete'];
    $selectstmt = $conn->prepare("DELETE from staff where staffId=?");
    $selectstmt->bindParam(1, $staffIdToDelete);
    $selectstmt->execute();

    /*$dataofstaff= $selectstmt->fetch(PDO::FETCH_ASSOC);

    $staffName = $dataofstaff['staffName'];
    $staffPhone = $dataofstaff['staffPhone'];
    $staffEmail = $dataofstaff['staffEmail'];
    $staffOldPassword = $dataofstaff['staffPassword'];
    $staffAddress = $dataofstaff['staffAddress'];*/
    echo "<script>location='staff_manage.php'</script>";
} else {
    echo "<script>alert('Access Denied!')</script>";
    echo "<script>location='staff_manage.php'</script>";
}
/*if (isset($_POST['btnDeleteStaff'])) {
    if ($_POST['tfPass'] == "") {
        $deleteStmt = $conn->prepare("DELETE FROM `staff`   where staffId=:staffId");
        
        $updateStmt->bindParam(':staffId', $staffIdToDelete);

        if ($updateStmt->execute()) {
            echo "<script>alert('Successfully Deleted!')</script>";
            echo "<script>location='main.php'</script>";
        } else {
            echo "<script>alert('Delete error!')</script>";
        }
    } 
}*/
?>