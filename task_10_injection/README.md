# Lỗ hổng code injection và command injection

## Lỗ hổng code injection

**Khái niệm:**

Lỗ hổng code injection là một trong các lỗ hổng bảo mật phổ biến và nguy hiểm nhất trong các ứng dụng web hiện đại. Lỗ hổng này cho phép kẻ tấn công chèn các đoạn mã độc hại vào các ứng dụng web. Lỗ hổng này xảy ra khi ứng dụng web không kiểm tra và xác thực đầu vào từ người dùng đầy đủ trước khi sử dụng chúng để tạo các lệnh động.

Cách thức tấn công của code injection thường là kẻ tấn công chèn các đoạn mã độc hại vào các trường nhập liệu của ứng dụng web, nhằm mục đích thực thi các lệnh độc hại trên máy chủ web hoặc cơ sở dữ liệu của ứng dụng.

Các kỹ thuật tấn công code injection bao gồm:

- SQL injection: kẻ tấn công chèn các câu lệnh SQL độc hại vào các trường nhập liệu, nhằm mục đích thực thi các lệnh trên cơ sở dữ liệu của ứng dụng.
- JavaScript injection: kẻ tấn công chèn mã JavaScript độc hại vào các trường nhập liệu, nhằm mục đích thực thi các lệnh trên trình duyệt của người dùng.
- PHP injection: kẻ tấn công chèn mã PHP độc hại vào các trường nhập liệu, nhằm mục đích thực thi các lệnh trên máy chủ web.

**Nguyên nhân:**
- Do đầu vào không được loc kĩ.
- Do đầu vào được cho trực tiếp vào mã nguồn để thực thi.
- Do sử dụng các hàm dễ gây ra Code injection (extract, system, eval, …).
- Do tạo ra các chức năng không cần thiết cho server.

**Các tác hại của code injection bao gồm:**

- Thực thi các lệnh độc hại trên cơ sở dữ liệu hoặc máy chủ web, có thể dẫn đến mất dữ liệu hoặc tấn công từ chối dịch vụ (DDoS).
- Đánh cắp thông tin nhạy cảm, như tên đăng nhập, mật khẩu hay thông tin thẻ tín dụng.

**Các cách bypass code injection bao gồm:**

- Encoding payloads: Kỹ thuật này sử dụng các mã hóa để che giấu payload của kẻ tấn công. Payload sẽ được mã hóa bằng cách sử dụng các kỹ thuật mã hóa phổ biến như URL encoding, HTML encoding, Base64 encoding, Unicode encoding, v.v. Điều này làm cho payload khó đọc được và tránh bị phát hiện bởi các cơ chế bảo vệ.

- Injection strings: Kỹ thuật này sử dụng các chuỗi chèn để tạo ra một payload mà khi chạy trên hệ thống sẽ thực hiện một lệnh hệ thống bên trong ứng dụng. Các chuỗi chèn thường được sử dụng là các lệnh shell như "&&", "|", ";&", "$()", v.v.

- Shellshock attack: Kỹ thuật này tấn công vào các ứng dụng sử dụng Bash shell. Kẻ tấn công sử dụng các biến môi trường để chèn các lệnh hệ thống vào trong ứng dụng. Điều này cho phép kẻ tấn công thực thi các lệnh hệ thống bên trong ứng dụng.

- File inclusion attack: Kỹ thuật này tấn công vào các ứng dụng sử dụng các tham số đường dẫn để xác định các tệp tin cần được tải. Kẻ tấn công sử dụng các giá trị khác nhau cho tham số đường dẫn để truy cập các tệp tin khác nhau, bao gồm các tệp tin chứa mã độc.

**Các cách phòng chống code injection bao gồm:**

- Kiểm tra và xác thực đầu vào từ người dùng trước khi sử dụng chúng để tạo các lệnh động, bằng cách sử dụng các phương thức mã hóa và kiểm tra định dạng dữ liệu.
- Sử dụng whitelist đối với đầu vào của người dùng.
- Sử dụng các biện pháp bảo mật để giảm thiểu các cuộc tấn công từ bên ngoài mạng, bao gồm cập nhật các bản vá bảo mật và cấu hình hệ thống tường lửa.
- Cập nhật các bản vá bảo mật cho các ứng dụng và hệ thống phần mềm để giảm thiểu các lỗ hổng bảo mật.

## Lỗ hổng command injection

**Khái niệm:**

Command injection là một lỗ hổng bảo mật trong các ứng dụng web, khi kẻ tấn công có thể chèn các lệnh hệ thống vào trong các tham số đầu vào của ứng dụng. Khi thực thi các lệnh này, kẻ tấn công có thể kiểm soát hoàn toàn hệ thống và thực hiện các hành động bất hợp pháp.

**Nguyên nhân:**

Lỗ hổng command injection xảy ra khi các ứng dụng không xử lý đầu vào của người dùng đầy đủ hoặc kiểm tra đầu vào đó không an toàn.

**Tác hại:**

