<?php
include('connect.php');
include('header.php');
if (isset($_POST['btnAddCat'])) {
    $insertstmt=$conn->prepare("INSERT into category(category) values(:category)");
    $insertstmt->bindParam(':category',$_POST['tfCat']);
    $insertstmt->execute();
}
if (isset($_GET['catIdToDelete'])) {
    $catIdToDelete=$_GET['catIdToDelete'];
    $deletecatstmt=$conn->prepare("DELETE from category where categoryId=?");
    $deletecatstmt->bindParam(1,$catIdToDelete);
    $deletecatstmt->execute();
}
?>

<div style="height: auto; margin-top: 30px;">
    <div class="d-flex justify-content-center">
        <form class="form-inline col-lg-6 col-sm-12" method="post">
            <div class="form-group mx-sm-3 mb-2 col-lg-9 col-sm-10">
                <label for="inputPassword2" class="sr-only">New Category</label>
                <input type="text" class="form-control col-12" name="tfCat" placeholder="New Category" autocomplete="off" required>
            </div>
            <button type="submit" name="btnAddCat" class="btn btn-primary mb-2">Add</button>
        </form>
    </div>

    <div class="d-flex justify-content-center mt-3">
    <table class="table col-6">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Category</th>
                <th scope="col">Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $selectcatstmt=$conn->prepare("SELECT * from category order by category asc");
            $selectcatstmt->execute();
            $datalist=$selectcatstmt->fetchAll(PDO::FETCH_ASSOC);
            if ($selectcatstmt->rowCount()==0) {
                echo "<tr><td colspan='2' style='text-align:center;'>There's no data!</td></tr>";
            }else{
                $count=0;
                foreach ($datalist as $data) {
                    $count++;
                    $cat=$data['category'];
                    $catId=$data['categoryId'];
                    echo "
                    <tr>
                    <th>$count</th>
                    <td>$cat</td>
                    <td><a href='addcat.php?catIdToDelete=$catId'><i class='fas fa-trash-alt'></i></a>
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