<?php 
    session_start();
    include 'db_conn.php';
    if(isset($_POST['submit'])) {
        $imgname=$_FILES['avatar']['name'];
        $extension = pathinfo($imgname, PATHINFO_EXTENSION);
        $name = $_SESSION['username'].'Avatar'.'.'.$extension;
        $filename = $_FILES['avatar']['tmp_name'];
        $file_path = 'uploads/'.$name;
        $flag = true;

        if($_FILES['avatar']['size'] > 52428800){
            echo "<script type='text/javascript'>alert('File size too big!');</script>";
            $flag = false;
        }
    
        if($flag){
            move_uploaded_file($filename, $file_path);
            $columnName=$_SESSION['username'];
            $sql = "UPDATE `task_1` SET `image`='$name' WHERE username='$columnName'";
            $result = mysqli_query($conn, $sql);
            header("Location: user.php?username=".$_SESSION['username']);
        }
        else{
            echo "<script type='text/javascript'>alert('Upload avatar failed!');</script>";
        }
        
    }
?>