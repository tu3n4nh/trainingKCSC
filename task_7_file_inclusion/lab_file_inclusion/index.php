<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="https://i.imgur.com/v7xAiBr.png">
    <title>File Inclusion Lab</title>
</head>

<body>

    <center>
        <h1 class="mt-5">File Inclusion Lab</h1>
    </center>

    <div class="container">
        <form action="index.php" method="get">
        <div class="card-group mt-5">
            <div class="card rounded-5">
                <img src="https://i.imgur.com/DPoX4hl.png" class="card-img-top" alt="cat">
                <button type="submit" class="btn btn-dark m-2" name="info" value="cat">More info</button>
            </div>
            <div class="card rounded-5">
                <img src="https://i.imgur.com/SJOSsq1.png" class="card-img-top" alt="cheems">
                <button type="submit" class="btn btn-warning m-2" name="info" value="cheems">More info</button>         
            </div>
            <div class="card rounded-5">
                <img src="https://i.imgur.com/xihhohu.png" class="card-img-top" alt="pepe">
                <button type="submit" class="btn btn-success m-2" name="info" value="pepe">More info</button>             
            </div>
        </div>
        </form>
    </div>
    <center>
        <?php 
        // Flag Part 1: KCSC{F1l3 1nclu
        if(isset($_GET['info'])){
            $info = include($_GET['info'].'.php');
        }
        ?>
    </center>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>