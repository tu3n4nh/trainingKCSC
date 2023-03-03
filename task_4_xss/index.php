<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="https://i.imgur.com/v7xAiBr.png">
    <title>Lab XSS</title>
</head>

<body style="width: 100vw; overflow-x: hidden;">
    <div style="display: flex; justify-content: center; margin-top: 40px;">
        <h1>Lab XSS</h1>
    </div>
    <form action="index.php" method="GET">
        <div class="row g-3 align-items-center dflex justify-content-center mt-3">
            <div class="col-auto">
                <label for="inputPassword6" class="col-form-label">Type your name:</label>
            </div>
            <div class="col-auto">
                <input type="text" id="inputPassword6" class="form-control" aria-describedby="passwordHelpInline" name="name">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

    <div style="display: flex; justify-content: center; margin-top: 20px;">
        <!-- For reflected XSS -->
        <?php
        if (isset($_GET['name']) && $_GET['name'] !== "") {
            $name = htmlspecialchars($_GET['name']);
            echo '<b>Reflected XSS:</b>&nbsp;&nbsp;&nbsp;&nbsp;Hello, ' . $name . '!';
        }
        ?>
    </div>
    <!-- For DOM based XSS -->
    <script>
        if(document.URL.indexOf('name')>0){
            let pos = document.URL.indexOf('name') + 5;
            document.write("<center><b>DOM based XSS:</b>&nbsp;&nbsp;&nbsp;&nbsp;Hello, " + decodeURIComponent(document.URL.substring(pos)) + "!</center>");
        }
    </script>
    <center>
        <?php
        if (isset($_GET['message'])) {
            echo '<div id="myAlert" class="alert alert-success" style="width: 40vw;" role="alert">' . $_GET['message'] . '!</div>';
        }
        ?>
    </center>
    <script>
        setTimeout(function() {
            document.getElementById('myAlert').style.display = 'none';
        }, 2000);
    </script>
    <!-- For Stored XSS -->
    <form action="" method="POST">
        <div style="margin: 5vh auto; max-width: 50%;">
            <h2 style="display: flex; justify-content: center;">Post comment here:</h2>
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Email address</label>
                <input type="email" class="form-control" id="exampleFormControlInput1" name="email" placeholder="name@example.com">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Your comment:</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" name="comment" rows="3"></textarea>
            </div>
            <div class="mb-3 d-flex justify-content-end me-3">
                <button type="submit" class="btn btn-secondary px-4" name="submit">Submit</button>
            </div>
        </div>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        if (isset($_POST['email']) && isset($_POST['comment'])) {
            $email = $_POST['email'];
            $comment = $_POST['comment'];

            $conn = mysqli_connect('localhost', 'root', '', 'kcsc');
            $sql = "INSERT INTO `task_4`(`id`, `email`, `comment`) VALUES (null,'$email','$comment')";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?message=Comment posted successfully");
            } else {
                echo "Error: " . mysqli_error($conn);
            }
        }
    }
    ?>
    <table style="margin-left: 30vw;">
        <thead>
            <tr>
                <th scope="col" colspan="1">Email&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th scope="col" colspan="2">Comment</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn = mysqli_connect("localhost", "root", "", "kcsc");
            $sql = "SELECT * FROM `task_4` WHERE 1";
            $resullt = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($resullt)) { ?>
                <tr>
                    <th scope="row"><?php echo $row['email'] . ':&nbsp;&nbsp;&nbsp;&nbsp;' ?></th>
                    <td colspan="2"><?php echo $row['comment'] ?></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>