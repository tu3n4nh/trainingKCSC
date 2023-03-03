# Stored XSS

```php
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
```

![](https://images.viblo.asia/f0648231-6f0d-4bd7-988a-a471f2a05e37.png)

Như flowchart bên trên, cách khai thác một website bị dính stored xss là việc server sẽ lưu trữ dữ liệu mà người dùng gửi vào dựa vào đó chúng ta sẽ gửi đến server một đoạn mã độc và sau khi victim truy cập vào website đó thì đoạn mã độc hại sẽ được trả về cho cho victim qua response.  Tùy thuộc vào mục đích của chúng ta để tạo ra đoạn script đó. Điểm đặc biệt của Stored XSS là phạm vi tấn công của nó sẽ rộng hơn (với bất kỳ ai truy cập website bị dính lỗi đó).

Em muốn victim bị rick roll nên đoạn script của em sẽ là: `<script>window.location.href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"</script>`

![](https://i.imgur.com/2bKsNcF.png)

![](https://i.imgur.com/kY2LXZX.png)

Lúc này server sẽ trả về trong response chứa đoạn javascript như sau:

![](https://i.imgur.com/WH1WFv1.png)

Và đoạn mã đó sẽ được tự động thực thi rồi chuyển hướng người dùng sang youtube

![](https://i.imgur.com/Ley3axF.png)

Vậy là ta đã thực hiện Stored XSS thành công.