<?php 
    session_start();
    $_SESSION['username'] = $_GET['username'];

    include 'db_conn.php';
    if(isset($_POST['update'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
    
        $password = md5($password);
        $sql = "UPDATE `task_1` SET `password`='$password',`address`='$address',`phone`='$phone' WHERE username='$username' ";
        $result = mysqli_query($conn, $sql);
        if($result) {
            echo "<script type='text/javascript'>alert('Update success!');</script>";
            header("Location: index.php");
        } else {
            echo "Failed: " . mysqli_error($conn);
        }
        
    }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="icon" type="image/x-icon"
        href="https://scontent.fhan14-3.fna.fbcdn.net/v/t39.30808-6/300582505_457118323100327_5264492577895373431_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=qMYwYD25960AX9qi2s9&_nc_ht=scontent.fhan14-3.fna&oh=00_AfCjRuo9eOURQuGdCT0ZcrZBVaqyG0e9HjQUhKU42JAFbg&oe=63E767A5">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <nav 
        class="navbar navbar-light justify-content-center fs-3 text-white fw-bold" 
        style="background-color: #57050b"
        >
            Update User
    </nav>
    <section class="vh-100" style="background-color: #eee;">

        <?php
            $usrname=$_GET['username'];
            $sql = "SELECT * FROM `task_1` WHERE username = '$usrname'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_array($result);
        ?>
        <div class="container py-3">
                <div class="mt-5">
                    <div class="card mb-4">
                        <form action="update.php" method="post">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                        <p class="mb-0">Username</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input style="padding: 5px; width: 90%;  border: none;" class="text-muted mb-0" type="text" name="username" value="<?php echo $row['username']; ?>" />
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                        <p class="mb-0">Password</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input style="padding: 5px; width: 90%;  border: none;" class="text-muted mb-0" type="text" name="password" value="<?php echo $row['password']; ?>" />
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                        <p class="mb-0">Address</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input style="padding: 5px; width: 90%;  border: none;" class="text-muted mb-0" type="text" name="address" value="<?php echo $row['address']; ?>" />
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3 d-flex justify-content-center align-items-center">
                                        <p class="mb-0">Phone</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input style="padding: 5px; width: 90%;  border: none;" class="text-muted mb-0" type="text" name="phone" value="<?php echo $row['phone']; ?>" />
                                    </div>
                                </div>
                                    <hr>
                                    <div class="d-flex justify-content-center mb-1">
                                        <button type="submit" name="update" class="btn btn-warning">Update</button>
                                        <a href="index.php" style="margin-left: 20px;" class="btn btn-danger">Cancel</a>
                                    </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div style="position: fixed; left:0; bottom:0; width: 100%; text-align: center;" class="mb-5">
                    © 2023 Build by:
                    <a class="text-black" href="https://github.com/tu3n4nh">tu3n4nh</a>
        </div>
    </section>
    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous"></script>
</body>

</html>