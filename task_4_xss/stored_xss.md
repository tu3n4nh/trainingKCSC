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

Nh?? flowchart b??n tr??n, c??ch khai th??c m???t website b??? d??nh stored xss l?? vi???c server s??? l??u tr??? d??? li???u m?? ng?????i d??ng g???i v??o d???a v??o ???? ch??ng ta s??? g???i ?????n server m???t ??o???n m?? ?????c v?? sau khi victim truy c???p v??o website ???? th?? ??o???n m?? ?????c h???i s??? ???????c tr??? v??? cho cho victim qua response.  T??y thu???c v??o m???c ????ch c???a ch??ng ta ????? t???o ra ??o???n script ????. ??i???m ?????c bi???t c???a Stored XSS l?? ph???m vi t???n c??ng c???a n?? s??? r???ng h??n (v???i b???t k??? ai truy c???p website b??? d??nh l???i ????).

Em mu???n victim b??? rick roll n??n ??o???n script c???a em s??? l??: `<script>window.location.href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"</script>`

![](https://i.imgur.com/2bKsNcF.png)

![](https://i.imgur.com/kY2LXZX.png)

L??c n??y server s??? tr??? v??? trong response ch???a ??o???n javascript nh?? sau:

![](https://i.imgur.com/WH1WFv1.png)

V?? ??o???n m?? ???? s??? ???????c t??? ?????ng th???c thi r???i chuy???n h?????ng ng?????i d??ng sang youtube

![](https://i.imgur.com/Ley3axF.png)

V???y l?? ta ???? th???c hi???n Stored XSS th??nh c??ng.