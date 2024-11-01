<?php
include('../connect.php');
include('header.php');
$qacIdToUpdate = 0;
if (isset($_REQUEST['qacIdToUpdate'])) {
    $qacIdToUpdate = $_REQUEST['qacIdToUpdate'];
    $selectstmt = $conn->prepare("SELECT * from qac where qacId=?");
    $selectstmt->bindParam(1, $qacIdToUpdate);
    $selectstmt->execute();
    $dataofqac = $selectstmt->fetch(PDO::FETCH_ASSOC);

    $qacName = $dataofqac['qacName'];
    $qacPhone = $dataofqac['qacPhone'];
    $qacEmail = $dataofqac['qacEmail'];
    $qacOldPassword = $dataofqac['qacPassword'];
    $qacAddress = $dataofqac['qacAddress'];
} else {
    echo "<script>alert('Access Denied!')</script>";
    echo "<script>location='index.php'</script>";
}
if (isset($_POST['btnUpdateQac'])) {
    if ($_POST['tfPass'] == "") {
        $updateStmt = $conn->prepare("UPDATE qac set qacName=:qacName, qacPhone=:qacPhone, qacEmail=:qacEmail,
                                        qacAddress=:qacAddress where qacId=:qacId");
        $updateStmt->bindParam(':qacName', $_POST['tfName']);
        $updateStmt->bindParam(':qacPhone', $_POST['tfPhone']);
        $updateStmt->bindParam(':qacEmail', $_POST['tfEmail']);
        $updateStmt->bindParam(':qacAddress', $_POST['tfAddress']);
        $updateStmt->bindParam(':qacId', $qacIdToUpdate);

        if ($updateStmt->execute()) {
            echo "<script>alert('Successfully updated!')</script>";
            echo "<script>location='qac_main.php'</script>";
        } else {
            echo "<script>alert('Update error!')</script>";
        }
    } else {
        if (password_verify($_POST['tfOldPass'], $qacOldPassword)) {
            $updateStmt = $conn->prepare("UPDATE qac set qacName=:qacName, qacPhone=:qacPhone, qacEmail=:qacEmail,
                                        qacPassword=:qacPassword, qacAddress=:qacAddress where qacId=:qacId");
            $updateStmt->bindParam(':qacName', $_POST['tfName']);
            $updateStmt->bindParam(':qacPhone', $_POST['tfPhone']);
            $updateStmt->bindParam(':qacEmail', $_POST['tfEmail']);
            $hasedpass = password_hash($_POST['tfPass'], PASSWORD_DEFAULT);
            $updateStmt->bindParam(':qacPassword', $hasedpass);
            $updateStmt->bindParam(':qacAddress', $_POST['tfAddress']);
            $updateStmt->bindParam(':qacId', $qacIdToUpdate);

            if ($updateStmt->execute()) {
                echo "<script>alert('Successfully updated!')</script>";
                echo "<script>location='qac_main.php'</script>";
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
            <a href="qac_main.php" style="text-decoration: underline;">
                <h6>Back</h6>
            </a>
            <center>
                <h4>Update QAC Account</h4>
            </center>
            <form action="" method="post">
                <div class="form-group">
                    <label for="label-name1">Enter Coordinator Name:</label>
                    <input type="text" name="tfName" value="<?php echo $qacName; ?>" class="form-control" id="label-name" aria-describedby="nameHelp" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="label-email">Enter Coordinator Phone:</label>
                    <input type="number" name="tfPhone" value="<?php echo $qacPhone; ?>" class="form-control" id="label-email" aria-describedby="emailHelp" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="label-password">Enter Coordinator Email:</label>
                    <input type="email" name="tfEmail" value="<?php echo $qacEmail; ?>" class="form-control" id="label-password" aria-describedby="passHelp" autocomplete="off" required>
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
                    <textarea class="form-control" name="tfAddress" id="exampleFormControlTextarea1" rows="3"><?php echo $qacAddress; ?></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" name="btnUpdateQac" class="btn btn-primary">Update Account</button>
                </div>

            </form>
        </div>

    </div>
</div>
<br>
<?php
include('footer.php');
?>