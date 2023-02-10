<?php
include "db_conn.php";

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    if($password != $repassword){
        echo "<script type='text/javascript'>alert('Repassword does match!');</script>";
    } else {
        $sql = "SELECT * FROM `task_1` WHERE username ='$username' ";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if($row != 0) {
            echo "<script type='text/javascript'>alert('Username already exists!');</script>";
        } else {
            $password = md5($password); 
            $sql = "INSERT INTO `task_1`(`id`, `username`, `password`, `address`, `phone`) VALUES (NULL,'$username','$password','$address','$phone')";
            $result = mysqli_query($conn, $sql);
            if($result) {
                echo "<script type='text/javascript'>alert('Register success!');</script>";
                header("Location: login.php");
            } else {
                echo "Failed: " . mysqli_error($conn);
            }
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
    <title>Register</title>

    <link rel="icon" type="image/x-icon" href="https://scontent.fhan14-3.fna.fbcdn.net/v/t39.30808-6/300582505_457118323100327_5264492577895373431_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=qMYwYD25960AX9qi2s9&_nc_ht=scontent.fhan14-3.fna&oh=00_AfCjRuo9eOURQuGdCT0ZcrZBVaqyG0e9HjQUhKU42JAFbg&oe=63E767A5">
    
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    
    <!-- login container -->
    <section class="vh-100" style="background-color: #57050b;">
    <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-xl-9">

                <h1 class="text-white mb-4">Register</h1>
                <form action="register.php" method="post" id="form" onsubmit="return validate()">
                    <div class="card" style="border-radius: 15px;">
                    <div class="card-body">

                        <div class="row align-items-center pt-4 pb-3">
                            <div class="col-md-3 ps-5">
                                <h6 class="mb-0">Username</h6>
                            </div>
                            <div class="col-md-9 pe-5">
                                <input name="username" id="username" type="text" class="form-control form-control-lg" placeholder="Username (between 5 and 15 characters)"/>
                            </div>
                        </div>
                        <div class="row align-items-center py-3">
                            <div class="col-md-3 ps-5">
                                <h6 class="mb-0">Password</h6>
                            </div>
                            <div class="col-md-9 pe-5">
                                <input id="password" name="password" type="password" class="form-control form-control-lg" placeholder="Password (between 8 and 20 characters)" />
                            </div>
                        </div>

                        <div class="row align-items-center py-3">
                            <div class="col-md-3 ps-5">
                                <h6 class="mb-0">Repassword</h6>
                            </div>
                            <div class="col-md-9 pe-5">
                                <input name="repassword" type="password" class="form-control form-control-lg" placeholder="Repassword" />
                            </div>
                        </div>

                        <div class="row align-items-center pt-4 pb-3">
                            <div class="col-md-3 ps-5">
                                <h6 class="mb-0">Address</h6>
                            </div>
                            <div class="col-md-9 pe-5">
                                <input name="address" type="text" class="form-control form-control-lg" placeholder="Address"/>
                            </div>
                        </div>

                        <div class="row align-items-center pt-4 pb-3">
                            <div class="col-md-3 ps-5">
                                <h6 class="mb-0">Phone Number</h6>
                            </div>
                            <div class="col-md-9 pe-5">
                                <input name="phone" type="text" class="form-control form-control-lg" placeholder="Phone Number"/>
                            </div>
                        </div>

                        <!-- <div class="row align-items-center py-3">
                            <div class="col-md-3 ps-5">
                                <h6 class="mb-0">Upload Avatar</h6>
                            </div>
                            <div class="col-md-9 pe-5">
                                <input name="" class="form-control form-control-lg" id="formFileLg" name="fileUpload" type="file" />
                                <div class="small text-muted mt-2">Upload your Avatar or any other relevant file. Max file
                                size 50 MB</div>
                            </div>
                        </div> -->

                        <!-- <hr class="my-1" /> -->

                        <div class="px-5 my-4 d-flex justify-content-center">
                            <button type="submit" name="submit" class="btn btn-primary btn-lg btn-dark ">Register</button>
                        </div>

                        <hr class="my-1">

                        <div class="px-5 d-flex justify-content-center">
                            Already have an account?                    
                        </div>
                        <div class="d-flex justify-content-center mx-5 mb-4">
                            <a href="login.php" class="btn btn-primary btn-lg btn-dark m-2">Login</a>
                        </div>

                        <!-- validate submit -->
                        <script>
                            function validate() {
                                let username = document.getElementById("username").value;
                                let password = document.getElementById("password").value;
                                if(username.length > 5 && username.length < 15 && password.length > 8 && password.length < 20){
                                    return true;
                                } else {
                                    alert("Validation failed!");
                                    return false;
                                }
                            }
                        </script>
                    </div>
                    </div>
                </form>
                <div class="text-center p-3 text-white mt-5">
                    © 2023 Build by:
                    <a class="text-white" href="https://github.com/tu3n4nh">tu3n4nh</a>
                </div>
            </div>
        </div>
    </div>
    </section>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>