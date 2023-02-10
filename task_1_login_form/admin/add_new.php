<?php
include "db_conn.php";

if(isset($_POST['submit'])) {
    $username = $_POST['username'];
    $sql = "SELECT * FROM `task_1` WHERE username ='$username' ";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    if($row != 0) {
        echo "<script type='text/javascript'>alert('Username already exists!');</script>";
    } else {
        $password = md5($_POST['password']);
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        
        $sql = "INSERT INTO `task_1` (`id`, `username`, `password`, `address`, `phone`) VALUES (NULL,'$username', '$password', '$address', '$phone')";
        
        $result = mysqli_query($conn, $sql);
        
        if($result) {
            header("Location: index.php?msg=New record created successfully");
        } else {
            echo "Failed: " . mysqli_error($conn);
        }        
    }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP CRUD Application</title>

    <link rel="icon" type="image/x-icon" href="https://scontent.fhan14-3.fna.fbcdn.net/v/t39.30808-6/300582505_457118323100327_5264492577895373431_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=09cbfe&_nc_ohc=qMYwYD25960AX9qi2s9&_nc_ht=scontent.fhan14-3.fna&oh=00_AfCjRuo9eOURQuGdCT0ZcrZBVaqyG0e9HjQUhKU42JAFbg&oe=63E767A5">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <nav 
    class="navbar navbar-light justify-content-center fs-3 mb-5 text-white fw-bold" 
    style="background-color: #57050b"
    >
        Admin Panel
    </nav>
    
    <div class="container">
        <div class="text-center mb-4">
            <h3>Add New User</h3>
            <p class="text-muted">Complite th form below to add a new user</p>
        </div>

        <div class="container d-flex justify-content-center">
            <form action="" method="post" style="width: 50vw; min-width: 300px;" onsubmit="return validate()">
                <div class="mb-3">
                    <label class="form-label">Username:</label>
                    <input type="text" class="form-control" name="username" placeholder="Username (between 5 and 15 characters)">
                </div>
                <div class="mb-3">
                    <label class="form-label">Password:</label>
                    <input type="text" class="form-control" name="password" placeholder="Password (between 8 and 20 characters)">
                </div>

                <div class="mb-3">
                <label class="form-label">Address:</label>
                        <input type="text" class="form-control" name="address" placeholder="141 Chien Thang">
                </div>

                <div class="mb-3">
                <label class="form-label">Phone:</label>
                        <input type="text" class="form-control" name="phone" placeholder="0123456789">
                </div>

                <div>
                    <button type="submit" class="btn btn-success" name="submit">Save</button>
                    <a href="index.php" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
  </body>
</html>