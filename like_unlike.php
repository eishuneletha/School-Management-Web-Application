<?php
include "connect.php";
include "header.php";

use PHPMailer\PHPMailer\PHPMailer;

require 'PHPMailer/PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/PHPMailer/src/SMTP.php';
//Get Idea Detail
if (isset($_REQUEST['ideaIdToDetail'])) {
	$ideaId = $_REQUEST['ideaIdToDetail'];

	$date = "";
	$time = "";
	$category = "";
	$authorname = "";
	$department = "";
	$idea = "";
	$file = "";

	$selectideastmt = $conn->prepare("SELECT * from idea where ideaId=$ideaId");
	$selectideastmt->execute();
	$dataofidea = $selectideastmt->fetch(PDO::FETCH_ASSOC);
	$adminId = $dataofidea['adminId'];
	$qamId = $dataofidea['qamId'];
	$qacId = $dataofidea['qacId'];
	$staffId = $dataofidea['staffId'];
	if ($adminId != 0) {
		$selectadminpost = $conn->prepare("SELECT i.*,a.*,c.* from idea i,admin a,category c where i.adminId=a.adminId and i.categoryId=c.categoryId and a.adminId=$adminId and i.ideaId=$ideaId");
		$selectadminpost->execute();
		$dataofadminpost = $selectadminpost->fetch(PDO::FETCH_ASSOC);

		$date = $dataofadminpost['uploadDate'];
		$time = $dataofadminpost['uploadTime'];

		$category = $dataofadminpost['category'];
		$idea = $dataofadminpost['idea'];
		$anonymous = $dataofadminpost['anonymousStatus'];
		$authoremail=$dataofadminpost['adminEmail'];
		if ($anonymous == 1) {
			$authorname = "Anonymous Author";
		} else {
			$authorname = $dataofadminpost['adminName'];
		}
		$file = !empty($dataofadminpost['document']) ? $dataofadminpost['document'] : "No File Found";
	}
	if ($qamId != 0) {
		$selectqampost = $conn->prepare("SELECT i.*,qm.*,c.* from idea i,qam qm,category c where i.qamId=qm.qamId and i.categoryId=c.categoryId and qm.qamId=$qamId and i.ideaId=$ideaId");
		$selectqampost->execute();
		$dataofqampost = $selectqampost->fetch(PDO::FETCH_ASSOC);

		$date = $dataofqampost['uploadDate'];
		$time = $dataofqampost['uploadTime'];

		$category = $dataofqampost['category'];
		$idea = $dataofqampost['idea'];
		$anonymous = $dataofqampost['anonymousStatus'];
		$authoremail=$dataofqampost['qamEmail'];
		if ($anonymous == 1) {
			$authorname = "Anonymous Author";
		} else {
			$authorname = $dataofqampost['qamName'];
		}
		$file = !empty($dataofqampost['document']) ? $dataofqampost['document'] : "No File Found";
	}
	if ($qacId != 0) {
		$selectqacpost = $conn->prepare("SELECT i.*,qc.*,c.*,d.* from idea i,qac qc,category c,department d where i.qacId=qc.qacId and i.categoryId=c.categoryId and qc.deptId=d.deptId and qc.qacId=$qacId and i.ideaId=$ideaId");
		$selectqacpost->execute();
		$dataofqacpost = $selectqacpost->fetch(PDO::FETCH_ASSOC);

		$date = $dataofqacpost['uploadDate'];
		$time = $dataofqacpost['uploadTime'];

		$category = $dataofqacpost['category'];
		$idea = $dataofqacpost['idea'];
		$anonymous = $dataofqacpost['anonymousStatus'];
		$authoremail=$dataofqacpost['qacEmail'];
		if ($anonymous == 1) {
			$authorname = "Anonymous Author";
		} else {
			$authorname = $dataofqacpost['qacName'];
		}
		$file = !empty($dataofqacpost['document']) ? $dataofqacpost['document'] : "No File Found";
		$department = $dataofqacpost['department'];
	}
	if ($staffId != 0) {
		$selectstaffpost = $conn->prepare("SELECT i.*,s.*,c.*,d.* from idea i,staff s,category c,department d where i.staffId=s.staffId and i.categoryId=c.categoryId and s.deptId=d.deptId and s.staffId=$staffId and i.ideaId=$ideaId");
		$selectstaffpost->execute();
		$dataofstaffpost = $selectstaffpost->fetch(PDO::FETCH_ASSOC);

		$date = $dataofstaffpost['uploadDate'];
		$time = $dataofstaffpost['uploadTime'];

		$category = $dataofstaffpost['category'];
		$idea = $dataofstaffpost['idea'];
		$anonymous = $dataofstaffpost['anonymousStatus'];
		$authoremail=$dataofstaffpost['staffEmail'];
		if ($anonymous == 1) {
			$authorname = "Anonymous Author";
		} else {
			$authorname = $dataofstaffpost['staffName'];
		}
		$file = !empty($dataofstaffpost['document']) ? $dataofstaffpost['document'] : "No File Found";
		$department = $dataofstaffpost['department'];
	}
} else {
	// echo "<script>alert('Access Denied')</script>";
	// echo "<script>location='ideaShowcase.php'</script>";
}

