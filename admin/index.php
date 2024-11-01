<?php
session_start();
include('../connect.php');
include('header_login.php');

if (isset($_SESSION['adminlogin'])) {
  echo "<script>location='AdminDashboard.php'</script>";
}

if (isset($_POST['btnlogin'])) {
  $txtemail = $_POST['txtemail'];
  $txtpassword = $_POST['txtpassword'];

  $md5 = md5($txtpassword);

  $Admin = "SELECT * FROM admin 
				WHERE adminEmail=:email
				AND adminPassword=:md5";

  $stmt = $conn->prepare($Admin);
  $stmt->bindParam(':email', $txtemail);
  $stmt->bindParam(':md5', $md5);

  $stmt->execute();   //execute
  $checkcount = $stmt->rowCount();  //row count


  if ($checkcount == 1) {
    echo "<script>window.alert('Welcome Back. Login Successful')</script>";
    echo "<script>window.location='AdminDashboard.php'</script>";
    $_SESSION['adminlogin']=1;
  } else {
    echo "<script>window.alert('Email or Password Incorrect')</script>";
    echo "<script>window.location='index.php'</script>";
  }
}

?>

<section class="page-title" style="background-image: url('../images/bg/hat.png') !important; background-size: cover; background-repeat: no-repeat;">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Hello!</span>
          <h1 class="text-capitalize mb-5 text-lg">Welcome</h1>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container col-md-6 ">
  <div class="col">
    <!-- <div class="p-4"></div> -->
    <div class="border rounded mt-5 bg-light">
      <div class="text-center">
        <h2 class="text-capitalize my-5 text-lg">Admin Login</h2>
      </div>
      <form action="index.php" method="POST" class="mx-3">
        <div class="row mb-3">
          <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
          <div class="col-sm-10">
            <input type="email" name="txtemail" class="form-control" placeholder="Enter Your Email Address" id="inputEmail3" autocomplete="off" required>
          </div>
        </div>
        <div class="row mb-5">
          <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
          <div class="col-sm-10">
            <input type="password" name="txtpassword" class="form-control" placeholder="Enter Password" id="inputPassword3" autocomplete="off" required>
          </div>
        </div>
        <div class="text-center mb-3">
          <button type="submit" name="btnlogin" class="btn btn-primary btn-lg ">Login in</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
include('footer.php');
?>