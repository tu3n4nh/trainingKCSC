<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon"
        href="https://scontent.fhan2-5.fna.fbcdn.net/v/t39.30808-6/300582505_457118323100327_5264492577895373431_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=InPEQ7ZBsT0AX8a0OIG&_nc_ht=scontent.fhan2-5.fna&oh=00_AfASMdtDXHBvDXoNHSampgMACiJxHsRrY4Zb-91qH9NOBQ&oe=63F739A5">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Error based</title>
</head>
<body>
    <div class="d-flex justify-content-center mt-5"><h1>Error based</h1></div>
    <div class="d-flex align-items-center justify-content-center mt-5">
            <form method="post">
                <div class="mb-3">
                    <label for="exampleInputUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="exampleInputUsername" aria-describedby="emailHelp" name="username">
                    <div id="emailHelp" class="form-text">We'll never share your information with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1" name="password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="exampleCheck1" name="remember" value="remember">
                    <label class="form-check-label" for="exampleCheck1">Remember me!</label>
                </div>
                <div class="d-flex justify-content-end me-3">
                    <button type="submit" class="btn btn-primary" name="login">Login</button>
                </div>
            </form>
            </div>
            <br>
    <?php 
    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $conn = mysqli_connect("localhost", "root", "", "kcsc");
        $sql = "SELECT * FROM `task_3` WHERE `username` = '$username' AND `password` = '$password'";
        $resullt = mysqli_query($conn, $sql);
        echo '<center>'.mysqli_error($conn).'</center>';
        if($resullt){
            if (isset($_POST['remember'])) {
                setcookie('remember', base64_encode($username.md5($username)), time() + (86400 * 30), "/");
            } else {
                setcookie('remember', '', time() - 3600, "/");
            }

            $row = mysqli_fetch_assoc($resullt);
            if($row != 0){

                echo '<br><center>Hello brooooooooooo!</center>';
                echo '
                <div class="d-flex align-items-center justify-content-center mt-5">
                <form method="post">
                <div class="d-flex justify-content-end me-3">
                <button type="submit" class="btn btn-secondary" name="logout">Logout</button>
                </div>
                </form>
                </div>';
            }else{
                echo '<center>Wrong username or password</center>';
            }
        } 
    }

    if(isset($_POST['logout'])) {
        session_destroy();
        setcookie('remember', '', time() - 3600, "/");
        header('location: lab_error_based.php');
    }
    ?>
        
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>