//-------------------------------------

if ($_SESSION['authortype'] == "admin") {
	$authorid = $_SESSION['authorid'];
	$adnselect = "Select * from admin where adminId=$authorid";
	$adnstmt = $conn->prepare($adnselect);
	$adnstmt->execute();
	$adnfetch = $adnstmt->fetch();

	$adname = $adnfetch['adminName'];
	$adminid = $adnfetch['adminId'];
	$adminEmail = $adnfetch['adminEmail'];

	echo "<input type='hidden' class='form-control' value='$adminname'  readonly>";
	echo "<input type='hidden' name='adminid' value='$adminid'>";
}

if ($_SESSION['authortype'] == "qam") {
	$authorid = $_SESSION['authorid'];
	$qmnselect = "Select * from qam where qamId=$authorid";
	$qmnstmt = $conn->prepare($qmnselect);
	$qmnstmt->execute();
	$qmnfetch = $qmnstmt->fetch();

	$qmname = $qmnfetch['qamName'];
	$qmid = $qmnfetch['qamId'];
	$qamEmail = $qmnfetch['qamEmail'];

	echo "<input  type='hidden' class='form-control' value=' $qmname' readonly>";
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
	$qacEmail = $qcnfetch['qacEmail'];

	echo "<input type='hidden' class='form-control' value='$qcname'  readonly>";
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
	$staffEmail = $snfetch['staffEmail'];


	echo "<input type='hidden' class='form-control' value='$sname'  readonly>";
	echo "<input type='hidden' name='sid' value='$staffid'>";
}

$adminid = !empty($adminid) ? $adminid : null;

//$qamid = $qmid;
$qmid = !empty($qmid) ? $qmid : null;

//$qacid = $qcid;
$qcid = !empty($qcid) ? $qcid : null;


//$sid =$staffid; //assign
$staffid = !empty($staffid) ? $staffid : null; //check empty

$ideaid = $_REQUEST['ideaIdToDetail'];


if ($adminid != null) {
	$userid = $adminid;
	$usrtype = "admin";
	$usrname = $adname;
	$usrEmail = $adminEmail;
}
if ($qmid != null) {
	$userid = $qmid;
	$usrtype = "qm";
	$usrname = $qmname;
	$usrEmail = $qamEmail;
}
if ($qcid != null) {
	$userid = $qcid;
	$usrtype = "qc";
	$usrname = $qcname;
	$usrEmail = $qacEmail;
}
if ($staffid != null) {
	$userid = $staffid;
	$usrtype = "staff";
	$usrname = $sname;
	$usrEmail = $staffEmail;
}

// var_dump("ideaid",$ideaid,"adminid",$adminid,"qamid",$qmid,"qacid",$qcid,"staffid",$staffid);

