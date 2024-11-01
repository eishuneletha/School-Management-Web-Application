<?php
include('../connect.php');
include('header.php');
$cdId = 0;
if (isset($_REQUEST['cdId'])) {
    $cdId = $_REQUEST['cdId'];
    $selectstmt = $conn->prepare("SELECT * from closuredate where cdId =?");
    $selectstmt->bindParam(1, $cdId);
    $selectstmt->execute();
    $data = $selectstmt->fetch(PDO::FETCH_ASSOC);

    $academicYear = $data['academicYear'];
    $iclosureDate = $data['closureDate'];
    $fclosureDate = $data['finalClosureDate'];
} else {
    echo "<script>alert('Access Denied!')</script>";
    echo "<script>location='manageClosureDate.php'</script>";
}

if (isset($_POST['btnUpdate'])) {

    $updateStmt = $conn->prepare("UPDATE closuredate set academicYear=:academicYear, closureDate=:closureDate, finalClosureDate=:finalClosureDate
                                where cdId=:cdId");
    $updateStmt->bindParam(':academicYear', $_POST['tfAcademic']);
    $updateStmt->bindParam(':closureDate', $_POST['tfClosure']);
    $updateStmt->bindParam(':finalClosureDate', $_POST['tfFinal']);
    $updateStmt->bindParam(':cdId', $cdId);

    if ($updateStmt->execute()) {
        echo "<script>alert('Successfully updated!')</script>";
        echo "<script>location='manageClosureDate.php'</script>";
    } else {
        echo "<script>alert('Error occured when updating.')</script>";
    }

}
?>
<!-- Update form for closure dates -->

<div style="margin: auto; margin-bottom:80px; margin-top:50px;" class="col-6">
    <form method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">Academic Year</label>
            <input type="text" class="form-control" name="tfAcademic" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo $academicYear; ?>" required>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Closure Date</label>
            <input type="date" class="form-control" name="tfClosure" id="exampleInputPassword1" value="<?php echo $iclosureDate; ?>" required>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Final Closure Date</label>
            <input type="date" class="form-control" name="tfFinal" id="exampleInputPassword1" value="<?php echo $fclosureDate; ?>" required>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" name="btnUpdate">Update</button>
        </div>
    </form>
</div>
