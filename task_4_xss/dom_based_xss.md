# DOM based XSS

```javascript
    

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

    <div style="display: flex; justify-content: center; margin-top: 20px;" id="hello"></div>

    <script>
        let pos = document.URL.indexOf('name') + 5;
        document.getElementById("hello").innerHTML = decodeURIComponent(document.URL.substring(pos));
    </script>
```

![](https://images.viblo.asia/cc00ebb0-67cc-4110-91e4-4188104777fd.png)

![](https://i.imgur.com/hGTqLET.png)

Như flowchart bên trên, cách khai thác một website bị dính DOM based xss là chúng ta sẽ gửi cho victim một url chứa malicious script và sau khi victim truy cập vào link đó để đến website thì đoạn mã script của chúng ta sẽ được thực hiện thực hiện tại ngay phía victim mà không cần gửi lên server. Tùy thuộc vào mục đích của chúng ta để tạo ra đoạn script đó. Cách khai thác này sinh ra để bypass việc prevent XSS như sử dụng XSS Author, XSS Filter, NoScript...

Em muốn victim bị rick roll nên đoạn script của em sẽ là: `<script>window.location.href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"</script>`

Và đoạn url em gửi cho victim sẽ là: `http://localhost/trainingKCSC/task_4_xss/index.php?name=%3Cscript%3Ewindow.location.href=%22https://www.youtube.com/watch?v=dQw4w9WgXcQ%22%3C/script%3E`

![](https://i.imgur.com/q1SzhgO.png)

Response server trả về không có chứa đoạn script nhưng script vẫn được thực thi vì điểm khác biết của nó với reflected xss nó thực thi ở phía client luôn và không cần chờ kết quả trả về từ phía server

![](https://i.imgur.com/ArjCBHo.png)

Vậy là em đã DOM based XSS thành công