<?php
session_start();

include("connect.php");
include("header_login.php");
if (isset($_SESSION['authorid']) && isset($_SESSION['authortype'])) {
	echo "<script>location='UserDashboard.php'</script>";
}

if (isset($_REQUEST['login'])) {
	$email = $_REQUEST['email'];
	$password = $_REQUEST['password'];

	if ($_POST['logintype'] == "qam") {
		$check = "Select * from qam where qamEmail= :email ";
		$stmt = $conn->prepare($check);
		$stmt->bindParam(':email', $email);

		$stmt->execute();   //execute
		$checkcount = $stmt->rowCount();  //row count
		if ($checkcount == 1) {
			$fetch = $stmt->fetch(PDO::FETCH_BOTH);  //fetch array
			if ($fetch['status'] == 1) {
				if (password_verify($password,  $fetch['qamPassword'])) {
					echo "<script>window.alert('Login Successful as QA Manager...')</script>";
					$_SESSION['authorid']=$fetch['qamId'];
					$_SESSION['authortype']="qam";
					echo "<script>location='UserDashboard.php'</script>";
				} else {
					if (isset($_SESSION['errorcount'])) {
						$logincount = $_SESSION['errorcount'];

						if ($logincount == 1) {
							$_SESSION['errorcount'] = 2;
							echo "<script>window.alert('Login Fail 2nd time')</script>";
						}
						if ($logincount == 2) {
							$_SESSION['errorcount'] = 3;
							echo "<script>window.alert('Login Fail 3rd time')</script>";
							echo "<script>window.location='LoginTimer.php'</script>";
						}

						if ($logincount == 3) {

							echo "<script>window.alert('Login Fail. You are locked for 10 minutes')</script>";
						}
					} else {
						$_SESSION['errorcount'] = 1;
						echo "<script>window.alert('Login Fail First time')</script>";
					}
				}
			} else {
				echo "<script>window.alert('Your account is still being requesting! You have no access to use it. Contact admin team to get quickly.')</script>";
			}
		} else {
			echo "<script>window.alert('Account was not found! Invalid Email!')</script>";
		}
	}

	if ($_POST['logintype'] == "qac") {
		$check = "Select * from qac where qacEmail= :email";
		$stmt = $conn->prepare($check);
		$stmt->bindParam(':email', $email);

		$stmt->execute();   //execute
		$checkcount = $stmt->rowCount();  //row count
		if ($checkcount == 1) {
			$fetchdata = $stmt->fetch(PDO::FETCH_BOTH);  //fetch array
			if ($fetchdata['status'] == 1) {
				$hashedpassfromdb = $fetchdata['qacPassword'];
				if (password_verify($password, $hashedpassfromdb)) {
					echo "<script>alert('Login successful as QA Coordinator...')</script>";
					$_SESSION['authorid']=$fetchdata['qacId'];
					$_SESSION['authortype']="qac";
					echo "<script>location='UserDashboard.php'</script>";
				} else {
					if (isset($_SESSION['errorcount'])) {
						$logincount = $_SESSION['errorcount'];

						if ($logincount == 1) {
							$_SESSION['errorcount'] = 2;
							echo "<script>window.alert('Login Fail 2nd time')</script>";
						}
						if ($logincount == 2) {
							$_SESSION['errorcount'] = 3;
							echo "<script>window.alert('Login Fail 3rd time')</script>";
							echo "<script>window.location='LoginTimer.php'</script>";
						}

						if ($logincount == 3) {

							echo "<script>window.alert('Login Fail. You are locked for 10 minutes')</script>";
						}
					} else {
						$_SESSION['errorcount'] = 1;
						echo "<script>window.alert('Login Fail First time')</script>";
					}
				}
			} else {
				echo "<script>window.alert('Your account is still being requesting! You have no access to use it. Contact admin team to get quickly.')</script>";
			}
		} else {
			echo "<script>window.alert('Account was not found! Invalid Email!')</script>";
		}
	}

	if ($_POST['logintype'] == "staff") {
		$check = "Select * from staff where staffEmail= :email ";
		$stmt = $conn->prepare($check);
		$stmt->bindParam(':email', $email);

		$stmt->execute();   //execute
		$checkcount = $stmt->rowCount();  //row count
		if ($checkcount == 1) {
			$fetch = $stmt->fetch(PDO::FETCH_BOTH);  //fetch array
			if ($fetch['status'] == 1) {
				if (password_verify($password,  $fetch['staffPassword'])) {
					echo "<script>window.alert('Login Successful as Staff')</script>";
					$_SESSION['authorid']=$fetch['staffId'];
					$_SESSION['authortype']="staff";
					echo "<script>location='UserDashboard.php'</script>";
				} else {
					if (isset($_SESSION['errorcount'])) {
						$logincount = $_SESSION['errorcount'];

						if ($logincount == 1) {
							$_SESSION['errorcount'] = 2;
							echo "<script>window.alert('Login Fail 2nd time')</script>";
						}
						if ($logincount == 2) {
							$_SESSION['errorcount'] = 3;
							echo "<script>window.alert('Login Fail 3rd time')</script>";
							echo "<script>window.location='LoginTimer.php'</script>";
						}

						if ($logincount == 3) {

							echo "<script>window.alert('Login Fail. You are locked for 10 minutes')</script>";
						}
					} else {
						$_SESSION['errorcount'] = 1;
						echo "<script>window.alert('Login Fail First time')</script>";
					}
				}
			} else {
				echo "<script>window.alert('Your account is still being requesting! You have no access to use it. Contact admin team to get quickly.')</script>";
			}
		} else {
			echo "<script>window.alert('Account was not found! Invalid Email!')</script>";
		}
	}
}