if ($usrtype == "admin") {
	$check = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND adminid=:adminid";
	$stmt = $conn->prepare($check);
	$stmt->bindParam(":ideaid", $ideaid);
	$stmt->bindParam(":adminid", $adminid);
	$stmt->execute();
	$count = $stmt->rowCount();

	if ($count == '0') {
		// echo "Failed !";
	} else {
		// echo "Admin Success !";
		$condition = "exit";
	}
}


if ($usrtype == "qm") {
	$qmid = (int)$qmid;
	// var_dump("qmid = ",$qmid);
	// var_dump("ideaid = ",$ideaid);
	$check = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND qamId=:qamid";
	$stmt = $conn->prepare($check);
	$stmt->bindParam(":ideaid", $ideaid);
	$stmt->bindParam(":qamid", $qmid);
	$stmt->execute();
	$count = $stmt->rowCount();

	if ($count == '0') {
		// echo "Failed !";
	} else {
		// echo "qm Success !";
		$condition = "exit";
	}
}
if ($usrtype == "qc") {
	$check = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND qacid=:qacid";
	$stmt = $conn->prepare($check);
	$stmt->bindParam(":ideaid", $ideaid);
	$stmt->bindParam(":qacid", $qcid);
	$stmt->execute();
	$count = $stmt->rowCount();
	if ($count == '0') {
		// echo "Failed !";
	} else {
		// echo "qc Success !";
		$condition = "exit";
	}
}

if ($usrtype == "staff") {
	$check = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND staffid=:staffid";
	$stmt = $conn->prepare($check);
	$stmt->bindParam(":ideaid", $ideaid);
	$stmt->bindParam(":staffid", $staffid);
	$stmt->execute();
	$count = $stmt->rowCount();

	if ($count == '0') {
		// echo "Failed !";
	} else {
		// echo "staff Success !";
		$condition = "exit";
	}
}



// $check = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND adminid=:adminid AND qamid=:qamid AND qacid=:qacid AND staffid=:staffid";
//   	$check = "SELECT * FROM rating_info WHERE adminid=:adminid ";
// $stmt = $conn->prepare($check);

// $stmt->bindParam(":ideaid", $ideaid);
// $stmt->bindParam(":adminid", $adminid);
// $stmt->bindParam(":qamid", $qmid);
//            $stmt->bindParam(":qacid", $qcid);
//            $stmt->bindParam(":staffid", $staffid);

//                         $stmt->execute();
//                         $count = $stmt->rowCount();

// if($count =='0'){
//     echo "Failed !";
// }
// else{
//     echo "Success !";
// }

// if ($stmt->execute()) 
// {
//                   echo "successfully";
//     } 
//     else 
//        {
//          echo var_dump($insertstmt->errorInfo());
//        }



if ($count == 0) {
	$condition = "";
} else {
	$condition = "exit";
}

