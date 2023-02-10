<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload avatar</title>
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
        Upload Avatar
    </nav>
    <div class="m-5">
        <a href="user.php?username=<?php session_start(); echo $_SESSION['username']; ?>" class="btn btn-dark">Back</a>
    </div>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="d-flex justify-content-center align-items-center flex-column" style="width: 70%; margin-left: 15%;" >
            <label for="formFileLg" class="form-label">Upload your avatar!</label>
            <input class="form-control form-control-lg" name="avatar" id="formFileLg" type="file">
            <input type="submit" class="btn btn-dark mt-3" name="submit" value="Upload" />
        </div>
    </form>


    <div style="position: fixed; left:0; bottom:0; width: 100%; text-align: center;" class="mb-5">
                    © 2023 Build by:
                    <a class="text-black" href="https://github.com/tu3n4nh">tu3n4nh</a>
    </div>

    <!-- bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>
</html>