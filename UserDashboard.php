<?php
include('connect.php');
include('header.php');
//Who are you?
$type = "";
if ($authortype == "qam") {
    $selectstmt = $conn->prepare("SELECT * from qam where qamId=$authorid");
    $selectstmt->execute();
    $qamData = $selectstmt->fetch();
    $name = $qamData['qamName'];
    $email = $qamData['qamEmail'];
    $type = "QA Manager";
}
if ($authortype == "qac") {
    $selectstmt = $conn->prepare("SELECT * from qac where qacId=$authorid");
    $selectstmt->execute();
    $qamData = $selectstmt->fetch();
    $name = $qamData['qacName'];
    $email = $qamData['qacEmail'];
    $type = "QA Coordinator";
}
if ($authortype == "staff") {
    $selectstmt = $conn->prepare("SELECT * from staff where staffId=$authorid");
    $selectstmt->execute();
    $qamData = $selectstmt->fetch();
    $name = $qamData['staffName'];
    $email = $qamData['staffEmail'];
    $type = "Staff";
}

//Analyze Popular Ideas
$numberOfPopular = 3;
// $three_popu_arr = array();
// $all_popu_count = array();
// $three_popu_id=array();
// $selectpopu = $conn->prepare("SELECT ideaId from idea");
// $selectpopu->execute();
// $dataofpopu = $selectpopu->fetchAll(PDO::FETCH_ASSOC);
// foreach ($dataofpopu as $dataofpopu_each) {
//     $ideaIdEach = $dataofpopu_each['ideaId'];
//     $selecteachthumbup = $conn->prepare("SELECT COUNT(*) as countoftu FROM rating_info WHERE rating_action=1 AND ideaId=$ideaIdEach");
//     $selecteachthumbup->execute();
//     $data_thumbup_each = $selecteachthumbup->fetch(PDO::FETCH_ASSOC);
//     $thumbup_each = $data_thumbup_each['countoftu'];
//     array_push($all_popu_count,$thumbup_each);
    

//     arsort($all_popu_count);
//     $three_popu_arr = array_slice($all_popu_count, 0, 3);
//     // echo $thumbup_each."<br>";
// }

//Analyze Anonymous Ideas
$selectanony = $conn->prepare("SELECT count(ideaId) from idea where anonymousStatus=1");
$selectanony->execute();
$anonyData = $selectanony->fetch();
$numberOfAnony = $anonyData[0];

//Analyze inactive ideas
$numberOfInactive = 0;
$arrOfActive = array();
$selectactive = $conn->prepare("SELECT i.ideaId from idea i,comment c where i.ideaId=c.ideaId");
$selectactive->execute();
$activeData = $selectactive->fetchAll();
foreach ($activeData as $active) {
    array_push($arrOfActive, $active[0]);
}
if (sizeof($arrOfActive) == 0) {
    $selectunactive = "SELECT count(*) from idea";
} else {
    $selectunactive = "SELECT count(*) from idea where";
    $count = 0;
    foreach ($arrOfActive as $activedata) {
        if ($count == 0) {
            $selectunactive .= " ideaId!=$activedata";
            $count = 1;
        } else {
            $selectunactive .= " and ideaId!=$activedata";
        }
    }
}
$selectunactivestmt = $conn->prepare($selectunactive);
$selectunactivestmt->execute();
$unactiveData = $selectunactivestmt->fetch();
$numberOfInactive = $unactiveData[0];