if ($condition == "exit") {

	if ($usrtype == "qm") {

		if (isset($_GET['did'])) {

			$did = $_GET['did'];
			$didr = 0;

			$sql = "UPDATE rating_info set rating_action=:rating_action WHERE ideaid=:ideaid AND qamid=:qamid";



			$insertstmt = $conn->prepare($sql);

			$insertstmt->bindParam(":ideaid", $ideaid);
			// $insertstmt->bindParam(":adminid", $adminid);
			$insertstmt->bindParam(":qamid", $qmid);
			// $insertstmt->bindParam(":qacid", $qcid);
			// $insertstmt->bindParam(":staffid", $staffid);
			$insertstmt->bindParam(":rating_action", $didr);
			$insertstmt->execute();
			// if ($insertstmt->execute()) {
			//                         	echo "dislike successfully";

			//         } else {
			//           echo var_dump($insertstmt->errorInfo());
			//                   }
			echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
		}
		if (isset($_GET['lid'])) {
			$lid = $_GET['lid'];
			$lidr = 1;
			$sql = "UPDATE rating_info set rating_action=:rating_action WHERE ideaid=:ideaid AND qamid=:qamid";

			$insertstmt = $conn->prepare($sql);
			$insertstmt->bindParam(":ideaid", $ideaid);
			// $insertstmt->bindParam(":adminid", $adminid);
			$insertstmt->bindParam(":qamid", $qmid);
			// $insertstmt->bindParam(":qacid", $qcid);
			// $insertstmt->bindParam(":staffId", $staffid);
			$insertstmt->bindParam(":rating_action", $lidr);

			$insertstmt->execute();


			//                 if ($insertstmt->execute()) {
			//                 	echo "successfully";
			// } else {
			//   echo var_dump($insertstmt->errorInfo());
			// }

			echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
		}
	} //qm end

	if ($usrtype == "admin") {

		if (isset($_GET['did'])) {

			$did = $_GET['did'];
			$didr = 0;

			$sql = "UPDATE rating_info set rating_action=:rating_action WHERE ideaid=:ideaid AND adminid=:adminid";



			$insertstmt = $conn->prepare($sql);

			$insertstmt->bindParam(":ideaid", $ideaid);
			$insertstmt->bindParam(":adminid", $adminid);
			// $insertstmt->bindParam(":qamid", $qmid);
			// $insertstmt->bindParam(":qacid", $qcid);
			// $insertstmt->bindParam(":staffid", $staffid);
			$insertstmt->bindParam(":rating_action", $didr);
			$insertstmt->execute();
			// if ($insertstmt->execute()) {
			//                         	echo "dislike successfully";

			//         } else {
			//           echo var_dump($insertstmt->errorInfo());
			//                   }
			echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
		}
		if (isset($_GET['lid'])) {
			$lid = $_GET['lid'];
			$lidr = 1;
			$sql = "UPDATE rating_info set rating_action=:rating_action WHERE ideaid=:ideaid AND adminid=:adminid";

			$insertstmt = $conn->prepare($sql);
			$insertstmt->bindParam(":ideaid", $ideaid);
			$insertstmt->bindParam(":adminid", $adminid);
			// $insertstmt->bindParam(":qamid", $qmid);
			// $insertstmt->bindParam(":qacid", $qcid);
			// $insertstmt->bindParam(":staffId", $staffid);
			$insertstmt->bindParam(":rating_action", $lidr);

			$insertstmt->execute();


			//                 if ($insertstmt->execute()) {
			//                 	echo "successfully";
			// } else {
			//   echo var_dump($insertstmt->errorInfo());
			// }

			echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
		}
	} //admin end


	if ($usrtype == "qc") {

		if (isset($_GET['did'])) {

			$did = $_GET['did'];
			$didr = 0;

			$sql = "UPDATE rating_info set rating_action=:rating_action WHERE ideaid=:ideaid AND qacid=:qacid";



			$insertstmt = $conn->prepare($sql);

			$insertstmt->bindParam(":ideaid", $ideaid);
			// $insertstmt->bindParam(":adminid", $adminid);
			// $insertstmt->bindParam(":qamid", $qmid);
			$insertstmt->bindParam(":qacid", $qcid);
			// $insertstmt->bindParam(":staffid", $staffid);
			$insertstmt->bindParam(":rating_action", $didr);
			$insertstmt->execute();
			// if ($insertstmt->execute()) {
			//                         	echo "dislike successfully";

			//         } else {
			//           echo var_dump($insertstmt->errorInfo());
			//                   }
			echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
		}
		if (isset($_GET['lid'])) {
			$lid = $_GET['lid'];
			$lidr = 1;
			$sql = "UPDATE rating_info set rating_action=:rating_action WHERE ideaid=:ideaid AND qacid=:qacid";

			$insertstmt = $conn->prepare($sql);
			$insertstmt->bindParam(":ideaid", $ideaid);
			// $insertstmt->bindParam(":adminid", $adminid);
			// $insertstmt->bindParam(":qamid", $qmid);
			$insertstmt->bindParam(":qacid", $qcid);
			// $insertstmt->bindParam(":staffId", $staffid);
			$insertstmt->bindParam(":rating_action", $lidr);

			$insertstmt->execute();


			//                 if ($insertstmt->execute()) {
			//                 	echo "successfully";
			// } else {
			//   echo var_dump($insertstmt->errorInfo());
			// }

			echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
		}
	} //qac end
	if ($usrtype == "staff") {

		if (isset($_GET['did'])) {

			$did = $_GET['did'];
			$didr = 0;

			$sql = "UPDATE rating_info set rating_action=:rating_action WHERE ideaid=:ideaid AND staffid=:staffid";



			$insertstmt = $conn->prepare($sql);

			$insertstmt->bindParam(":ideaid", $ideaid);
			// $insertstmt->bindParam(":adminid", $adminid);
			// $insertstmt->bindParam(":qamid", $qmid);
			// $insertstmt->bindParam(":qacid", $qcid);
			$insertstmt->bindParam(":staffid", $staffid);
			$insertstmt->bindParam(":rating_action", $didr);
			$insertstmt->execute();
			// if ($insertstmt->execute()) {
			//                         	echo "dislike successfully";

			//         } else {
			//           echo var_dump($insertstmt->errorInfo());
			//                   }
			echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
		}
		if (isset($_GET['lid'])) {
			$lid = $_GET['lid'];
			$lidr = 1;
			$sql = "UPDATE rating_info set rating_action=:rating_action WHERE ideaid=:ideaid AND staffid=:staffid";

			$insertstmt = $conn->prepare($sql);
			$insertstmt->bindParam(":ideaid", $ideaid);
			// $insertstmt->bindParam(":adminid", $adminid);
			// $insertstmt->bindParam(":qamid", $qmid);
			// $insertstmt->bindParam(":qacid", $qcid);
			$insertstmt->bindParam(":staffid", $staffid);
			$insertstmt->bindParam(":rating_action", $lidr);

			$insertstmt->execute();

			//                 if ($insertstmt->execute()) {
			//                 	echo "successfully";
			// } else {
			//   echo var_dump($insertstmt->errorInfo());
			// }

			echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
		}
	} //staff end

} else if ($condition != "exit") {

	if (isset($_GET['did'])) {
		$did = $_GET['did'];
		$didr = 0;

		$sql = "INSERT INTO rating_info (ideaid,adminId,qamId,qacId,staffId,rating_action) values (:ideaid,:adminid,:qamid,:qacid,:staffId,:rating_action)";
		$insertstmt = $conn->prepare($sql);
		$insertstmt->bindParam(":ideaid", $ideaid);
		$insertstmt->bindParam(":adminid", $adminid);
		$insertstmt->bindParam(":qamid", $qmid);
		$insertstmt->bindParam(":qacid", $qcid);
		$insertstmt->bindParam(":staffId", $staffid);
		$insertstmt->bindParam(":rating_action", $didr);
		$insertstmt->execute();

		// echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
		// if ($insertstmt->execute()) {
		//                 	echo "successfully";
		// } else {
		//   echo var_dump($insertstmt->errorInfo());
		// }



	}
	if (isset($_GET['lid'])) {
		$lid = $_GET['lid'];
		$lidr = 1;
		$sql = "INSERT INTO rating_info (ideaid,adminId,qamId,qacId,staffId,rating_action) values (:ideaid,:adminid,:qamid,:qacid,:staffId,:rating_action)";
		$insertstmt = $conn->prepare($sql);
		$insertstmt->bindParam(":ideaid", $ideaid);
		$insertstmt->bindParam(":adminid", $adminid);
		$insertstmt->bindParam(":qamid", $qmid);
		$insertstmt->bindParam(":qacid", $qcid);
		$insertstmt->bindParam(":staffId", $staffid);
		$insertstmt->bindParam(":rating_action", $lidr);

		$insertstmt->execute();


		//                 if ($insertstmt->execute()) {
		//                 	echo "successfully";
		// } else {
		//   echo var_dump($insertstmt->errorInfo());
		// }

		echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaId'</script>";
	}
}

