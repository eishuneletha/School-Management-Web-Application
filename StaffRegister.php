<?php

include('connect.php');
include('header_reg.php');

if (isset($_POST['enter'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $pwd_hashed = password_hash($password, PASSWORD_DEFAULT);

    $address = $_POST['address'];
    $status = 0;


    $check = $conn->prepare("Select staffEmail from staff where staffEmail=:email");
    $check->bindParam(':email', $email);
    $check->execute();

    if ($check->rowCount() > 0) {
        echo "<script>window.alert('Email already exist')</script>";
    } else {
        $insert = "INSERT INTO staff (staffName,staffPhone,staffEmail,staffPassword,staffAddress,status,deptId) 
                values (:name,:phone,:email,:password,:address,:status,:deptId)";
        $stmt = $conn->prepare($insert);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $pwd_hashed);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':deptId', $_POST['tfDepartment']);


        $run = $stmt->execute();

        if ($run) {
            // if(password_verify($password,  $pwd_hashed))

            echo "<script>window.alert('Register Successful')</script>";
        } else {
            echo "<script>window.alert('Something went wrong')</script>";
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
    <title>StaffRegister</title>
</head>

<body>


    <section class="page-title" style="background-image: url('images/bg/hat.png') !important; background-size: cover; background-repeat: no-repeat;">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block text-center">
                        <h1 class="text-capitalize mb-5 text-lg">Staff Register</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section class="contact-form-wrap section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-title text-center">
                        <h2 class="text-md mb-2">Register Here</h2>
                        <div class="divider mx-auto my-4"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <form method="post" action="StaffRegister.php">

                        <div class="row">
                            <div class="col-sm-3" style="color:black; text-align:center; margin-top:15px;">
                                Name <i class="fas fa-user"> </i> :
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <input name="name" type="text" class="form-control" placeholder="Enter your name" required>
                                </div>
                            </div>


                            <div class="col-sm-3" style="color:black; text-align:center; margin-top:15px;">
                                Phone <i class="fas fa-phone"></i> :
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <input name="phone" type="text" class="form-control" placeholder="Enter Your Phone Number" required>
                                </div>
                            </div>


                            <div class="col-sm-3" style="color:black; text-align:center; margin-top:15px;">
                                Email <i class="fas fa-envelope"></i> :
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <input name="email" type="email" class="form-control" placeholder="Enter Your Email Address" required>
                                </div>
                            </div>

                            <div class="col-sm-3" style="color:black; text-align:center; margin-top:15px;">
                                Password <i class="fas fa-key"></i> :
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <input name="password" type="password" class="form-control" placeholder="Enter Password" required>
                                </div>
                            </div>


                            <div class="col-sm-3" style="color:black; text-align:center; margin-top:15px;">
                                Address: <i class="fas fa-home"></i> :
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <textarea name="address" class="form-control" placeholder="Enter your address"></textarea>
                                </div>
                            </div>

                            <div class="col-sm-3" style="color:black; text-align:center; margin-top:15px;">
                                Department: <i class="fas fa-home"></i>
                            </div>
                            <div class="col-sm-9">
                                <div class="form-group">
                                    <select name="tfDepartment" id="" class="form-control" required>
                                        <option value="">None</option>
                                        <?php
                                        $selectdeptstmt = $conn->prepare("SELECT * from department");
                                        $selectdeptstmt->execute();
                                        $datalist = $selectdeptstmt->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($datalist as $data) {
                                            echo "<option value='" . $data['deptId'] . "'>" . $data['department'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div style="text-align:center; margin-top:30px">
                            <input type="submit" name="enter" class="btn btn-main btn-round-full" value="Register">
                        </div>


                    </form>

                    <center class="mt-4">
                        <a href="index.php" style="color:black; text-align:center; margin-top:30px; text-decoration: underline; ">Already have an account?</a>
                    </center>
                </div>
            </div>
        </div>
    </section>

</body>

</html>

<?php
include('footer.php');

?>