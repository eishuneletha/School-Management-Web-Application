<?php
include('../connect.php');
include('header.php');
$staffIdToUpdate = 0;

if (isset($_REQUEST['staffIdToUpdate'])) {
    $staffIdToUpdate = $_REQUEST['staffIdToUpdate'];
    $selectstmt = $conn->prepare("SELECT * from staff where staffId=?");
    $selectstmt->bindParam(1, $staffIdToUpdate);
    $selectstmt->execute();
    $dataofstaff= $selectstmt->fetch(PDO::FETCH_ASSOC);

    $staffName = $dataofstaff['staffName'];
    $staffPhone = $dataofstaff['staffPhone'];
    $staffEmail = $dataofstaff['staffEmail'];
    $staffOldPassword = $dataofstaff['staffPassword'];
    $staffAddress = $dataofstaff['staffAddress'];
} else {
    echo "<script>alert('Access Denied!')</script>";
    echo "<script>location='staff_manage.php'</script>";
}
if (isset($_POST['btnUpdateStaff'])) {
    if ($_POST['tfPass'] == "") {
        $updateStmt = $conn->prepare("UPDATE staff set staffName=:staffName, staffPhone=:staffPhone, staffEmail=:staffEmail,
                                        staffAddress=:staffAddress where staffId=:staffId");
        $updateStmt->bindParam(':staffName', $_POST['tfName']);
        $updateStmt->bindParam(':staffPhone', $_POST['tfPhone']);
        $updateStmt->bindParam(':staffEmail', $_POST['tfEmail']);
        $updateStmt->bindParam(':staffAddress', $_POST['tfAddress']);
        $updateStmt->bindParam(':staffId', $staffIdToUpdate);

        if ($updateStmt->execute()) {
            echo "<script>alert('Successfully updated!')</script>";
            echo "<script>location='staff_manage.php'</script>";
        } else {
            echo "<script>alert('Update error!')</script>";
        }
    } else {
        $POP=$_POST['tfOldPass'];


        if (password_verify($_POST['tfOldPass'], $staffOldPassword))

         {
            echo "$POP";
        echo " <---   ----> ";
        echo "$staffOldPassword";

            $updateStmt = $conn->prepare("UPDATE staff set staffName=:staffName, staffPhone=:staffPhone, staffEmail=:staffEmail,
                                        staffPassword=:staffPassword, staffAddress=:staffAddress where staffId=:staffId");
            $updateStmt->bindParam(':staffName', $_POST['tfName']);
            $updateStmt->bindParam(':staffPhone', $_POST['tfPhone']);
            $updateStmt->bindParam(':staffEmail', $_POST['tfEmail']);
            $hasedpass = password_hash($_POST['tfPass'], PASSWORD_DEFAULT);
            $updateStmt->bindParam(':staffPassword', $hasedpass);
            $updateStmt->bindParam(':staffAddress', $_POST['tfAddress']);
            $updateStmt->bindParam(':staffId', $staffIdToUpdate);

            if ($updateStmt->execute()) {
                echo "<script>alert('Successfully updated!')</script>";
                echo "<script>location='staff_manage.php'</script>";
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
            <a href="main.php" style="text-decoration: underline;">
                <h6>Back</h6>
            </a>
            <center>
                <h4>Update Staff Account</h4>
            </center>
            <form action="" method="post">
                <div class="form-group">
                    <label for="label-name1">Enter Staff Name:</label>
                    <input type="text" name="tfName" value="<?php echo $staffName; ?>" class="form-control" id="label-name" aria-describedby="nameHelp" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="label-email">Enter Staff Phone:</label>
                    <input type="number" name="tfPhone" value="<?php echo $staffPhone; ?>" class="form-control" id="label-email" aria-describedby="emailHelp" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <label for="label-password">Enter Staff Email:</label>
                    <input type="email" name="tfEmail" value="<?php echo $staffEmail; ?>" class="form-control" id="label-password" aria-describedby="passHelp" autocomplete="off" required>
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
                    <textarea class="form-control" name="tfAddress" id="exampleFormControlTextarea1" rows="3"><?php echo $staffAddress; ?></textarea>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" name="btnUpdateStaff" class="btn btn-primary">Create Account</button>
                </div>

            </form>
        </div>

    </div>
</div>
<br>
<?php
include('footer.php');
?>