//Analysis of total idea
$selecttotal = $conn->prepare("SELECT count(ideaId) from idea");
$selecttotal->execute();
$totalData = $selecttotal->fetch();
$numberOfTotal = $totalData[0];
?>
<style>
    .ideaStyle {
        width: 20rem;
        display: -webkit-box;
        overflow: hidden;
        text-overflow: ellipsis;
        height: 2.5em;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
</style>
<script>
    setInterval(showTime, 1000);

    function showTime() {
        let time = new Date();
        let hour = time.getHours();
        var title_text = "";
        if (hour < 12) {
            title_text = "Good Morning, ";
        } else if (hour >= 12 && hour < 17) {
            title_text = "Good Afternoon, ";
        } else if (hour >= 17 && hour < 19) {
            title_text = "Good Evening, ";
        } else if (hour >= 19) {
            title_text = "Good Night, ";
        }
        document.getElementById("titleText").innerHTML = title_text + "<?php echo $name; ?>";
    }
    document.addEventListener("DOMContentLoaded", function(event) {
        showTime();
        var year = new Date().getFullYear();
        document.getElementById("closureTitle").innerHTML += "( " + year + " - " + (year + 1) + " )";
        document.getElementById("tabFourth").click();
    });

    function clickTab(index) {
        if (index == 1) {
            document.getElementById("tabFirst").click();
        } else if (index == 2) {
            document.getElementById("tabSecond").click();
        } else if (index == 3) {
            document.getElementById("tabThird").click();
        } else {
            document.getElementById("tabFourth").click();
        }
    }
</script>
<main style="margin-left: 2%; margin-right: 2%;" class="shadow-lg p-3 mb-5 bg-white rounded">
    <div class="container-fluid px-4">
        <h2 class="mt-4" id="titleText"></h2>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active text-dark">Login as : &nbsp;<b><?php echo $email; ?></b>, Account Type : &nbsp;<b><?php echo $type; ?></b></li>
        </ol>
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i>
                        Data of ideas per department
                    </div>
                    <div class="card-body"><canvas id="myBarChart" width="100%" height="100"></canvas></div>
                </div>
            </div>
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-1"></i>
                        Percentages of Ideas per Department
                    </div>
                    <div class="card-body"><canvas id="myPieChart" width="100%" height="30"></canvas></div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- <div class="col-xl-4 col-md-6" onclick="clickTab(1)">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <p>Popular Ideas</p>
                        <p style="font-size: 60px; font-weight: bold;" class="text-center"><?php echo $numberOfPopular; ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#tab_content_pane">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div> -->
            <div class="col-xl-6 col-md-6" onclick="clickTab(2)">
                <div class="card bg-secondary text-white mb-4">
                    <div class="card-body">
                        <p>Anonymous Ideas</p>
                        <p style="font-size: 60px; font-weight: bold;" class="text-center"><?php echo $numberOfAnony; ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#tab_content_pane">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6" onclick="clickTab(3)">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <p>Inactive Ideas</p>
                        <p style="font-size: 60px; font-weight: bold;" class="text-center"><?php echo $numberOfInactive; ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#tab_content_pane">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6" onclick="clickTab(4)">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <p>Total Idea</p>
                        <p style="font-size: 60px; font-weight: bold;" class="text-center"><?php echo $numberOfTotal; ?></p>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#tab_content_pane">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-md-6" onclick="clickTab(4)">
                <div class="card bg-info text-white mb-4">
                    <div class="card-body">
                        <p id="closureTitle">Configured Closure Info for academic year </p>
                        <a class="small text-white stretched-link" href="#tab_content_pane">
                            <p class="text-center">
                                <?php
                                $year = date('Y');
                                $selectclosure = $conn->prepare("SELECT * from closuredate where academicYear=$year");
                                $selectclosure->execute();
                                $dataofclosure = $selectclosure->fetch(PDO::FETCH_ASSOC);
                                $closure = $dataofclosure['closureDate'];
                                $finalclosure = $dataofclosure['finalClosureDate'];
                                ?>
                                Initial Closure Date - <span style="font-size: 25px; font-weight: bold;"><?php echo $closure; ?></span><br>
                                Final Closure Date - <span style="font-size: 25px; font-weight: bold;"><?php echo $finalclosure; ?></span>
                            </p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs" role="tablist" style="display: none;">
            <li class="nav-item">
                <a class="nav-link active" id="tabFirst" data-toggle="tab" href="#tabs-1" role="tab">First Panel</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabSecond" data-toggle="tab" href="#tabs-2" role="tab">Second Panel</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabThird" data-toggle="tab" href="#tabs-3" role="tab">Third Panel</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="tabFourth" data-toggle="tab" href="#tabs-4" role="tab">Third Panel</a>
            </li>
        </ul>
        <hr class="mb-5">
        <div class="tab-content" id="tab_content_pane">
            <!-- All About Tab Pane 1 -->
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                <h1>Popular Ideas</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-secondary">
                        <tr>
                            <th>No</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Idea</th>
                            <th>Date & Time</th>
                            <th>Thumb Up</th>
                            <th>Thumb Down</th>
                        </tr>
                        <?php
                        $count = 0;
                        $selectidea = $conn->prepare("SELECT * from idea i where thumbUp>50");
                        $selectidea->execute();
                        if ($selectidea->rowCount() > 0) {
                            $ideadata = $selectidea->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($ideadata as $data) {
                                $count++;
                                $adminId = $data['adminId'];
                                $qamId = $data['qamId'];
                                $qacId = $data['qacId'];
                                $staffId = $data['staffId'];
                                $ideaId = $data['ideaId'];
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
                                    $anonymous = $dataofadminpost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofadminpost['adminName'];
                                    }
                                    $thumbUp = $dataofadminpost['thumbUp'];
                                    $thumbDown = $dataofadminpost['thumbDown'];
                                    echo "
                                  <tr>
                                      <td>$count</td>
                                      <td>$authorname (Admin)</td>
                                      <td>$category</td>
                                      <td class='ideaStyle'>$idea</td>
                                      <td>$date - $time</td>
                                      <td>$thumbUp</td>
                                      <td>$thumbDown</td>
                                  </tr>
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
                                    $anonymous = $dataofqampost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofqampost['qamName'];
                                    }
                                    $thumbUp = $dataofqampost['thumbUp'];
                                    $thumbDown = $dataofqampost['thumbDown'];
                                    echo "
                                  <tr scope='col'>
                                      <td scope='row'>$count</td>
                                      <td scope='row'>$authorname (QA Manager)</td>
                                      <td scope='row'>$category</td>
                                      <td class='ideaStyle'>$idea</td>
                                      <td scope='row'>$date - $time</td>
                                      <td scope='row'>$thumbUp</td>
                                      <td scope='row'>$thumbDown</td>
                                  </tr>
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
                                    $anonymous = $dataofqacpost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofqacpost['qacName'];
                                    }
                                    $thumbUp = $dataofqacpost['thumbUp'];
                                    $thumbDown = $dataofqacpost['thumbDown'];
                                    echo "
                                  <tr>
                                      <td>$count</td>
                                      <td>$authorname (QA Coordinator)</td>
                                      <td>$category</td>
                                      <td class='ideaStyle'>$idea</td>
                                      <td>$date - $time</td>
                                      <td>$thumbUp</td>
                                      <td>$thumbDown</td>
                                  </tr>
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
                                    $anonymous = $dataofstaffpost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofstaffpost['staffName'];
                                    }
                                    $thumbUp = $dataofstaffpost['thumbUp'];
                                    $thumbDown = $dataofstaffpost['thumbDown'];
                                    echo "
                                  <tr scope='col'>
                                      <td scope='row'>$count</td>
                                      <td scope='row'>$authorname (Staff)</td>
                                      <td scope='row'>$category</td>
                                      <td scope='row' class='ideaStyle'>$idea</td>
                                      <td scope='row'>$date - $time</td>
                                      <td scope='row'>$thumbUp</td>
                                      <td scope='row'>$thumbDown</td>
                                  </tr>
                                  ";
                                }
                            }
                        } else {
                            echo "<tr style='text-align:center;'><td colspan='7'>No data found!</td></tr>";
                        }
                        ?>
                    </table>
                </div>

            </div>
            <div class="tab-pane" id="tabs-2" role="tabpanel">
                <h1>Anonymous Ideas</h1>
                <div class="table-responsive">
                    <table class="table table-striped table-secondary">
                        <tr>
                            <th>No</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Idea</th>
                            <th>Date & Time</th>
                        </tr>
                        <?php
                        $count = 0;
                        $selectidea = $conn->prepare("SELECT * from idea where anonymousStatus=1");
                        $selectidea->execute();
                        if ($selectidea->rowCount() > 0) {
                            $ideadata = $selectidea->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($ideadata as $data) {
                                $count++;
                                $adminId = $data['adminId'];
                                $qamId = $data['qamId'];
                                $qacId = $data['qacId'];
                                $staffId = $data['staffId'];
                                $ideaId = $data['ideaId'];
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
                                    $anonymous = $dataofadminpost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofadminpost['adminName'];
                                    }
                                    $thumbUp = $dataofadminpost['thumbUp'];
                                    $thumbDown = $dataofadminpost['thumbDown'];
                                    echo "
                                <tr>
                                    <td>$count</td>
                                    <td>$authorname (Admin)</td>
                                    <td>$category</td>
                                    <td class='ideaStyle'>$idea</td>
                                    <td>$date - $time</td>
                                </tr>
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
                                    $anonymous = $dataofqampost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofqampost['qamName'];
                                    }
                                    $thumbUp = $dataofqampost['thumbUp'];
                                    $thumbDown = $dataofqampost['thumbDown'];
                                    echo "
                                <tr>
                                    <td>$count</td>
                                    <td>$authorname (QA Manager)</td>
                                    <td>$category</td>
                                    <td class='ideaStyle'>$idea</td>
                                    <td>$date - $time</td>
                                </tr>
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
                                    $anonymous = $dataofqacpost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofqacpost['qacName'];
                                    }
                                    $thumbUp = $dataofqacpost['thumbUp'];
                                    $thumbDown = $dataofqacpost['thumbDown'];
                                    echo "
                                <tr>
                                    <td>$count</td>
                                    <td>$authorname (QA Coordinator)</td>
                                    <td>$category</td>
                                    <td class='ideaStyle'>$idea</td>
                                    <td>$date - $time</td>
                                </tr>
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
                                    $anonymous = $dataofstaffpost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofstaffpost['staffName'];
                                    }
                                    $thumbUp = $dataofstaffpost['thumbUp'];
                                    $thumbDown = $dataofstaffpost['thumbDown'];
                                    echo "
                                <tr>
                                    <td>$count</td>
                                    <td>$authorname (Staff)</td>
                                    <td>$category</td>
                                    <td class='ideaStyle'>$idea</td>
                                    <td>$date - $time</td>
                                </tr>
                                ";
                                }
                            }
                        } else {
                            echo "<tr style='text-align:center;'><td colspan='7'>No data found!</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="tabs-3" role="tabpanel">
                <h1>Inactive Ideas</h1>
                <div class="table-responsive">


                    <table class="table table-striped table-secondary">
                        <tr>
                            <th>No</th>
                            <th>Author</th>
                            <th>Category</th>
                            <th>Idea</th>
                            <th>Date & Time</th>
                        </tr>
                        <?php
                        $no = 0;
                        $numberOfInactive = 0;
                        $arrOfActive = array();
                        $selectactive = $conn->prepare("SELECT i.ideaId from idea i,comment c where i.ideaId=c.ideaId");
                        $selectactive->execute();
                        $activeData = $selectactive->fetchAll();
                        foreach ($activeData as $active) {
                            array_push($arrOfActive, $active[0]);
                        }
                        if (sizeof($arrOfActive) == 0) {
                            $selectunactive = "SELECT * from idea";
                        } else {
                            $selectunactive = "SELECT * from idea where";
                            $count = 0;
                            foreach ($arrOfActive as $activedata) {
                                if ($count == 0) {
                                    $selectunactive .= " ideaId!=$activedata";
                                    $count = 1;
                                } else {
                                    $selectunactive .= " and ideaId!=$activedata";
                                }
                            }
                        }
                        $selectunactivestmt = $conn->prepare($selectunactive);
                        $selectunactivestmt->execute();
                        if ($selectunactivestmt->rowCount() > 0) {
                            $ideadata = $selectunactivestmt->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($ideadata as $data) {
                                $no++;
                                $adminId = $data['adminId'];
                                $qamId = $data['qamId'];
                                $qacId = $data['qacId'];
                                $staffId = $data['staffId'];
                                $ideaId = $data['ideaId'];
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
                                    $anonymous = $dataofadminpost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofadminpost['adminName'];
                                    }
                                    $thumbUp = $dataofadminpost['thumbUp'];
                                    $thumbDown = $dataofadminpost['thumbDown'];
                                    echo "
                                <tr>
                                    <td>$no</td>
                                    <td>$authorname (Admin)</td>
                                    <td>$category</td>
                                    <td class='ideaStyle'>$idea</td>
                                    <td>$date - $time</td>
                                </tr>
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
                                    $anonymous = $dataofqampost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofqampost['qamName'];
                                    }
                                    $thumbUp = $dataofqampost['thumbUp'];
                                    $thumbDown = $dataofqampost['thumbDown'];
                                    echo "
                                <tr>
                                    <td>$no</td>
                                    <td>$authorname (QA Manager)</td>
                                    <td>$category</td>
                                    <td class='ideaStyle'>$idea</td>
                                    <td>$date - $time</td>
                                </tr>
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
                                    $anonymous = $dataofqacpost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofqacpost['qacName'];
                                    }
                                    $thumbUp = $dataofqacpost['thumbUp'];
                                    $thumbDown = $dataofqacpost['thumbDown'];
                                    echo "
                                <tr>
                                    <td>$no</td>
                                    <td>$authorname (QA Coordinator)</td>
                                    <td>$category</td>
                                    <td class='ideaStyle'>$idea</td>
                                    <td>$date - $time</td>
                                </tr>
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
                                    $anonymous = $dataofstaffpost['anonymousStatus'];
                                    if ($anonymous == 1) {
                                        $authorname = "Anonymous Author";
                                    } else {
                                        $authorname = $dataofstaffpost['staffName'];
                                    }
                                    $thumbUp = $dataofstaffpost['thumbUp'];
                                    $thumbDown = $dataofstaffpost['thumbDown'];
                                    echo "
                                <tr>
                                    <td>$no</td>
                                    <td>$authorname (Staff)</td>
                                    <td>$category</td>
                                    <td class='ideaStyle'>$idea</td>
                                    <td>$date - $time</td>
                                </tr>
                                ";
                                }
                            }
                        } else {
                            echo "<tr style='text-align:center;'><td colspan='7'>No data found!</td></tr>";
                        }
                        ?>
                    </table>
                </div>
            </div>
            <div class="tab-pane" id="tabs-4" role="tabpanel">

            </div>
        </div>
    </div>
