<?php
error_reporting(0);
include('connect.php');
include('header.php');
// session_start();

use PHPMailer\PHPMailer\PHPMailer;

require 'PHPMailer/PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/PHPMailer/src/SMTP.php';

if (isset($_REQUEST['submit'])) {

  $qamid = $_REQUEST['qamid'];
  $qmid = !empty($qamid) ? $qamid : null;

  $qacid = $_REQUEST['qcid'];
  $qcid = !empty($qacid) ? $qacid : null;


  $sid = $_REQUEST['sid']; //assign
  $staffid = !empty($sid) ? $sid : null; //check empty

  $cid = $_REQUEST['category'];
  $idea = $_REQUEST['idea'];
  $date = $_REQUEST['date'];
  $time = $_REQUEST['time'];


  $mail = new PHPMailer();
  $mail->SMTPDebug = 0;                               // Enable verbose debug output
  $mail->isSMTP();                                      // Set mailer to use SMTP
  $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
  $mail->SMTPAuth = true;                               // Enable SMTP authentication
  $mail->Username = 'derbyshireuniversity@gmail.com';                 // SMTP username
  $mail->Password = 'derbyshire1234';                           // SMTP password
  $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
  //$mail->Port = 465;                                    // TCP port to connect to
  $mail->Port = 587;
  $mail->setFrom('derbyshireuniversity@gmail.com', 'Derbyshire University');
  $mail->addReplyTo('derbyshireuniversity@gmail.com');

  //Check closure Date
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
        $sql = "INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values (:qamid,:qacid,:staffId,null,:categoryid,:idea,:status,:date,:time,:up,:down)";

        // $sql="INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,document,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values ($qamid,$qacid,$staffid,NULL,$cid,$idea,$filename,$status,$date,$time,$up,$down)";
        $insertstmt = $conn->prepare($sql);
        if ($_POST['anonymous'] == "yes") {
          $status = 1;
        } else {
          $status = 0;
        }
        $up = 0;
        $down = 0;

        $insertstmt->bindParam(":qamid", $qmid);
        $insertstmt->bindParam(":qacid", $qcid);
        $insertstmt->bindParam(":staffId", $staffid);
        $insertstmt->bindParam(":categoryid", $cid);
        $insertstmt->bindParam(":idea", $idea);
        $insertstmt->bindParam(":status", $status);
        $insertstmt->bindParam(":date", $date);
        $insertstmt->bindParam(":time", $time);
        $insertstmt->bindParam(":up", $up);
        $insertstmt->bindParam(":down", $down);

        if ($insertstmt->execute()) {
        } else {
          // echo var_dump($insertstmt->errorInfo());
        }


        //Mail Function
        //mail  
        $mquery = "SELECT qc.*,s.*,d.*,i.*
                     from qac qc, staff s,department d,idea i
                     where qc.deptId=d.deptId
                     and d.deptId=s.deptId
                     and i.staffId=s.staffId
                     and s.staffId=:staffId
                     
             ";
        $mstmt = $conn->prepare($mquery);
        $mstmt->bindParam(":staffId", $staffid);

        $mstmt->execute();

        $mcount = $mstmt->rowCount();

        while ($mrow = $mstmt->fetch(PDO::FETCH_BOTH)) {
          $address = $mrow['qacEmail'];
          $qacname = $mrow['qacName'];
          $sname = $mrow['staffName'];
          $dname = $mrow['department'];


          $mail->addAddress($address, $qacname);
          $mail->Subject = 'Idea submission notification';

          if ($status == 0) {
            $mail->Body = "$sname from your department, $dname uploaded an idea ";
          }

          if ($status == 1) {
            $mail->Body    = "Someone from your department, $dname uploaded an idea ";
          }
        }
        //---------------------


        echo "<script>window.alert('Idea uploaded successfully without file')</script>";
      } else {
        //destination of the file on the server
        $destination = "Uploads/" . $filename;

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
                // $sql="INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,document,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values (NULLIF(:qamid,''),NULLIF(:qacid,''),NULLIF(:staffId,''),NULL,:categoryid,:idea,NULLIF(:document,''),1,:date,:time,0,0)";
                $sql = "INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,document,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values (:qamid,:qacid,:staffId,null,:categoryid,:idea,:document,:status,:date,:time,:up,:down)";

                // $sql="INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,document,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values ($qamid,$qacid,$staffid,NULL,$cid,$idea,$filename,$status,$date,$time,$up,$down)";
                $insertstmt = $conn->prepare($sql);

                $status = 1;
                $up = 0;
                $down = 0;

                $insertstmt->bindParam(":qamid", $qmid);
                $insertstmt->bindParam(":qacid", $qcid);
                $insertstmt->bindParam(":staffId", $staffid);
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
                // echo  $staffid ."<br>";
                // echo  $qcid ."<br>";
                // echo $qmid ."<br>";
                // echo $status."<br>";
                // echo $filename;

                $mquery = "SELECT qc.*,s.*,d.*,i.*
            from qac qc, staff s,department d,idea i
            where qc.deptId=d.deptId
            and d.deptId=s.deptId
            and i.staffId=s.staffId
            and s.staffId=:staffId
            
    ";
                $mstmt = $conn->prepare($mquery);
                $mstmt->bindParam(":staffId", $staffid);

                $mstmt->execute();

                $mcount = $mstmt->rowCount();

                while ($mrow = $mstmt->fetch(PDO::FETCH_BOTH)) {
                  $address = $mrow['qacEmail'];
                  $qacname = $mrow['qacName'];
                  $sname = $mrow['staffName'];
                  $dname = $mrow['department'];

                  $mail->addAddress($address, $qacname);
                  $mail->Subject = 'Idea submit notification for anonymous';
                  $mail->Body    = "Someone from your department, $dname uploaded an idea with a file";
                }

                echo "<script>window.alert('Idea uploaded successfully')</script>";
              } else {
                echo "<script>window.alert('fail to upload idea')</script>";
              }
            }


            if ($_POST['anonymous'] == "no") {
              if (move_uploaded_file($file, $destination)) {
                $sql = "INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,document,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values (:qamid,:qacid,:staffId,null,:categoryid,:idea,:document,:status,:date,:time,:up,:down)";
                // $sql="INSERT INTO idea (qamId,qacId,staffId,adminId,categoryId,idea,document,anonymousStatus,uploadDate,uploadTime,thumbUp,thumbDown) values ($qamid,$qacid,$staffid,NULL,$cid,$idea,$filename,$status,$date,$time,$up,$down)";
                $insertstmt = $conn->prepare($sql);

                $status = 0;
                $up = 0;
                $down = 0;

                $insertstmt->bindParam(":qamid", $qmid);
                $insertstmt->bindParam(":qacid", $qcid);
                $insertstmt->bindParam(":staffId", $staffid);
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
                // echo $time;
                // echo $staffid;
                // echo $qcid;
                // echo $qmid;
                // echo $status;
                // echo $filename;

                $mquery = "SELECT qc.*,s.*,d.*,i.*
            from qac qc, staff s,department d,idea i
            where qc.deptId=d.deptId
            and d.deptId=s.deptId
            and i.staffId=s.staffId
            and s.staffId=:staffId
            
    ";
                $mstmt = $conn->prepare($mquery);
                $mstmt->bindParam(":staffId", $staffid);

                $mstmt->execute();

                $mcount = $mstmt->rowCount();

                while ($mrow = $mstmt->fetch(PDO::FETCH_BOTH)) {
                  $address = $mrow['qacEmail'];
                  $qacname = $mrow['qacName'];
                  $sname = $mrow['staffName'];
                  $dname = $mrow['department'];


                  $mail->addAddress($address, $qacname);
                  $mail->Subject = 'Idea submit notification for non-anonymous';
                  $mail->Body    = "$sname from your department, $dname uploaded an idea with a file";
                }

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
      $mail->send();
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

  <section class="page-title" style="background-image: url('images/bg/hat.png') !important; background-size: cover; background-repeat: no-repeat;">
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
        <form action="UserIdeaForm.php" method="post" enctype="multipart/form-data" class="mx-3">
          <div class="row mb-5 pt-4">
            <label for="inputName3" class="col-sm-2 col-form-label">Name:</label>
            <div class="col-sm-10">

              <?php

              if ($_SESSION['authortype'] == "qam") {
                $authorid = $_SESSION['authorid'];
                $qmnselect = "Select * from qam where qamId=$authorid";
                $qmnstmt = $conn->prepare($qmnselect);
                $qmnstmt->execute();
                $qmnfetch = $qmnstmt->fetch();

                $qmname = $qmnfetch['qamName'];
                $qmid = $qmnfetch['qamId'];

                echo "<input  type='text' class='form-control' value=' $qmname' readonly>";
                echo "<input type='hidden' name='qamid' value='$qmid'>";
              }


              if ($_SESSION['authortype'] == "qac") {
                $authorid = $_SESSION['authorid'];
                $qcnselect = "Select * from qac where qacId=$authorid";
                $qcnstmt = $conn->prepare($qcnselect);
                $qcnstmt->execute();
                $qcnfetch = $qcnstmt->fetch();

                $qcname = $qcnfetch['qacName'];
                $qcid = $qcnfetch['qacId'];

                echo "<input type='text' class='form-control' value='$qcname'  readonly>";
                echo "<input type='hidden' name='qcid' value='$qcid'>";
              }

              if ($_SESSION['authortype'] == "staff") {
                $authorid = $_SESSION['authorid'];
                $snselect = "Select * from staff where staffId=$authorid";
                $snstmt = $conn->prepare($snselect);
                $snstmt->execute();
                $snfetch = $snstmt->fetch();

                $sname = $snfetch['staffName'];
                $staffid = $snfetch['staffId'];

                echo "<input type='text' class='form-control' value='$sname'  readonly>";
                echo "<input type='hidden' name='sid' value='$staffid'>";
              }

              ?>
            </div>
          </div>
          <div class="row mb-5">
            <label for="inputPassword3" class="col-sm-2 col-form-label">Department:</label>
            <div class="col-sm-10">
              <?php
              if ($_SESSION['authortype'] == "qac") {
                $authorid = $_SESSION['authorid'];
                $selectstmt = $conn->prepare("SELECT q.*,d.* from qac q,department d where q.deptId=d.deptId and q.qacId=$authorid");
                $selectstmt->execute();
                $data = $selectstmt->fetch(PDO::FETCH_ASSOC);
                $department = $data['department'];
                echo "<input name='department' type='text' style='color:black;' class='form-control' value='$department' required readonly>";
                // echo "<input type='hidden' name='did' value='$did'>";
              }

              if ($_SESSION['authortype'] == "staff") {
                $authorid = $_SESSION['authorid'];
                $selectstmt = $conn->prepare("SELECT s.*,d.* from staff s,department d where s.deptId=d.deptId and s.staffId=$authorid");
                $selectstmt->execute();
                $data = $selectstmt->fetch(PDO::FETCH_ASSOC);
                $department = $data['department'];
                echo "<input name='department' type='text' style='color:black;' class='form-control' value='$department' required readonly>";
                // echo "<input type='hidden' name='did' value='$did'>";

              }

              if ($_SESSION['authortype'] == "qam") {

                echo "<input name='department' type='text' style='color:black;' class='form-control' placeholder='No Department Found'  required readonly>";
              }

              ?>


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
                                        echo date('H:i:s') ?>" id="example" class="form-control" readonly>
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
            <label for="inputPassword3" class="col-sm-12 col-form-label">Idea Category : </label>
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