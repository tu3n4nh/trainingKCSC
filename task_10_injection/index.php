<?php
    if (isset($_GET['cmd']) && !empty($_GET['cmd'])) {
        $cmd = $_GET['cmd'];
        if(preg_match('/head|dir|type|echo|m4|curl|\;|\|\{\|\}|\$|\&/i', $cmd)){
            die("Hack detected!");
        }
        $command = $cmd;
        system($command);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="https://i.imgur.com/v7xAiBr.png">
    <title>KCSC Training</title>
</head>
<body>
    <form action="" method="get">
        <p>Nhập tên của bạn:</p>
        <input type="text" name="command">
        <button type="submit">submit</button>
    </form>
</body>
</html>


<?php 
if(isset($_REQUEST["command"])){
    echo "<br>Xin chào!!! ";
    $command_string = strval($_REQUEST["command"]);
    eval("echo ".'"'.$command_string.'"'.";");
    // Flag part 1: KCSCCTF{C0d3_1nj3c71on_
}
?>