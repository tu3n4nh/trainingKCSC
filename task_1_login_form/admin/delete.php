<?php
    include "db_conn.php";
    $username = $_GET['username'];
    $sql = "DELETE FROM `task_1` WHERE `username` = '$username'";
    $result = mysqli_query($conn, $sql);
    if($result){
        session_destroy();
        header("Location: index.php?msg=User deleted successfully");
    } else {
        echo "Failed: " . mysqli_error($conn);
}
?>