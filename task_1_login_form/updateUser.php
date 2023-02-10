<?php
    include 'db_conn.php';
    if(isset($_POST['update'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
    
        if($password != $repassword){
            echo "<script type='text/javascript'>alert('Repassword does match!');</script>";
        } else {
            $password = md5($password);
            $sql = "UPDATE `task_1` SET `password`='$password',`address`='$address',`phone`='$phone' WHERE username='$username' ";
            $result = mysqli_query($conn, $sql);
            if($result) {
                echo "<script type='text/javascript'>alert('Update success!');</script>";
                header("Location: user.php?username=".$username);
            } else {
                echo "Failed: " . mysqli_error($conn);
            }
        }
    }
?>