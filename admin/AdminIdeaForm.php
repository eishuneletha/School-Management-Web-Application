<?php
error_reporting(0);
include('../connect.php');
include('header.php');
// session_start();


if (isset($_REQUEST['submit'])) {


  // $qamid=$_REQUEST['qamid'];
  // $qacid=$_REQUEST['qacid'];
  // $sid=$_REQUEST['sid'];

  $aid = $_REQUEST['aid'];
  $cid = $_REQUEST['category'];
  $idea = $_REQUEST['idea'];
  $date = $_REQUEST['date'];
  $time = $_REQUEST['time'];


  $year = date('Y');
  $selectclosure = $conn->prepare("SELECT closureDate from closuredate where academicYear=$year");
  $selectclosure->execute();
  $dataofclosure = $selectclosure->fetch(PDO::FETCH_ASSOC);
  $closureDate = $dataofclosure['closureDate'];
  if ($closureDate == "") {
    echo "<script>window.alert('Closure Date has not been set yet!')</script>";
  } else {
    if ($date > $closureDate) {
      //name of uploaded file
      $filename = $_FILES['ideafile']['name'];
      // $filename=!empty ($fn) ? "$fn":"NULL";

      //counting files
      // $countfiles=count($filename);

      if ($filename == "") {
        $sql = "INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values (:qamid,:qacid,:staffId,:aid,:categoryid,:idea,:status,:date,:time,:up,:down)";


        $insertstmt = $conn->prepare($sql);

        if ($_POST['anonymous'] == "yes") {
          $status = 1;
        } else {
          $status = 0;
        }
        $up = 0;
        $down = 0;
        $qmid = null;
        $qcid = null;
        $staffid = null;

        $insertstmt->bindParam(":qamid", $qmid);
        $insertstmt->bindParam(":qacid", $qcid);
        $insertstmt->bindParam(":staffId", $staffid);

        $insertstmt->bindParam(":aid", $aid);
        $insertstmt->bindParam(":categoryid", $cid);
        $insertstmt->bindParam(":idea", $idea);
        $insertstmt->bindParam(":status", $status);
        $insertstmt->bindParam(":date", $date);
        $insertstmt->bindParam(":time", $time);
        $insertstmt->bindParam(":up", $up);
        $insertstmt->bindParam(":down", $down);

        $insertstmt->execute();
        echo "<script>window.alert('Idea uploaded successfully')</script>";
      } else {
        //destination of the file on the server
        $destination = "../Uploads/" . $filename;

        //get the file extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);

        //the physical file on a temporary uploads directory on the server
        $file = $_FILES['ideafile']['tmp_name'];
        $size = $_FILES['ideafile']['size'];

        //checking whether the file already exists
        $check = "Select document from idea where document=:filename";
        $checkstmt = $conn->prepare($check);
        $checkstmt->bindParam(":filename", $filename);
        $checkstmt->execute();
        $checkcount = $checkstmt->rowCount();


        // $uploadOk=1;


        if ($checkcount == 0) {

          if ($filename == "NULL") {
            echo "<script>window.alert('file not chosen')</script>";
          }

          if (!in_array($extension, ['zip', 'pdf', 'docx', 'csv'])) {
            echo "<script>window.alert('Your file extension must be .zip,.pdf, .docx,.csv')</script>";
          } else if ($_FILES['ideafile']['size'] > 500000000) //file shouldn't be larger than 1 megabite
          {

            echo "<script>window.alert('File too large')</script>";
            // $uploadOk=0;
          } else {
            if ($_POST['anonymous'] == "yes") {
              if (move_uploaded_file($file, $destination)) {

                $sql = "INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,document,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values (:qamid,:qacid,:staffId,:aid,:categoryid,:idea,:document,:status,:date,:time,:up,:down)";


                $insertstmt = $conn->prepare($sql);

                $status = 1;
                $up = 0;
                $down = 0;
                $qmid = null;
                $qcid = null;
                $staffid = null;

                $insertstmt->bindParam(":qamid", $qmid);
                $insertstmt->bindParam(":qacid", $qcid);
                $insertstmt->bindParam(":staffId", $staffid);

                $insertstmt->bindParam(":aid", $aid);
                $insertstmt->bindParam(":categoryid", $cid);
                $insertstmt->bindParam(":idea", $idea);
                $insertstmt->bindParam(":document", $filename);
                $insertstmt->bindParam(":status", $status);
                $insertstmt->bindParam(":date", $date);
                $insertstmt->bindParam(":time", $time);
                $insertstmt->bindParam(":up", $up);
                $insertstmt->bindParam(":down", $down);

                $insertstmt->execute();

                //  echo $idea;
                //  echo $date;
                //  echo $time."<br>";
                //  echo $staffid ."<br>";
                //  echo $qcid ."<br>";
                //  echo $aid ."<br>";
                //  echo $qmid ."<br>";
                //  echo $status."<br>";
                //  echo $filename;

                echo "<script>window.alert('Idea uploaded successfully')</script>";
              } else {
                echo "<script>window.alert('fail to upload idea')</script>";
              }
            }


            if ($_POST['anonymous'] == "no") {
              if (move_uploaded_file($file, $destination)) {
                $sql = "INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,document,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values (:qamid,:qacid,:staffId,:aid,:categoryid,:idea,:document,:status,:date,:time,:up,:down)";


                $insertstmt = $conn->prepare($sql);

                $status = 0;
                $up = 0;
                $down = 0;
                $qmid = null;
                $qcid = null;
                $staffid = null;

                $insertstmt->bindParam(":qamid", $qmid);
                $insertstmt->bindParam(":qacid", $qcid);
                $insertstmt->bindParam(":staffId", $staffid);

                $insertstmt->bindParam(":aid", $aid);
                $insertstmt->bindParam(":categoryid", $cid);
                $insertstmt->bindParam(":idea", $idea);
                $insertstmt->bindParam(":document", $filename);
                $insertstmt->bindParam(":status", $status);
                $insertstmt->bindParam(":date", $date);
                $insertstmt->bindParam(":time", $time);
                $insertstmt->bindParam(":up", $up);
                $insertstmt->bindParam(":down", $down);

                $insertstmt->execute();

                // echo $idea;
                // echo $date;
                // echo $time."<br>";
                // echo $staffid ."<br>";
                // echo $qcid ."<br>";
                // echo $aid ."<br>";
                // echo $qmid ."<br>";
                // echo $status."<br>";
                // echo $filename;

                echo "<script>window.alert('Idea uploaded successfully')</script>";
              } else {
                echo "<script>window.alert('Fail to upload idea')</script>";
              }
            }
          }
        } else {
          echo "<script>window.alert('This file already exists')</script>";
        }
      }
    } else {
      echo "<script>window.alert('Submission period is over!!!')</script>";
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>IdeaForm</title>
</head>

<body>

  <section class="page-title bg-1">
    <div class="overlay"></div>
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="block text-center">
            <h1 class="text-capitalize mb-5 text-lg">Idea Submit Form</h1>

          </div>
        </div>
      </div>
    </div>
  </section>



  <div class="container col-md-8 ">
    <div class="col">
      <!-- <div class="p-4"></div> -->
      <div class="border rounded mt-5 bg-light">
        <div class="text-center">
          <!-- <p class="text-capitalize my-5 text-lg text-dark">Employee Idea Form</</p> -->
          <h2 class="text-capitalize my-5 text-dark">Idea Form</h2>
        </div>
        <form action="AdminIdeaForm.php" method="post" enctype="multipart/form-data" class="mx-3">
          <div class="row mb-5 pt-4">
            <label for="inputName3" class="col-sm-2 col-form-label">Name:</label>
            <div class="col-sm-10">

              <?php

              $AdminID = $_SESSION['adminlogin'];
              $aselect = "Select * from admin where adminId='$AdminID'";
              $astmt = $conn->prepare($aselect);
              $astmt->execute();
              $afetch = $astmt->fetch();


              $aname = $afetch['adminName'];
              $aid = $afetch['adminId'];
              echo "<input  type='text' class='form-control' value='$aname'  required readonly>";
              echo "<input name='aid' type='hidden' class='form-control' value='$aid' readonly >";




              ?>
            </div>
          </div>
          <div class="row mb-5">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Department:</label>
            <div class="col-sm-10">
              <input type="hidden" id="example" class="form-control" required readonly>

            </div>
          </div>

          <div class="row mb-5">
            <label for="date" class="col-sm-2 col-form-label">Upload Date:</label>
            <div class="col-sm-10">
              <input name="date" value="<?php echo date('Y/m/d') ?>" id="example" class="form-control" required readonly>
            </div>
          </div>


          <div class="row mb-5">
            <label for="date" class="col-sm-2 col-form-label">Upload Time:</label>
            <div class="col-sm-10">
              <input name="time" value="<?php date_default_timezone_set("Asia/Yangon");
                                        echo date('H:i:s') ?>" id="example" class="form-control" required readonly>
            </div>
          </div>

          <div class="row mb-5">
            <label for="inputPassword3" class="col-sm-6 col-form-label">Do you wish to remain anonymous?</label>
            <div class="col-sm-6">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="anonymous" id="inlineRadio1" value="yes">
                <label class="form-check-label" for="inlineRadio1">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="anonymous" id="inlineRadio2" value="no" checked>
                <label class="form-check-label" for="inlineRadio2">No</label>
              </div>
            </div>
          </div>

          <div class="row mb-5">
            <label for="inputPassword3" class="col-sm-12 col-form-label">Category : </label>
            <div class="col-sm-12 ">
              <select name="category" id="" class="form-control" required>
                <option value="">None</option>
                <?php
                $cselect = "Select * from category";
                $cstmt = $conn->prepare($cselect);
                $cstmt->execute();

                $ccount = $cstmt->rowCount();

                for ($i = 0; $i < $ccount; $i++) {
                  $cfetch = $cstmt->fetch(PDO::FETCH_BOTH);
                  $cid = $cfetch['categoryId'];
                  $cname = $cfetch['category'];

                  echo "<option value='$cid'>$cname</option>";
                }
                ?>

              </select>
            </div>
          </div>

          <div class="row mb-5">
            <label for="inputPassword3" class="col-sm-12 col-form-label">Please provide a brief summary of your idea:</label>
            <div class="col-sm-12 ">
              <textarea name="idea" class="form-control" id="exampleFormControlTextarea1" rows="4"></textarea>
            </div>
          </div>

          <div class="row mb-5">
            <div class="col-sm-6 ">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                <label class="form-check-label" for="flexCheckDefault"><a href="" style="text-decoration: underline;">Agree to terms and conditions</a></label>
              </div>
            </div>
          </div>

          <div class="row mb-5">
            <label for="inputPassword3" class="col-sm-12 col-form-label">File(s) : </label>
            <div class="col-sm-12 ">
              <input type="file" name="ideafile" class="form-control" multiple>
            </div>
          </div>



          <div class="col-lg-12 mb-5" style="text-align:center; margin-top:30px;">
            <input type="submit" name="submit" class="btn btn-main btn-round-full" value="submit">
          </div>
        </form>
      </div>
    </div>
  </div>

</body>

</html>

<?php
include('footer.php');
?>