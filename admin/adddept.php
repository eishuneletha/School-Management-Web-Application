<?php
include('../connect.php');
include('header.php');
if (isset($_POST['btnAddDept'])) {
    $insertdept="INSERT into department(department) values(:deptval)";
    $insertstmt=$conn->prepare($insertdept);
    $insertstmt->bindParam(":deptval",$_POST['tfDept']);
    if ($insertstmt->execute()) {
        
    }
}
if (isset($_GET['deptIdToDelete'])) {
    $deptIdToDelete=$_GET['deptIdToDelete'];
    $deletstmt=$conn->prepare("DELETE from department where deptId=:deptIdToDelete");
    $deletstmt->bindParam(":deptIdToDelete",$deptIdToDelete);
    $deletstmt->execute();
}
?>

<div style="height: auto; margin-top: 30px;">
    <div class="d-flex justify-content-center">
        <form class="form-inline col-lg-6 col-sm-12" method="post">
            <div class="form-group mx-sm-3 mb-2 col-lg-9 col-sm-10">
                <label for="inputPassword2" class="sr-only">New Department</label>
                <input type="text" class="form-control col-12" name="tfDept" placeholder="New Department" autocomplete="off" required>
            </div>
            <button type="submit" name="btnAddDept" class="btn btn-primary mb-2">Add</button>
        </form>
    </div>

    <div class="d-flex justify-content-center mt-3">
    <table class="table col-6">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Department</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $selectdeptstmt=$conn->prepare("SELECT * from department order by department asc");
            $selectdeptstmt->execute();
            $datalist=$selectdeptstmt->fetchAll(PDO::FETCH_ASSOC);
            if ($selectdeptstmt->rowCount()==0) {
                echo "<tr><td colspan='2' style='text-align:center;'>There's no data!</td></tr>";
            }else{
                $count=0;
                foreach ($datalist as $data) {
                    $count++;
                    $dept=$data['department'];
                    $deptId=$data['deptId'];
                    echo "
                    <tr>
                    <th>$count</th>
                    <td>$dept</td>
                    <td><a href='adddept.php?deptIdToDelete=$deptId'><i class='fas fa-trash-alt'></i></a>
                    </tr>
                    ";
                }
            }
            
            ?>
            <!-- <tr>
                <th scope="row">1</th>
                <td>Mark</td>
            </tr> -->
        </tbody>
    </table>
    </div>
</div>



<?php
include('footer.php');
?>