//like unlike count
$likes = 1;




$like_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
$counts = $conn->prepare($like_count);
$counts->bindParam(":ideaid", $ideaid);
$counts->bindParam(":rating_action", $likes);
$counts->execute();
$like_counts = $counts->rowCount();

$unlikes = 0;
$unlike_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
$counts = $conn->prepare($unlike_count);
$counts->bindParam(":ideaid", $ideaid);
$counts->bindParam(":rating_action", $unlikes);
$counts->execute();
$unlike_counts = $counts->rowCount();
// var_dump("Likes = ",$like_counts);
// var_dump("UnLikes = ",$unlike_counts);

//like_unlike function end

if (isset($_POST['btnSubmit'])) {

	$ideaid = $_POST['tfHidden'];

	$comment = $_POST['comment'];
	// var_dump("IdeaID = ",$ideaid);
	// die();

	$sql = "INSERT INTO comment (comment,ideaId,adminId,qamId,qacId,staffId) values (:comment,:ideaid,:adminid,:qamid,:qacid,:staffid)";
	$insertstmt = $conn->prepare($sql);

	$insertstmt->bindParam(":comment", $comment);
	$insertstmt->bindParam(":ideaid", $ideaid);
	$insertstmt->bindParam(":adminid", $adminid);
	$insertstmt->bindParam(":qamid", $qmid);
	$insertstmt->bindParam(":qacid", $qcid);
	$insertstmt->bindParam(":staffid", $staffid);
	$insertstmt->execute();




	//               if ($insertstmt->execute()) {
	//                 	echo "comment successfully";

	// } else {
	//   echo var_dump($insertstmt->errorInfo());
	//   die();
	// }

	//Comment mail notification
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

	$ideaOwnerEmail=$authoremail;
	$commentWriter=$usrname;
	if($usrtype=="admin"){
		$commentWriterType="Admin";
	}else if($usrtype=="qm"){
		$commentWriterType="QA Manager";
	}else if($usrtype=="qc"){
		$commentWriterType="QA Coordinator";
	}else if($usrtype=="staff"){
		$commentWriterType="Staff";
	}else{
		$commentWriterType="";
	}
	

	$mail->addAddress($ideaOwnerEmail, "Author");
	$mail->Subject = 'Comment Notification';
	$mail->Body = "$commentWriter ($commentWriterType) commented on your post \r\n :: $comment ";
	$mail->send();
	
	


	echo "<script>location='like_unlike.php?ideaIdToDetail=$ideaid'</script>";
}