Command injection có thể gây ra các hậu quả nghiêm trọng, bao gồm đánh cắp dữ liệu, thay đổi hoặc xóa dữ liệu, chèn mã độc, chạy các lệnh hệ thống độc hại, v.v. Kẻ tấn công có thể kiểm soát hoàn toàn hệ thống và thực hiện các hành động bất hợp pháp.

**Một số cách bypass:**

Để bypass command injection, kẻ tấn công có thể sử dụng các kỹ thuật như encoding payloads, injection strings, shellshock attack, v.v. để che giấu payload và tránh bị phát hiện bởi các cơ chế bảo vệ.

**Cách phòng chống:**

Để phòng chống command injection, các nhà phát triển có thể sử dụng các kỹ thuật như kiểm tra đầu vào, mã hóa đầu vào, sử dụng các thư viện bảo mật và sử dụng các công cụ bảo mật để phát hiện các lỗ hổng bảo mật trong ứng dụng. Tuy nhiên, tốt nhất là phòng chống ngay từ lúc thiết kế ứng dụng bằng cách sử dụng các phương pháp an toàn để mã hóa đầu vào và đảm bảo tính toàn vẹn của dữ liệu. Các phương pháp bảo mật khác bao gồm sử dụng các chức năng của ngôn ngữ lập trình để thực hiện các lệnh hệ thống, sử dụng các tài liệu tham khảo bảo mật và giám sát hoạt động của ứng dụng.

### Sự khác biệt giữa lỗ hổng code injection và command injection

Mặc dù lỗ hổng code injection và command injection đều là những lỗ hổng bảo mật nguy hiểm và phổ biến trong các ứng dụng web, tuy nhiên chúng có một số điểm khác biệt như sau:

- Đối tượng tấn công: Code injection nhắm vào các ứng dụng web mà cho phép người dùng nhập và thực thi mã nguồn trực tiếp trên máy chủ. Trong khi đó, command injection nhắm vào các ứng dụng web mà cho phép người dùng nhập và thực thi các lệnh hệ thống trên máy chủ.

- Loại mã tấn công: Code injection chèn các đoạn mã nguồn độc hại vào các trang web hoặc các biến đầu vào của ứng dụng web. Trong khi đó, command injection chèn các lệnh hệ thống độc hại vào các biến đầu vào của ứng dụng web.

- Tác hại: Code injection có thể dẫn đến các hậu quả nghiêm trọng như trộm dữ liệu, thay đổi hoặc xóa dữ liệu, chèn mã độc, v.v. Trong khi đó, command injection có thể kiểm soát hoàn toàn hệ thống và thực hiện các hành động bất hợp pháp như đánh cắp dữ liệu, chèn mã độc, v.v.

- Cách phòng chống: Các phương pháp phòng chống code injection và command injection cũng có những khác biệt nhất định. Để phòng chống code injection, các nhà phát triển có thể sử dụng các phương pháp mã hóa đầu vào và kiểm tra đầu vào để đảm bảo tính toàn vẹn của dữ liệu. Để phòng chống command injection, các nhà phát triển có thể sử dụng các phương pháp kiểm tra đầu vào, mã hóa đầu vào và sử dụng các thư viện bảo mật để đảm bảo tính an toàn của hệ thống.


# Lab

```php
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
```
## Code injection

Vào lab em nhận được giao diện như này:

![](https://i.imgur.com/2WGoXMy.png)

Thử input vài trường hợp:

- `123`

![](https://i.imgur.com/B7cdyGO.png)

- `aaaa`

![](https://i.imgur.com/4Y3Voxs.png)

- `"`

![](https://i.imgur.com/3783bw5.png)

- `123"."aaaaa`

![](https://i.imgur.com/X1ZsPXW.png)

Có thể nối chuỗi ở field này, lại còn dùng hàm eval() nên em tiếp tục thử

- `123".phpinfo()."`

![](https://i.imgur.com/nTGaH1e.png)

Vậy là em có thể thực hiện code injection rồi:

Thay `phpinfo()` thành `highlight_file('index.php')`

![](https://i.imgur.com/xAnvRba.png)


=> Flag part 1: KCSCCTF{C0d3_1nj3c71on_ 

## Command injection

Dựa vào nội dung file index.php lấy được ở trên em biết được ứng dụng sử dụng param `cmd` để truyền vào hàm system() nhưng nhưng lệnh hay dùng ở cmd lại bị filter.

Quan sát kĩ hơn thì em thấy chưa filter netcat nên em sử dụng reverse shell xem sao.

- `ncat -lnvp 1234`

![](https://i.imgur.com/9VUT1GU.png)

- `ngrok tcp 1234`

![](https://i.imgur.com/WJmoxtx.png)

- Tạo reverse shell tại: https://www.revshells.com/

![](https://i.imgur.com/Il9vlWk.png)

- Copy payload gửi thử:

![](https://i.imgur.com/V6Vqhlh.png)

Vậy là đã thành công!

Giờ tìm phần flag còn lại thôi:

![](https://i.imgur.com/AeJ7QCl.png)

=> Flag part 2: 4nd_C0mm4nd_1nj3c71on}