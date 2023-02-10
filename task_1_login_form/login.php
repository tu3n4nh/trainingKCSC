<?php 
    include 'db_conn.php';

    session_start();
    if(isset($_POST['register'])) {
        header('Location: register.php');
    }
    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = md5($password);

        $sql = "SELECT * FROM `task_1` WHERE username = '$username' AND password = '$password'";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        if($row == 0) {
            echo "<script type='text/javascript'>alert('Wrong username or password!');</script>";
        } else {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: index.php?id='.$row['id']);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

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

    <!-- login container -->
    <section class="vh-100" style="background-color: #57050b;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <form action="login.php" method="post" id="form" onsubmit="return validate()">

                        <div class="card shadow-2-strong" style="border-radius: 1rem;">
                            <div class="card-body p-5 text-center">

                                <h3 class="mb-5 p-3 text-white" style="background-color: #57050b;">Sign in</h3>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="typeEmailX-2">Username</label>
                                    <input type="text" id="username" name="username" class="form-control form-control-lg"
                                        placeholder="Username (between 5 and 15 characters)" />
                                </div>

                                <div class="form-outline mb-4">
                                    <label class="form-label" for="typePasswordX-2">Password</label>
                                    <input type="password" name="password" id="password" class="form-control form-control-lg"
                                        placeholder="Password (between 8 and 20 characters)" />
                                </div>

                                <!-- Checkbox -->
                                <!-- <div class="form-check d-flex justify-content-start mb-4">
                                    <input class="form-check-input" type="checkbox" value="" id="form1Example3" />
                                    <label class="form-check-label" for="form1Example3"> Remember password </label>
                                </div> -->
                                

                                <button class="btn btn-primary btn-lg btn-dark mt-3 mb-2" type="submit"
                                    name="login">Login</button>

                                <hr class="my-3">

                                <div>
                                    Don't have account?
                                </div>

                                <a href="register.php" class="btn btn-primary btn-lg btn-dark m-2">Register</a>

                            </div>
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
                <div class="text-center p-3 text-white">
                © 2023 Build by:
                <a class="text-white" href="https://github.com/tu3n4nh">tu3n4nh</a>
                </div>
            </div>
        </div>
    </section>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN"
        crossorigin="anonymous">
    </script>
</body>

</html>