</main>
<hr>
<!-- Bar Chart Configure -->
<?php
$selectdept = $conn->prepare("select department from department d");
$selectdept->execute();
$dataofdept = $selectdept->fetchAll();
foreach ($dataofdept as $dept) {
    $department = $dept['department'];

    $selectdata = $conn->prepare("select count(*) from idea i, qac qc, department d
    where i.qacId=qc.qacId and qc.deptId=d.deptId and d.department='$department'");
    $selectdata->execute();
    $countqac = $selectdata->fetch();

    $selectdata = $conn->prepare("select count(*) from idea i, staff s, department d
    where i.staffId=s.staffId and s.deptId=d.deptId and d.department='$department'");
    $selectdata->execute();
    $countstaff = $selectdata->fetch();

    $numbers[] = $countqac[0] + $countstaff[0];
    $showDept[] = $department;
}

?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js" integrity="sha512-QSkVNOCYLtj73J4hbmVoOV6KVZuMluZlioC+trLpewV8qMjsWqlIQvkn1KGX2StWvPMdWGBqim1xlC8krl1EKQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js"></script>
<script>
    const ctx = document.getElementById('myBarChart').getContext('2d');
    const myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($showDept); ?>,
            datasets: [{
                label: 'Number of posts',
                data: <?php echo json_encode($numbers); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(98, 13, 192, 0.2)',
                    'rgba(19, 102, 255, 0.2)',
                    'rgba(29, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    ticks: {
                        autoSkip: false,
                        maxrotation: 90
                    }
                }]
            }
        }
    });
    const ctx2 = document.getElementById('myPieChart').getContext('2d');
    const myPieChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($showDept); ?>,
            datasets: [{
                data: <?php echo json_encode($numbers); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(98, 13, 192, 0.2)',
                    'rgba(19, 102, 255, 0.2)',
                    'rgba(29, 159, 64, 0.2)'
                ],
                hoverOffset: 4

            }]
        },
        options: {
            tooltips: {
                enabled: false
            },
            plugins: {
                datalabels: {
                    formatter: (value, ctx2) => {
                        let sum = 0;
                        let dataArr = ctx2.chart.data.datasets[0].data;
                        dataArr.map(data => {
                            sum += data;
                        });
                        let percentage = (value * 100 / sum).toFixed(1) + "%";
                        return percentage;
                    },
                    color: '#5584AC'
                }
            }
        },
        plugins: [ChartDataLabels]
    });
</script>

<?php
include('footer.php');
?>