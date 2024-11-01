<?php
include "../connect.php";
include "header.php";

if (isset($_REQUEST['ideaIdToComment'])) {
    $ideaIdToComment = $_REQUEST['ideaIdToComment'];



    //get comment count
    $comment_count = "SELECT * FROM comment WHERE ideaid=:ideaid ";
    $comment_counts = $conn->prepare($comment_count);
    $comment_counts->bindParam(":ideaid", $ideaIdToComment);
    $comment_counts->execute();
    $total_comment = $comment_counts->rowCount();
    //---------------------
}
?>
<section class="section blog-wrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="comment-area mt-4 mb-5">
                    <h4 class="mb-4"><?php echo $total_comment; ?> Comments on this idea... </h4>

                    <div>

                        <?php
                        $selectcomment = $conn->prepare("SELECT * FROM comment WHERE ideaid=$ideaIdToComment");
                        $selectcomment->execute();
                        $dataofcommentall = $selectcomment->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($dataofcommentall as $data) {
                            $myadminId = $data['adminId'];
                            $myqamId = $data['qamId'];
                            $myqacId = $data['qacId'];
                            $mystaffId = $data['staffId'];

                            //pointer
                            $mycommentId = $data['commentId'];

                            // var_dump($myadminId);
                            // var_dump($myqamId);
                            // var_dump($myqacId);
                            // var_dump($mystaffId);
                            // echo ('<br>');
                            if ($data['adminId'] != 0) {

                                $selectcommentstmt = $conn->prepare("SELECT * from comment c,admin a where c.adminId=a.adminId and c.ideaId=$ideaIdToComment and c.adminId=$myadminId and c.commentId=$mycommentId");
                                $selectcommentstmt->execute();
                                $dataofcomment = $selectcommentstmt->fetch(PDO::FETCH_ASSOC);

                                $authorname = $dataofcomment['adminName'];
                                $comment = $dataofcomment['comment'];
                                echo "
                                            <div class='card mb-3 p-3'>
                                            <b><h4>$authorname</h4></b>
                                            <span class='text-muted'>Admin</span>
                                            <p>$comment</p>
                                        </div>
                                                ";
                            }
                            if ($data['qamId'] != 0) {

                                $selectcommentstmt = $conn->prepare("SELECT * from comment c,qam qm where c.qamId=qm.qamId and c.ideaId=$ideaIdToComment and c.qamId=$myqamId and c.commentId=$mycommentId");
                                $selectcommentstmt->execute();
                                $dataofcomment = $selectcommentstmt->fetch(PDO::FETCH_ASSOC);

                                $authorname = $dataofcomment['qamName'];
                                $comment = $dataofcomment['comment'];
                                echo "
                                            <div class='card mb-3 p-3'>
                                            <b><h4>$authorname</h4></b>
                                            <span class='text-muted'>QA Manager</span>
                                            <p>$comment</p>
                                        </div>
                                                ";
                            }
                            if ($data['qacId'] != 0) {

                                $selectcommentstmt = $conn->prepare("SELECT * from comment c,qac qc where c.qacId=qc.qacId and c.ideaId=$ideaIdToComment and c.qacId=$myqacId and c.commentId=$mycommentId");
                                $selectcommentstmt->execute();
                                $dataofcomment = $selectcommentstmt->fetch(PDO::FETCH_ASSOC);

                                $authorname = $dataofcomment['qacName'];
                                $comment = $dataofcomment['comment'];
                                echo "
                                            <div class='card mb-3 p-3'>
                                            <b><h4>$authorname</h4></b>
                                            <span class='text-muted'>QA Coordinator</span>
                                            <p>$comment</p>
                                        </div>
                                                ";
                            }
                            if ($data['staffId'] != 0) {

                                $selectcommentstmt = $conn->prepare("SELECT * from comment c,staff s where c.staffId=s.staffId and c.ideaId=$ideaIdToComment and c.staffId=$mystaffId and c.commentId=$mycommentId");
                                $selectcommentstmt->execute();
                                $dataofcomment = $selectcommentstmt->fetch(PDO::FETCH_ASSOC);

                                $authorname = $dataofcomment['staffName'];
                                $comment = $dataofcomment['comment'];
                                echo "
                                            <div class='card mb-3 p-3'>
                                            <b><h4>$authorname</h4></b>
                                            <span class='text-muted'>Staff</span>
                                            <p>$comment</p>
                                            </div>
                                                ";
                            }
                        }
                        ?>



                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
include "footer.php";
?>