?>

<!DOCTYPE html>
<html lang="zxx">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="description" content="Orbitor,business,company,agency,modern,bootstrap4,tech,software">
	<meta name="author" content="themefisher.com">
	<script src="https://kit.fontawesome.com/c8fd1d96f9.js" crossorigin="anonymous"></script>

	<title>Novena- Health & Care Medical template</title>

	<!-- Favicon -->
	<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />

	<!-- bootstrap.min css -->
	<link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css">
	<!-- Icon Font Css -->
	<link rel="stylesheet" href="plugins/icofont/icofont.min.css">
	<!-- Slick Slider  CSS -->
	<link rel="stylesheet" href="plugins/slick-carousel/slick/slick.css">
	<link rel="stylesheet" href="plugins/slick-carousel/slick/slick-theme.css">

	<!-- Main Stylesheet -->
	<link rel="stylesheet" href="css/style.css">

</head>

<body id="top">

	<header>
		<!-- <div class="header-top-bar">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<ul class="top-bar-info list-inline-item pl-0 mb-0">
						<li class="list-inline-item"><a href="mailto:support@gmail.com"><i class="icofont-support-faq mr-2"></i>support@novena.com</a></li>
						<li class="list-inline-item"><i class="icofont-location-pin mr-2"></i>Address Ta-134/A, New York, USA </li>
					</ul>
				</div>
				<div class="col-lg-6">
					<div class="text-lg-right top-right-bar mt-2 mt-lg-0">
						<a href="tel:+23-345-67890" >
							<span>Call Now : </span>
							<span class="h4">823-4565-13456</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div> -->
		<!-- <nav class="navbar navbar-expand-lg navigation" id="navbar">
		<div class="container">
		 	 <a class="navbar-brand" href="index.html">
			  	<img src="images/logo.png" alt="" class="img-fluid">
			  </a>

		  	<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarmain" aria-controls="navbarmain" aria-expanded="false" aria-label="Toggle navigation">
			<span class="icofont-navigation-menu"></span>
		  </button>
	  
		  <div class="collapse navbar-collapse" id="navbarmain">
			<ul class="navbar-nav ml-auto">
			  <li class="nav-item active">
				<a class="nav-link" href="index.html">Home</a>
			  </li>
			   <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
			    <li class="nav-item"><a class="nav-link" href="service.html">Services</a></li>

			    <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="department.html" id="dropdown02" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Department <i class="icofont-thin-down"></i></a>
					<ul class="dropdown-menu" aria-labelledby="dropdown02">
						<li><a class="dropdown-item" href="department.html">Departments</a></li>
						<li><a class="dropdown-item" href="department-single.html">Department Single</a></li>
					</ul>
			  	</li>

			  	<li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="doctor.html" id="dropdown03" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Doctors <i class="icofont-thin-down"></i></a>
					<ul class="dropdown-menu" aria-labelledby="dropdown03">
						<li><a class="dropdown-item" href="doctor.html">Doctors</a></li>
						<li><a class="dropdown-item" href="doctor-single.html">Doctor Single</a></li>
						<li><a class="dropdown-item" href="appoinment.html">Appoinment</a></li>
					</ul>
			  	</li>

			   <li class="nav-item dropdown">
					<a class="nav-link dropdown-toggle" href="blog-sidebar.html" id="dropdown05" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Blog <i class="icofont-thin-down"></i></a>
					<ul class="dropdown-menu" aria-labelledby="dropdown05">
						<li><a class="dropdown-item" href="blog-sidebar.html">Blog with Sidebar</a></li>

						<li><a class="dropdown-item" href="blog-single.html">Blog Single</a></li>
					</ul>
			  	</li>
			   <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
			</ul>
		  </div>
		</div>
	</nav> -->
	</header>



	<section class="page-title" style="background-image: url('images/bg/hat.png') !important; background-size: cover; background-repeat: no-repeat;">
		<div class="overlay"></div>
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="block text-center">
						<!-- <span class="text-white">QA Coordinator Register</span> -->
						<h1 class="text-capitalize mb-5 text-lg">User Panel Login</h1>

						<!-- <ul class="list-inline breadcumb-nav">
            <li class="list-inline-item"><a href="index.html" class="text-white">Home</a></li>
            <li class="list-inline-item"><span class="text-white">/</span></li>
            <li class="list-inline-item"><a href="#" class="text-white-50">Contact Us</a></li>
          </ul> -->
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- contact form start -->

	<!-- <section class="section contact-info pb-0">
    <div class="container">
         <div class="row">
            <div class="col-lg-4 col-sm-6 col-md-6">
                <div class="contact-block mb-4 mb-lg-0">
                    <i class="icofont-live-support"></i>
                    <h5>Call Us</h5>
                     +823-4565-13456
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6">
                <div class="contact-block mb-4 mb-lg-0">
                    <i class="icofont-support-faq"></i>
                    <h5>Email Us</h5>
                     contact@mail.com
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-md-6">
                <div class="contact-block mb-4 mb-lg-0">
                    <i class="icofont-location-pin"></i>
                    <h5>Location</h5>
                     North Main Street,Brooklyn Australia
                </div>
            </div>
        </div>
    </div>
