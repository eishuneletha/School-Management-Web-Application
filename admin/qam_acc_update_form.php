<?php
include('../connect.php');
include('header.php');
$qamIdToUpdate = 0;
if (isset($_REQUEST['qamIdToUpdate'])) {
    $qamIdToUpdate = $_REQUEST['qamIdToUpdate'];
    $selectstmt = $conn->prepare("SELECT * from qam where qamId=?");
    $selectstmt->bindParam(1, $qamIdToUpdate);
    $selectstmt->execute();
    $dataofqam = $selectstmt->fetch(PDO::FETCH_ASSOC);

    $qamName = $dataofqam['qamName'];
    $qamPhone = $dataofqam['qamPhone'];
    $qamEmail = $dataofqam['qamEmail'];
    $qamOldPassword = $dataofqam['qamPassword'];
    $qamAddress = $dataofqam['qamAddress'];
} else {
    echo "<script>alert('Access Denied!')</script>";
    echo "<script>location='qam_manage.php'</script>";
}
if (isset($_POST['btnUpdateqam'])) {
    if ($_POST['tfPass'] == "") {
        $updateStmt = $conn->prepare("UPDATE qam set qamName=:qamName, qamPhone=:qamPhone, qamEmail=:qamEmail,
                                        qamAddress=:qamAddress where qamId=:qamId");
        $updateStmt->bindParam(':qamName', $_POST['tfName']);
        $updateStmt->bindParam(':qamPhone', $_POST['tfPhone']);
        $updateStmt->bindParam(':qamEmail', $_POST['tfEmail']);
        $updateStmt->bindParam(':qamAddress', $_POST['tfAddress']);
        $updateStmt->bindParam(':qamId', $qamIdToUpdate);

        if ($updateStmt->execute()) {
            echo "<script>alert('Successfully updated!')</script>";
            echo "<script>location='qam_manage.php'</script>";
        } else {
            echo "<script>alert('Update error!')</script>";
        }
    } else {
        if (password_verify($_POST['tfOldPass'], $qamOldPassword)) {
            $updateStmt = $conn->prepare("UPDATE qam set qamName=:qamName, qamPhone=:qamPhone, qamEmail=:qamEmail,
                                        qamPassword=:qamPassword, qamAddress=:qamAddress where qamId=:qamId");
            $updateStmt->bindParam(':qamName', $_POST['tfName']);
            $updateStmt->bindParam(':qamPhone', $_POST['tfPhone']);
            $updateStmt->bindParam(':qamEmail', $_POST['tfEmail']);
            $hasedpass = password_hash($_POST['tfPass'], PASSWORD_DEFAULT);
            $updateStmt->bindParam(':qamPassword', $hasedpass);
            $updateStmt->bindParam(':qamAddress', $_POST['tfAddress']);
            $updateStmt->bindParam(':qamId', $qamIdToUpdate);

            if ($updateStmt->execute()) {
                echo "<script>alert('Successfully updated!')</script>";
                echo "<script>location='qam_manage.php'</script>";
            } else {
                echo "<script>alert('Update error!')</script>";
            }
        } else {
            echo "<script>alert('Wrong Old Password!')</script>";
            echo "<script>document.getElementById('label-old-password').focus();</script>";
        }
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-2">

        </div>
        <div class="col-8">
            <a href="index.php" style="text-decoration: underline;">
                <h6>Back</h6>
            </a>
            <center>
                <h4>Update qam Account</h4>
            </center>
            <form action="" method="post">
                <div class="form-group">
                    <label for="label-name1">Enter Coordinator Name:</label>
                    <input type="text" name="tfName" value="<?php echo $qamName; ?>" class="form-control" id="label-name" aria-describedby="nameHelp" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="label-email">Enter Coordinator Phone:</label>
                    <input type="number" name="tfPhone" value="<?php echo $qamPhone; ?>" class="form-control" id="label-email" aria-describedby="emailHelp" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="label-password">Enter Coordinator Email:</label>
                    <input type="email" name="tfEmail" value="<?php echo $qamEmail; ?>" class="form-control" id="label-password" aria-describedby="passHelp" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="label-old-password">Enter Old Password:</label>
                    <input type="password" name="tfOldPass" class="form-control" id="label-old-password" aria-describedby="passHelp" autocomplete="off">
                    <small id="passHelp" class="form-text text-muted">If you don't remember old password, you won't able to change the password.</small>
                </div>
                <div class="form-group">
                    <label for="label-password">Create a new Password:</label>
                    <input type="password" name="tfPass" class="form-control" id="label-password" aria-describedby="passHelp" autocomplete="off">
                    <small id="passHelp" class="form-text text-muted">Must be 8 characters long</small>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1">Address (Optional)</label>
                    <textarea class="form-control" name="tfAddress" id="exampleFormControlTextarea1" rows="3"><?php echo $qamAddress; ?></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" name="btnUpdateqam" class="btn btn-primary">Update Account</button>
                </div>

            </form>
        </div>

    </div>
</div>
<br>
<?php
include('footer.php');
?>
