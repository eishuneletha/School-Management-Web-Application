<?php
include('../connect.php');
include "header.php";

?>
<style>
  .makeline {
    display: -webkit-box;
    overflow: hidden;
    text-overflow: ellipsis;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
  }
</style>

<section class="page-title " style="background-image: url('../images/bg/hat.png') !important; background-size: cover; background-repeat: no-repeat;">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Idea Showcase</span>
          <h1 class="text-capitalize mb-5 text-lg">Ideas</h1>

          <!-- <ul class="list-inline breadcumb-nav">
            <li class="list-inline-item"><a href="index.html" class="text-white">Home</a></li>
            <li class="list-inline-item"><span class="text-white">/</span></li>
            <li class="list-inline-item"><a href="#" class="text-white-50">Our blog</a></li>
          </ul> -->
        </div>
      </div>
    </div>
  </div>
</section>

<section class="section blog-wrap">
  <div class="container">
    <div class="row">
      <div class="col-lg-8">
        <div class="row">

          <!-- Loop posts -->
          <?php
          // pagination code
          $numperpage = 5;
          $countIdeas = $conn->prepare("SELECT COUNT(ideaId) from idea");
          $countIdeas->execute();
          $ideaRow = $countIdeas->fetch();
          $numIdeas = $ideaRow[0];
          $numPage = ceil($numIdeas / $numperpage);
          if (isset($_GET['start'])) {
            $page = $_GET['start'];
          } else {
            $page = 0;
          }

          if (!$page)
            $page = 0;
          $start = $page * $numperpage;



          $selectideastmt = $conn->prepare("SELECT * from idea order by uploadDate desc, uploadTime desc limit  $start, $numperpage");
          $selectideastmt->execute();
          $dataoffirststep = $selectideastmt->fetchAll(PDO::FETCH_ASSOC);
          foreach ($dataoffirststep as $datafirststep) {
            $adminId = $datafirststep['adminId'];
            $qamId = $datafirststep['qamId'];
            $qacId = $datafirststep['qacId'];
            $staffId = $datafirststep['staffId'];
            $ideaId = $datafirststep['ideaId'];
            //admin post
            if ($adminId != 0) {
              $selectadminpost = $conn->prepare("SELECT i.*,a.*,c.* from idea i,admin a,category c where i.adminId=a.adminId and i.categoryId=c.categoryId and a.adminId=$adminId and i.ideaId=$ideaId");
              $selectadminpost->execute();
              $dataofadminpost = $selectadminpost->fetch(PDO::FETCH_ASSOC);
              //get essential data
              $date = $dataofadminpost['uploadDate'];
              $time = $dataofadminpost['uploadTime'];

              $category = $dataofadminpost['category'];
              $idea = $dataofadminpost['idea'];
              $ideaId = $dataofadminpost['ideaId'];
              $anonymous = $dataofadminpost['anonymousStatus'];
              if ($anonymous == 1) {
                $authorname = "Anonymous Author";
              } else {
                $authorname = $dataofadminpost['adminName'];
              }

              //get comment count
              $comment_count = "SELECT * FROM comment WHERE ideaid=:ideaid";
              $comment_counts = $conn->prepare($comment_count);
              $comment_counts->bindParam(":ideaid", $ideaId);
              $comment_counts->execute();
              $total_comment = $comment_counts->rowCount();
              //----------------------------------
              //get like count
              $likes = 1;
              $like_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
              $counts = $conn->prepare($like_count);
              $counts->bindParam(":ideaid", $ideaId);
              $counts->bindParam(":rating_action", $likes);
              $counts->execute();
              $like_counts = $counts->rowCount();
              //----------------------------------
              //get unlike count
              $unlikes = 0;
              $unlike_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
              $counts = $conn->prepare($unlike_count);
              $counts->bindParam(":ideaid", $ideaId);
              $counts->bindParam(":rating_action", $unlikes);
              $counts->execute();
              $unlike_counts = $counts->rowCount();
              //----------------------------------

              echo "
              <div class='col-lg-12 col-md-12 mb-5 bg-gray'>
                <div class='blog-item'>
                  <div class='blog-item-content'>
                    <!-- Status of comments likes etc -->
                    <div class='blog-item-meta mb-3 mt-4'>
                      <span class='text-muted text-capitalize mr-3'><i class='icofont-calendar mr-2'></i> $date</span>
                      <span class='text-muted text-capitalize mr-3'><i class='icofont-clock-time mr-1'></i> $time</span>
                    </div>
                    <!-- Direct link and intro content -->
                    <h4 class='mt-3 mb-3'>
                        <img src='../images/admin.webp' class='rounded-circle' style='width: 40px;'> &nbsp; <b>$authorname</b></h4>
                        <span class='text-color-2 text-capitalize mr-3'><i class='icofont-book-mark mr-2'></i> Category - $category</span>
                        <span class='text-color-2 text-capitalize mr-3'><i class='fas fa-address-card mx-2'></i> Role - Admin</span>
                    <p class='mb-4 makeline'>$idea</p>
                    <a href='like_unlike.php?ideaIdToDetail=$ideaId' target='_blank' class='btn btn-main btn-icon btn-round-full'>Read More <i class='icofont-simple-right ml-2'></i></a>
                    <div class='blog-item-meta mb-3 mt-4'>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-comment mr-1'></i> $total_comment Comments</span>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-thumbs-up mr-1'></i> $like_counts Thumbs Up</span>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-thumbs-down mr-1'></i> $unlike_counts Thumbs Down</span>
                    </div>
                  </div>
                </div>
              </div>
              ";
            }

            //qam post
            if ($qamId != 0) {
              $selectqampost = $conn->prepare("SELECT i.*,qm.*,c.* from idea i,qam qm,category c where i.qamId=qm.qamId and i.categoryId=c.categoryId and qm.qamId=$qamId and i.ideaId=$ideaId");
              $selectqampost->execute();
              $dataofqampost = $selectqampost->fetch(PDO::FETCH_ASSOC);
              //get essential data
              $date = $dataofqampost['uploadDate'];
              $time = $dataofqampost['uploadTime'];

              $category = $dataofqampost['category'];
              $idea = $dataofqampost['idea'];
              $ideaId = $dataofqampost['ideaId'];
              $anonymous = $dataofqampost['anonymousStatus'];
              if ($anonymous == 1) {
                $authorname = "Anonymous Author";
              } else {
                $authorname = $dataofqampost['qamName'];
              }

              //get comment count
              $comment_count = "SELECT * FROM comment WHERE ideaid=:ideaid";
              $comment_counts = $conn->prepare($comment_count);
              $comment_counts->bindParam(":ideaid", $ideaId);
              $comment_counts->execute();
              $total_comment = $comment_counts->rowCount();
              //----------------------------------
              //get like count
              $likes = 1;
              $like_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
              $counts = $conn->prepare($like_count);
              $counts->bindParam(":ideaid", $ideaId);
              $counts->bindParam(":rating_action", $likes);
              $counts->execute();
              $like_counts = $counts->rowCount();
              //----------------------------------
              //get unlike count
              $unlikes = 0;
              $unlike_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
              $counts = $conn->prepare($unlike_count);
              $counts->bindParam(":ideaid", $ideaId);
              $counts->bindParam(":rating_action", $unlikes);
              $counts->execute();
              $unlike_counts = $counts->rowCount();
              //----------------------------------

              echo "
              <div class='col-lg-12 col-md-12 mb-5 bg-gray'>
                <div class='blog-item'>
                  <div class='blog-item-content'>
                    <!-- Status of comments likes etc -->
                    <div class='blog-item-meta mb-3 mt-4'>
                      <span class='text-muted text-capitalize mr-3'><i class='icofont-calendar mr-2'></i> $date</span>
                      <span class='text-muted text-capitalize mr-3'><i class='icofont-clock-time mr-1'></i> $time</span>
                    </div>
                    <!-- Direct link and intro content -->
                    <h4 class='mt-3 mb-3'>
                        <img src='../images/manager.webp' class='rounded-circle' style='width: 40px;'> &nbsp; <b>$authorname</b></h4>
                        <span class='text-color-2 text-capitalize mr-3'><i class='icofont-book-mark mr-2'></i> Category - $category</span>
                        <span class='text-color-2 text-capitalize mr-3'><i class='fas fa-address-card mx-2'></i> Role - QA Manager</span>
                    <p class='mb-4 makeline'>$idea</p>
                    <a href='like_unlike.php?ideaIdToDetail=$ideaId' target='_blank' class='btn btn-main btn-icon btn-round-full'>Read More <i class='icofont-simple-right ml-2'></i></a>
                    <div class='blog-item-meta mb-3 mt-4'>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-comment mr-1'></i> $total_comment Comments</span>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-thumbs-up mr-1'></i> $like_counts Thumbs Up</span>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-thumbs-down mr-1'></i> $unlike_counts Thumbs Down</span>
                    </div>
                  </div>
                </div>
              </div>
              ";
            }

            //qac post
            if ($qacId != 0) {
              $selectqacpost = $conn->prepare("SELECT i.*,qc.*,c.* from idea i,qac qc,category c where i.qacId=qc.qacId and i.categoryId=c.categoryId and qc.qacId=$qacId and i.ideaId=$ideaId");
              $selectqacpost->execute();
              $dataofqacpost = $selectqacpost->fetch(PDO::FETCH_ASSOC);
              //get essential data
              $date = $dataofqacpost['uploadDate'];
              $time = $dataofqacpost['uploadTime'];

              $category = $dataofqacpost['category'];
              $idea = $dataofqacpost['idea'];
              $ideaId = $dataofqacpost['ideaId'];
              $anonymous = $dataofqacpost['anonymousStatus'];
              if ($anonymous == 1) {
                $authorname = "Anonymous Author";
              } else {
                $authorname = $dataofqacpost['qacName'];
              }

              //get comment count
              $comment_count = "SELECT * FROM comment WHERE ideaid=:ideaid";
              $comment_counts = $conn->prepare($comment_count);
              $comment_counts->bindParam(":ideaid", $ideaId);
              $comment_counts->execute();
              $total_comment = $comment_counts->rowCount();
              //----------------------------------
              //get like count
              $likes = 1;
              $like_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
              $counts = $conn->prepare($like_count);
              $counts->bindParam(":ideaid", $ideaId);
              $counts->bindParam(":rating_action", $likes);
              $counts->execute();
              $like_counts = $counts->rowCount();
              //----------------------------------
              //get unlike count
              $unlikes = 0;
              $unlike_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
              $counts = $conn->prepare($unlike_count);
              $counts->bindParam(":ideaid", $ideaId);
              $counts->bindParam(":rating_action", $unlikes);
              $counts->execute();
              $unlike_counts = $counts->rowCount();
              //----------------------------------

              echo "
              <div class='col-lg-12 col-md-12 mb-5 bg-gray'>
                <div class='blog-item'>
                  <div class='blog-item-content'>
                    <!-- Status of comments likes etc -->
                    <div class='blog-item-meta mb-3 mt-4'>
                      <span class='text-muted text-capitalize mr-3'><i class='icofont-calendar mr-2'></i> $date</span>
                      <span class='text-muted text-capitalize mr-3'><i class='icofont-clock-time mr-1'></i> $time</span>
                    </div>
                    <!-- Direct link and intro content -->
                    <h4 class='mt-3 mb-3'>
                        <img src='../images/qac.webp' class='rounded-circle' style='width: 40px;'> &nbsp; <b>$authorname</b></h4>
                        <span class='text-color-2 text-capitalize mr-3'><i class='icofont-book-mark mr-2'></i> Category - $category</span>
                        <span class='text-color-2 text-capitalize mr-3'><i class='fas fa-address-card mx-2'></i> Role - QA Coordinator</span>
                    <p class='mb-4 makeline'>$idea</p>
                    <a href='like_unlike.php?ideaIdToDetail=$ideaId' target='_blank' class='btn btn-main btn-icon btn-round-full'>Read More <i class='icofont-simple-right ml-2'></i></a>
                    <div class='blog-item-meta mb-3 mt-4'>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-comment mr-1'></i> $total_comment Comments</span>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-thumbs-up mr-1'></i> $like_counts Thumbs Up</span>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-thumbs-down mr-1'></i> $unlike_counts Thumbs Down</span>
                    </div>
                  </div>
                </div>
              </div>
              ";
            }

            //staff post
            if ($staffId != 0) {
              $selectstaffpost = $conn->prepare("SELECT i.*,s.*,c.* from idea i,staff s,category c where i.staffId=s.staffId and i.categoryId=c.categoryId and s.staffId=$staffId and i.ideaId=$ideaId");
              $selectstaffpost->execute();
              $dataofstaffpost = $selectstaffpost->fetch(PDO::FETCH_ASSOC);
              //get essential data
              $date = $dataofstaffpost['uploadDate'];
              $time = $dataofstaffpost['uploadTime'];

              $category = $dataofstaffpost['category'];
              $idea = $dataofstaffpost['idea'];
              $ideaId = $dataofstaffpost['ideaId'];
              $anonymous = $dataofstaffpost['anonymousStatus'];
              if ($anonymous == 1) {
                $authorname = "Anonymous Author";
              } else {
                $authorname = $dataofstaffpost['staffName'];
              }

              //get comment count
              $comment_count = "SELECT * FROM comment WHERE ideaid=:ideaid";
              $comment_counts = $conn->prepare($comment_count);
              $comment_counts->bindParam(":ideaid", $ideaId);
              $comment_counts->execute();
              $total_comment = $comment_counts->rowCount();
              //----------------------------------
              //get like count
              $likes = 1;
              $like_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
              $counts = $conn->prepare($like_count);
              $counts->bindParam(":ideaid", $ideaId);
              $counts->bindParam(":rating_action", $likes);
              $counts->execute();
              $like_counts = $counts->rowCount();
              //----------------------------------
              //get unlike count
              $unlikes = 0;
              $unlike_count = "SELECT * FROM rating_info WHERE ideaid=:ideaid AND rating_action=:rating_action";
              $counts = $conn->prepare($unlike_count);
              $counts->bindParam(":ideaid", $ideaId);
              $counts->bindParam(":rating_action", $unlikes);
              $counts->execute();
              $unlike_counts = $counts->rowCount();
              //----------------------------------

              echo "
              <div class='col-lg-12 col-md-12 mb-5 bg-gray'>
                <div class='blog-item'>
                  <div class='blog-item-content'>
                    <!-- Status of comments likes etc -->
                    <div class='blog-item-meta mb-3 mt-4'>
                      <span class='text-muted text-capitalize mr-3'><i class='icofont-calendar mr-2'></i> $date</span>
                      <span class='text-muted text-capitalize mr-3'><i class='icofont-clock-time mr-1'></i> $time</span>
                    </div>
                    <!-- Direct link and intro content -->
                    <h4 class='mt-3 mb-3'>
                        <img src='../images/staff.webp' class='rounded-circle' style='width: 40px;'> &nbsp; <b>$authorname</b></h4>
                        <span class='text-color-2 text-capitalize mr-3'><i class='icofont-book-mark mr-2'></i> Category - $category</span>
                        <span class='text-color-2 text-capitalize mr-3'><i class='fas fa-address-card mx-2'></i> Role - Staff</span>
                    <p class='mb-4 makeline'>$idea</p>
                    <a href='like_unlike.php?ideaIdToDetail=$ideaId' target='_blank' class='btn btn-main btn-icon btn-round-full'>Read More <i class='icofont-simple-right ml-2'></i></a>
                    <div class='blog-item-meta mb-3 mt-4'>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-comment mr-1'></i> $total_comment Comments</span>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-thumbs-up mr-1'></i> $like_counts Thumbs Up</span>
                      <span class='text-black text-capitalize mr-3'><i class='icofont-thumbs-down mr-1'></i> $unlike_counts Thumbs Down</span>
                    </div>
                  </div>
                </div>
              </div>
              ";
            }
          }

          ?>



        </div>
      </div>

      <div class="col-lg-4">
        <div class="sidebar-wrap pl-lg-4 mt-5 mt-lg-0">
          <!-- Categories (linked to category table) -->

          <div class="sidebar-widget category mb-3">
            <h5 class="mb-4">Categories</h5>
            <ul class="list-unstyled">
              <li class="align-items-center">
                <?php
                $selectcat = $conn->prepare("SELECT * from category");
                $selectcat->execute();
                $dataofcat = $selectcat->fetchAll(PDO::FETCH_ASSOC);
                foreach ($dataofcat as $data) {
                  $category = $data['category'];
                  echo "<a href=''>$category</a><span></span><br>";
                }
                ?>
                <!-- <span>(14)</span> -->
              </li>

            </ul>
          </div>

        </div>
      </div>

    </div>

    <!-- Childeren of first row -->

    <?php
    // pagination continue


    ?>

    <div class="row mt-5">
      <div class="col-lg-8">
        <nav class="pagination py-2 d-inline-block">
          <div class="nav-links">

            <?php
            // pagination continue

            $prevPage = $page - 1;

            echo '
            <a class="page-numbers" href="ideaShowcase.php?start=' . $prevPage . '"><i class="icofont-thin-double-left"></i></a>
            ';

            for ($i = 0; $i < $numPage; $i++) {
              $y = $i + 1;
              echo '
                <a class="page-numbers" href="ideaShowcase.php?start=' . $i . '">' . $y . '</a>';
            }
            $nextPage = $page + 1;
            echo '<a class="page-numbers" href="ideaShowcase.php?start=' . $nextPage . '"><i class="icofont-thin-double-right"></i></a>';
            ?>
          </div>
        </nav>
      </div>
    </div>
  </div>

</section>

<?php
include "footer.php";
?>