<?php
include('../connect.php');
include "header.php";
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
		if ($anonymous == 1) {
			$authorname = "Anonymous Author";
		} else {
			$authorname = $dataofadminpost['adminName'];
		}
		$file=!empty($dataofadminpost['document']) ? $dataofadminpost['document'] : "No File Found";

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
		if ($anonymous == 1) {
			$authorname = "Anonymous Author";
		} else {
			$authorname = $dataofqampost['qamName'];
		}
		$file=!empty($dataofqampost['document']) ? $dataofqampost['document'] : "No File Found";
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
		if ($anonymous == 1) {
			$authorname = "Anonymous Author";
		} else {
			$authorname = $dataofqacpost['qacName'];
		}
		$file=!empty($dataofqacpost['document']) ? $dataofqacpost['document'] : "No File Found";
		$department=$dataofqacpost['department'];
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
		if ($anonymous == 1) {
			$authorname = "Anonymous Author";
		} else {
			$authorname = $dataofstaffpost['staffName'];
		}
		$file=!empty($dataofstaffpost['document']) ? $dataofstaffpost['document'] : "No File Found";
		$department=$dataofstaffpost['department'];
	}
} else {
	echo "<script>alert('Access Denied')</script>";
	echo "<script>location='ideaShowcase.php'</script>";
}
?>

<section class="page-title" style="background-image: url('../images/bg/hat.png') !important; background-size: cover; background-repeat: no-repeat;">
	<div class="overlay"></div>
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="block text-center">
					<span class="text-white">News details</span>
					<h1 class="text-capitalize mb-5 text-lg">Blog Single</h1>

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
							<div class="blog-item-content mt-5">
								<div class="blog-item-meta mb-3">
									<span class="text-muted text-capitalize mr-3"><i class="icofont-comment mr-2"></i> 0 Comments</span>
									<span class="text-black text-capitalize mr-3"><i class="icofont-like mr-1"></i> 0 Thumb Up</span>
									<span class="text-black text-capitalize mr-3"><i class="icofont-like mr-1"></i> 0 Thumb Down</span>
									<span class="text-black text-capitalize mr-3"><i class="icofont-calendar mr-2"></i> <?php echo $date; ?></span>
									<span class="text-muted text-capitalize mr-3"><i class="icofont-clock-time mr-1"></i> <?php echo $time; ?></span>
									<span class="text-color-2 text-capitalize mr-3"><i class="icofont-book-mark mr-2"></i> <?php echo $category; ?></span>

								</div>

								<h3 class='mt-3 mb-3'>
									<!-- <img src='images/staff.webp' class='rounded-circle' style='width: 40px;'> -->
									<b><?php echo $authorname; ?></b>
								</h3>
								<?php
								if ($department!="") {
									echo "<p class='lead mb-4'>Department - $department</p>";
								}
								?>
								

								<p><?php echo $idea; ?></p>

								<br>
								<h4>Supported Documents/Files : </h4>
								<blockquote class="quote">
								<?php echo $file; ?> &nbsp; &nbsp; <a href="eachFileDownload.php?download=<?php echo '../Uploads/'.$file ?>&&ideaIdToDetail=<?php echo $ideaId; ?>"> Download</a><br>
								</blockquote>


								<!-- <p class="lead mb-4 font-weight-normal text-black">The same is true as we experience the emotional sensation of stress from our first instances of social rejection ridicule. We quickly learn to fear and thus automatically.</p>

								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iste, rerum beatae repellat tenetur incidunt quisquam libero dolores laudantium. Nesciunt quis itaque quidem, voluptatem autem eos animi laborum iusto expedita sapiente.</p> -->

								<button type="button" name="like"><i class="icofont-thumbs-up"></i></button>
								<span id="likeno">5 Likes</span>

								<button type="button" name="dislike"><i class="icofont-thumbs-down"></i></button>
								<span id="dislikeno">0 Dislikes</span>

							</div>
						</div>
					</div>

					<div class="col-lg-12">
						<div class="comment-area mt-4 mb-5">
							<h4 class="mb-4">2 Comments on Healthy environment... </h4>
							<ul class="comment-tree list-unstyled">
								<li class="mb-5">
									<div class="comment-area-box">

										<!-- COMMENTS FROM DB WILL BE SHOWN HERE -->

										<!-- OUR STAFFS DO NOT HAVE PROFILE PIC -->

										<!-- <div class="comment-thumb float-left">
                							<img alt="" src="images/blog/testimonial1.jpg" class="img-fluid">
                						</div> -->

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
					</div>

					<!-- COMMENT ON THE POST -->

					<div class="col-lg-12">

						<!-- The data in the input name and email will be read only right? -->

						<form class="comment-form my-5" id="comment-form">
							<h4 class="mb-4">Write a comment</h4>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<input class="form-control" type="text" name="name" id="name" placeholder="Name:" readonly>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<input class="form-control" type="text" name="mail" id="mail" placeholder="Email:" readonly>
									</div>
								</div>
							</div>


							<textarea class="form-control mb-4" name="comment" id="comment" cols="30" rows="5" placeholder="Comment"></textarea>

							<input class="btn btn-main-2 btn-round-full" type="submit" name="submit-contact" id="submit_contact" value="Submit Message">
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