$comment_count = "SELECT * FROM comment WHERE ideaid=:ideaid ";
$comment_counts = $conn->prepare($comment_count);
$comment_counts->bindParam(":ideaid", $ideaid);

$comment_counts->execute();


$total_comment = $comment_counts->rowCount();

?>
<section class="page-title" style="background-image: url('images/bg/hat.png') !important; background-size: cover; background-repeat: no-repeat;">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="block text-center">
					<span class="text-white"></span>
					<h1 class="text-capitalize mb-5 text-lg">Idea Detail</h1>

					<!-- <ul class="list-inline breadcumb-nav">
            <li class="list-inline-item"><a href="index.html" class="text-white">Home</a></li>
            <li class="list-inline-item"><span class="text-white">/</span></li>
            <li class="list-inline-item"><a href="#" class="text-white-50">News details</a></li>
          </ul> -->
				</div>
			</div>
		</div>
	</div>
</section>
<section class="section blog-wrap">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="row">
					<div class="col-lg-12 mb-5">
						<div class="single-blog-item">


							<!-- My Info -->

							<div class="blog-item-content mt-5">
								<div class="blog-item-meta mb-3">
									<span class="text-muted text-capitalize mr-3"><i class="icofont-comment mr-2"></i> <?php echo $total_comment; ?> Comments</span>
									<span class="text-black text-capitalize mr-3"><i class="icofont-thumbs-up mr-1"></i> <?php echo $like_counts; ?> Thumb Up</span>
									<span class="text-black text-capitalize mr-3"><i class="icofont-thumbs-down mr-1"></i> <?php echo $unlike_counts; ?> Thumb Down</span>
									<span class="text-black text-capitalize mr-3"><i class="icofont-calendar mr-2"></i> <?php echo $date; ?> </span>
									<span class="text-muted text-capitalize mr-3"><i class="icofont-clock-time mr-1"></i> <?php echo $time; ?> </span>
									<span class="text-color-2 text-capitalize mr-3"><i class="icofont-book-mark mr-2"></i> <?php echo $category; ?> </span>

								</div>

								<h3 class='mt-3 mb-3'>
									<!-- <img src='images/staff.webp' class='rounded-circle' style='width: 40px;'> -->
									<b><?php echo $authorname; ?></b>
								</h3>
								<?php
								if ($department != "") {
									echo "<p class='lead mb-4'>Department - $department</p>";
								}
								?>


								<p><?php echo $idea; ?></p>

								<br>
								<h4>Supported Documents/Files : </h4>
								<blockquote class="quote">
									<?php echo $file; ?> &nbsp; &nbsp; <a href="eachFileDownload.php?download=<?php echo 'Uploads/' . $file ?>&&ideaIdToDetail=<?php echo $ideaId; ?>"> Download</a><br>
								</blockquote>


								<!-- <p class="lead mb-4 font-weight-normal text-black">The same is true as we experience the emotional sensation of stress from our first instances of social rejection ridicule. We quickly learn to fear and thus automatically.</p>

								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste, rerum beatae repellat tenetur incidunt quisquam libero dolores laudantium. Nesciunt quis itaque quidem, voluptatem autem eos animi laborum iusto expedita sapiente.</p> -->

								<a href='like_unlike.php?lid&&ideaIdToDetail=<?php echo $ideaId; ?>' class="btn btn-light btn-outline-dark"><i class="icofont-thumbs-up"></i> </a>
								<span id="likeno"><?php echo $like_counts  ?> Likes</span>
								<a href='like_unlike.php?did&&ideaIdToDetail=<?php echo $ideaId; ?>' class="btn btn-light btn-outline-dark"> <i class="icofont-thumbs-down"></i>
								</a>
								<span id="dislikeno"><?php echo $unlike_counts  ?> Dislikes</span>
								<a href="comment_detail.php?ideaIdToComment=<?php echo $ideaId; ?>" class="btn btn-light btn-outline-dark" target="_blank"><span class="text-muted text-capitalize mr-3"><i class="icofont-comment mr-2"></i> <?php echo $total_comment; ?> Comments</span></a>
							</div>


							<!-- -------------- -->

						</div>
					</div>


					<!-- <div class="col-lg-12">
						<div class="comment-area mt-4 mb-5">
							<h4 class="mb-4">2 Comments on Healthy environment... </h4>
							<ul class="comment-tree list-unstyled">
								<li class="mb-5">
									<div class="comment-area-box">

										<div class="comment-info">
											<h5 class="mb-1">John</h5>
											<span>United Kingdom</span>
											<span class="date-comm">| Posted April 7, 2019</span>
										</div>

										<div class="comment-content mt-3">
											<p>Some consultants are employed indirectly by the client via a consultancy staffing company, a company that provides consultants on an agency basis. </p>
										</div>

									</div>
								</li>

							</ul>
						</div>
					</div> -->

					<!-- COMMENT ON THE POST -->

					<div class="col-lg-12">

						<!-- The data in the input name and email will be read only right? -->

						<form class="comment-form my-5" id="comment-form" method="post">
							<h4 class="mb-4">Write a comment</h4>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<input class="form-control" type="text" name="name" id="name" placeholder="Name:<?php echo $usrname;  ?>" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input class="form-control" type="text" name="mail" id="mail" placeholder="Email:<?php echo $usrEmail;  ?>" readonly>
									</div>
								</div>
							</div>


							<textarea class="form-control mb-4" name="comment" id="comment_tf" cols="30" rows="5" placeholder="Comment"></textarea>
							<input type="hidden" value="<?php echo $ideaid; ?>" name="tfHidden">
							<!-- <a href="like_unlike.php?submit&&ideaIdToDetail=" class="btn btn-main-2 btn-round-full" id="submit_contact">Send Message</a> -->
							<input type="submit" value="Send Message" name="btnSubmit">
						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>
<?php

include "footer.php";
?>