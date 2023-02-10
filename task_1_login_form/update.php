<?php 
    session_start();
    include 'db_conn.php';
    $usr = false;
    if(isset($_GET['username'])){
        if(isset($_SESSION['username'])) {

            if($_SESSION['username'] == $_GET['username']) {
                $usr = true;
            }
        }
    }

    $username = $_GET['username'];
    $sql = "SELECT * FROM `task_1` WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if($row == 0) {
        header('Location: index.php');
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

    
        <div class="container py-3">
            <div class="row">
                <div class="col-lg-4" style="margin-top: 200px;">
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <img src="uploads/<?php echo $row['image'] ?>" alt="avatar" class="rounded-circle img-fluid mb-5 mt-5" style="width: 150px;">
                            <?php 
                                if($usr) echo '
                                <div class="d-flex justify-content-center mb-2">
                                    <a href="uploader.php?username='.$username.'" class="btn btn-secondary">Change avatar</a>
                                </div>'
                            ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8" style="margin-top: 200px;">
                    <div class="card mb-4">
                        <form action="updateUser.php" method="post" onsubmit="return validate()">
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
                                        <p class="mb-0">Repassword</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <input style="padding: 5px; width: 90%;  border: none;;" class="text-muted mb-0" type="text" name="repassword" value="<?php echo $row['password']; ?>" />
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
                                <?php 
                                    if($usr) echo '
                                    <hr>
                                    <div class="d-flex justify-content-center mb-1">
                                        <button type="submit" name="update" class="btn btn-warning">Update</button>
                                        <a href="user.php?username='.$username.'" style="margin-left: 20px;" class="btn btn-danger">Cancel</a>
                                    </div>'
                                ?>
                            </div>
                        </form>

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