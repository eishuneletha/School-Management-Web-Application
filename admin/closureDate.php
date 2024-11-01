<?php
include('../connect.php');
include('header.php');
if (isset($_POST['btnSave'])) {
    $insert = $conn->prepare("INSERT into closuredate(academicYear,closureDate,finalClosureDate)
                            values(:ay,:cd,:fcd)");
    $closure = $_POST['tfClosure'];
    $finalclosure = $_POST['tfFinal'];
    $insert->bindParam(':ay', $_POST['tfAcademic']);
    $insert->bindParam(':cd', $closure);
    $insert->bindParam(':fcd', $finalclosure);

    $selectclosure = $conn->prepare("SELECT count(*) from closuredate where academicYear=:academicYear");
    $selectclosure->bindParam(':academicYear', $_POST['tfAcademic']);
    $selectclosure->execute();
    $dataofclosure = $selectclosure->fetch();
    $closureCount = $dataofclosure[0];
    if ($closureCount > 0) {
        echo "<script>alert('You have already inserted for this academic year!!!')</script>";
    } else {
        if ($insert->execute()) {
            echo "<script>alert('Successfully saved!')</script>";
        } else {
            echo "<script>alert('Failed to insert')</script>";
        }
    }
}
?>
<div style="margin: auto; margin-bottom:80px; margin-top:50px;" class="col-6">
    <form method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">Academic Year</label>
            <input type="text" class="form-control" name="tfAcademic" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="e.g 2022-2023" required>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Closure Date</label>
            <input type="date" class="form-control" name="tfClosure" id="exampleInputPassword1" required>
        </div>
        <div class="form-group">
            <label for="exampleInputPassword1">Final Closure Date</label>
            <input type="date" class="form-control" name="tfFinal" id="exampleInputPassword1" required>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" name="btnSave">Save</button>
        </div>
    </form>
</div>
<?php
include('footer.php');
?>