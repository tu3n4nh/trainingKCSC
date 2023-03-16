# Lý thuyết lỗ hổng upload file

Lỗ hổng upload file là một trong những lỗ hổng bảo mật phổ biến trên các trang web cho phép người dùng tải lên các tệp lên server. Lỗ hổng này cho phép kẻ tấn công tải lên những tệp độc hại, thực thi mã và chiếm quyền điều khiển server.

## Tại sao lại xảy ra lỗi?

Lỗ hổng upload file xảy ra khi trang web không kiểm soát được các tệp được tải lên và không xác thực tệp được tải lên. Kẻ tấn công có thể tải lên các tệp có chứa mã độc hoặc thực thi mã trên server bằng cách tải lên các tệp có đuôi phù hợp với định dạng được chấp nhận bởi ứng dụng web.

## Cách khai thác và một số cách bypass

Kẻ tấn công có thể tải lên các tệp có chứa mã độc hoặc thực thi mã trên server bằng cách tải lên các tệp có đuôi phù hợp với định dạng được chấp nhận bởi ứng dụng web. Để khai thác lỗ hổng upload file, kẻ tấn công có thể tải lên một trong các tệp sau đây:

- Tệp chứa mã độc, chẳng hạn như mã JavaScript hoặc PHP, sẽ được thực thi khi được tải lên server.
- Tệp tin nén, chứa mã độc hoặc file khác, được giải nén bởi ứng dụng web.
- Tệp tin hình ảnh hoặc video chứa mã độc được giấu trong phần dữ liệu bổ sung.

Một số cách bypass lỗ hổng upload file bao gồm:

- Thay đổi đuôi tệp tin để vượt qua các kiểm tra định dạng tệp
- Sử dụng các kỹ thuật mã hoá để che giấu mã độc trong các tệp khác như tệp hình ảnh
- Sử dụng các kỹ thuật để tránh các giới hạn dung lượng tệp tin.

## Cách ngăn chặn

Để ngăn chặn lỗ hổng upload file, các ứng dụng web cần thực hiện các biện pháp sau:

- Xác thực và kiểm soát các tệp được tải lên
- Kiểm tra định dạng tệp tin và ngăn chặn các tệp tin có định dạng gây nguy hiểm như mã độc
- Sử dụng các kỹ thuật mã hoá để bảo vệ dữ liệu trước khi tải lên
- Giới hạn dung lượng tệp tin được tải lên.

---

# Root me

![](https://i.imgur.com/jPZ1IJH.png)

link to chall: http://challenge01.root-me.org/web-serveur/ch20/

## File upload - Double extensions

![](https://i.imgur.com/Ta8WyX1.png)

Ta có web shell như sau:

![](https://i.imgur.com/8Qhqlvs.png)

Đoạn code trên có nghĩa là nó sẽ nhập vào một param tên là cmd thông qua GET method, hay nói một cách dễ hiểu là cmd nó sẽ chứa command line trên hệ thống linux mà bạn muốn thực thi, và hàm system() này sẽ thực thi nó. Sau đó, dùng echo để show kết quả ra.

Ta upload thử file shell-3.php và dĩ nhiên là không được :v

![](https://i.imgur.com/t4C0o0G.png)

Vì tên bài là double extension nên ta nghĩ ngay đến 1 cách bypass là thêm .png vào sau

![](https://i.imgur.com/3GhGBjz.png)

Upload file shell-3.php.png 

![](https://i.imgur.com/uQccBF2.png)

Mở file shell vừa up và thêm prama ?cmd=ls -a vào cuối URL

![](https://i.imgur.com/eitqyQf.png)

Lần lượt thêm ../ vào cuối để list các file có trong đường dẫn lúc đó, để tìm ra file .passwd

Cuối cùng, thì chỉ cần cat(Lệnh cat cho phép người dùng xem nội dung file) file .passwd ?cmd=cat ../../../.passwd 

![](https://i.imgur.com/a30Lp49.png)

---

## File upload - MIME type

![](https://i.imgur.com/3FpG43i.png)


Ta có web shell:

![](https://i.imgur.com/IYgaq1y.png)


Nếu vẫn tiếp tục các thêm extension như bài trước sẽ không được.

Dựa theo tên chall MIME ta nghĩ đến việc đổi Content-Type

![](https://i.imgur.com/QnAxPI2.png)


Mặc định file shell.php của ta sẽ có Content-Type là application/octet-stream chính vì vậy nên file bị filter và dẫn đến Wrong file type!

Việc cần làm chỉ là đổi Content-Type thành image/png

![](https://i.imgur.com/Jh0G9kB.png)


Đến đây ta đã upload xong web shell giờ thì chỉ cần làm như chall trước để có được flag :v 

![](https://i.imgur.com/QE0JORh.png)


## File upload - Null byte

![](https://i.imgur.com/1aF9B1o.png)


Vẫn dùng web shell như trên

Nếu ta thử bypass bằng tất cả những cách trên sẽ không được :v 

![](https://i.imgur.com/KB647wI.png)


Lại dựa vào tên của chall là Null byte ta sử dụng ngay 1 cách sử dụng %00 để bypass như sau

![](https://i.imgur.com/ZEF5u8w.png)


Thêm %00 vào filename trong đó %00 tương đương giá trị NULL, khi nhận tên file vào đến giá trị NULL bị dừng lại, từ đó ta có thể tải lên file shell.php

Mở file shell ta đã upload lên

Và thế là ta đã có được flag :v 

![](https://i.imgur.com/WK01zOW.png)


## File upload - ZIP

![](https://i.imgur.com/9IhzBEr.png)


Ta thử upload 1 file zip

![](https://i.imgur.com/4anGv0C.png)

![](https://i.imgur.com/FZ8pAPA.png)


Truy cập vô thấy file zip bị đổi tên, mở file ảnh, video, ... được nhưng file .php bị chọn (Error 403) 

Không chạy được file .php nhưng những file khác thì ok nên em đã làm theo như sau:

Tạo 1 file .txt để symbolic link đến file index.php cần đọc

`$ ln -s ../../../index.php shell.txt`

Sau đó nén file shell.txt lại

`$ zip -y shell.zip shell.txt`

Cuối cùng là upload file shell.zip lên và truy cập vào file shell.txt

![](https://i.imgur.com/codXEWZ.png)


Và thế là ta đã có được flag :v 

![](https://i.imgur.com/nAtBBJF.png)


Còn có rất nhiều các tấn công file khác nữa, ta có thể thấy trong ảnh dưới đây

![](https://i.imgur.com/sedHiBi.png)
