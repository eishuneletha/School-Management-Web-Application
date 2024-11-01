<?php
include('../connect.php');
include "header.php";

// Deleting the existing closure date

if (isset($_GET["cdId"])) {
    $cdId = $_GET["cdId"];
    echo "<script>
    if(confirm('Are you sure to delete this closure date?')==true){
    location='cdDelete.php?cdId=$cdId';
    }else{
    location='manageClosureDate.php';
    }
    </script>";
}

?>

<script>
    $(document).ready(function() {
        $('#example').DataTable();
    });
</script>

<!-- Head Section -->

<section class="page-title" style="background-image: url('../images/bg/hat.png') !important; background-size: cover; background-repeat: no-repeat;">
    <div class="overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="block text-center">
                    <span class="text-white">Administration (Admin Panel)</span>
                    <h1 class="text-capitalize mb-5 text-lg">Closure Dates</h1>

                </div>
            </div>
        </div>
    </div>
</section>
<br>

<!-- Head Section -->

<!-- Closure Dates -->

<div class="container">
    <div class="row">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Academic Year</th>
                        <th>Initial Closure Dates</th>
                        <th>Final Closure Dates</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $selectstmt = $conn->prepare("SELECT * from closuredate");
                    $selectstmt->execute();
                    $datalist = $selectstmt->fetchAll(PDO::FETCH_ASSOC);
                    $count = 0;
                    foreach ($datalist as $data) {
                        $count++;
                        $cdId = $data['cdId'];
                        echo "<tr>";
                        echo "<td>$count</td>";
                        echo "<td>" . $data['academicYear'] . "</td>";
                        echo "<td>" . $data['closureDate'] . "</td>";
                        echo "<td>" . $data['finalClosureDate'] . "</td>";
                        echo "<td><a href='manageClosureDate.php?cdId=$cdId' class='actionlink'><i class='fas fa-trash'></i>&nbsp;&nbspDelete</a>
                            <br>
                            <a href='updateCD.php?cdId=$cdId' class='actionlink'><i class='fas fa-edit'></i>&nbsp;&nbspEdit</a></td>";
                        echo "</tr>";
                    }
                    ?>

                </tbody>
                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>Academic Year</th>
                        <th>Initial Closure Dates</th>
                        <th>Final Closure Dates</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<br><br>

<?php
include('footer.php');
?>