</section> -->

	<section class="contact-form-wrap section">
		<div class="container">
			<div class="row justify-content-center">
				<div class="col-lg-6">
					<div class="section-title text-center">
						<h2 class="text-md mb-2">Login Here</h2>
						<div class="divider mx-auto my-4"></div>
						<!-- <p class="mb-5">Laboriosam exercitationem molestias beatae eos pariatur, similique, excepturi mollitia sit perferendis maiores ratione aliquam?</p> -->
					</div>
				</div>
			</div>
			<div class="row container-fluid">
				<div class="col-lg-3"></div>
				<div class="col-lg-6 col-md-12 col-sm-12">
					<form method="post" action="index.php">

						<div class="row">

							<div class="col-sm-3" style="color:black; text-align:center; margin-top:15px;">
								Email <i class="fas fa-envelope"></i> :
							</div>
							<div class="col-sm-9">
								<div class="form-group">
									<input name="email" type="email" class="form-control" placeholder="Enter Your Email Address" autocomplete="off" required>
								</div>
							</div>

							<div class="col-sm-3" style="color:black; text-align:center; margin-top:15px;">
								Password <i class="fas fa-key"></i> :
							</div>
							<div class="col-sm-9">
								<div class="form-group">
									<input name="password" type="password" class="form-control" placeholder="Enter Password" autocomplete="off" required>
								</div>
							</div>


							<div class="col-sm-3" style="color:black; text-align:center; margin-top:15px;">

							</div>
							<div class="col-sm-9">
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="logintype" id="inlineRadio1" value="qam" checked>
									<label class="form-check-label" for="inlineRadio1">QA Manager</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="logintype" id="inlineRadio2" value="qac">
									<label class="form-check-label" for="inlineRadio2">QA Coordinator</label>
								</div>
								<div class="form-check form-check-inline">
									<input class="form-check-input" type="radio" name="logintype" id="inlineRadio3" value="staff">
									<label class="form-check-label" for="inlineRadio3">Staff</label>
								</div>
							</div>

						</div>


						<div class="col-lg-12" style="text-align:center; margin-top:30px;">
							<input type="submit" name="login" class="btn btn-main btn-round-full" value="Login">
						</div>

					</form>
				</div>
			</div>
	</section>



	<?php
	include("footer.php");
	?>

	<!-- 
    Essential Scripts
    =====================================-->


	<!-- Main jQuery -->
	<script src="plugins/jquery/jquery.js"></script>
	<!-- Bootstrap 4.3.2 -->
	<script src="plugins/bootstrap/js/popper.js"></script>
	<script src="plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="plugins/counterup/jquery.easing.js"></script>
	<!-- Slick Slider -->
	<script src="plugins/slick-carousel/slick/slick.min.js"></script>
	<!-- Counterup -->
	<script src="plugins/counterup/jquery.waypoints.min.js"></script>

	<script src="plugins/shuffle/shuffle.min.js"></script>
	<script src="plugins/counterup/jquery.counterup.min.js"></script>
	<!-- Google Map -->
	<script src="plugins/google-map/map.js"></script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAkeLMlsiwzp6b3Gnaxd86lvakimwGA6UA&callback=initMap"></script>

	<script src="js/script.js"></script>
	<script src="js/contact.js"></script>

</body>

</html>