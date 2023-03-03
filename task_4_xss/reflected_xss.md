# Reflected XSS Lab
```php
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
        <?php
        if (isset($_GET['name']) && $_GET['name'] !== "") {
            $name = $_GET['name'];
            echo 'Hello, ' . $_GET["name"] . '!';
        }
        ?>
    </div>
```

![](https://images.viblo.asia/28e81cf8-c006-4835-9ef0-a8df7d2ccd12.jpg)

![](https://i.imgur.com/s4RDNx0.png)

Như flowchart bên trên, cách khai thác một website bị dính reflected xss là chúng ta sẽ gửi cho victim một url chứa malicious script và sau khi victim truy cập vào link đó để đến website thì đoạn mã script của chúng ta sẽ được gửi lên server và server sẽ trả về đoạn script đó cho browser của victim thực thi. Tùy thuộc vào mục đích của chúng ta để tạo ra đoạn script đó.

Em muốn victim bị rick roll nên đoạn script của em sẽ là: `<script>window.location.href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"</script>`

Và đoạn url em gửi cho victim sẽ là: `http://localhost/trainingKCSC/task_4_xss/index.php?name=%3Cscript%3Ewindow.location.href=%22https://www.youtube.com/watch?v=dQw4w9WgXcQ%22%3C/script%3E`

Lúc này server sẽ trả về trong DOM sẽ chưa đoạn javascript như sau:

![](https://i.imgur.com/J8Y3ywr.png)

Và đoạn mã đó sẽ được tự động thực thi rồi chuyển hướng người dùng sang youtube

![](https://i.imgur.com/Ley3axF.png)

Vậy là ta đã thực hiện Reflected XSS thành công.