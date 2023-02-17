# SQL Injection Bind
Link to chall: http://challenge01.root-me.org/web-serveur/ch10/
![](https://i.imgur.com/C0QjPD7.png)

## Solution
Vào đầu chall em bắt đầu thử đăng nhập để xem các tính năng cũng như kết quả trả về của từng đầu vào ra sao.
- Đăng nhập thử với `username=a` và `password=a` ![](https://i.imgur.com/qTL4NYb.png)
- Lỗi hiện ra là: `no such user/password`
- Đăng nhập thử với `username=a'` và `password=a` ![](https://i.imgur.com/OELKe1Y.png)
- Lỗi hiện ra là: `SQLite3::query(): Unable to prepare statement...`
- Tiếp tục nhưng với `username=a' or 1=1 --` và `password=a` ![](https://i.imgur.com/0vBcAYW.png)
- Ta đã đăng nhập thành công vào tài khoản user1
- Thử tiếp với: `username=a' or 1=1 order by 1 --` và `password=a` ![](https://i.imgur.com/UskFq0O.png)
- Ta đã đăng nhập thành công vào tài khoản admin

Nhờ những bước fuzz đơn giản trên ta đã xác định được: 
- database là sqlite
- có ít nhất 2 user là: user1 và admin
- để solve chall thì ta chỉ cần tìm được password của user admin

Vì dữ liệu của truy vấn không được trả về nên ta không thể sử dụng union để list tên table, thêm cả tên bài là 'Blind' nên ta sẽ brute force tên bẳng burp suite:

`username=admin' AND (SELECT length(name) from sqlite_master where type='table')>1 --&password=1`

Sử dụng payload trên để tìm tên table:
- Em đoán đoạn select phía trước là `SELECT * FROM tenbang WHERE tencotuser='bien username nhap vao' AND tencotpassword='bien pasword nhap vao'
- Dựa vào bên trên em đã biết được user `admin` có tồn tại nên khi đưa đưa `username=admin'` vào em sẽ được 1 mệnh đề điều kiện đúng
- Thay vì `AND` với password thì em thay bằng `AND` với điều kiện mà em muốn kiểm tra rồi comment hết đoạn sau lại bằng `--`
- Đầu tiên là em phải tìm độ dài của table nên điều kiện em phải kiểm tra ở đây là `SELECT length(name) from sqlite_master where type='table'` từ `sqlite_master` ở đâu có kiểu dữ liệu là `table` thì lấy ra độ dài của tên bảng đó. rồi em cho nó so sánh với chiều dài mà em đoán, rồi tăng dần giá trị đấy lên ![](https://i.imgur.com/WAJgE32.png)
- Tăng giá trị đó lên `>5` thì response trả về sai ![](https://i.imgur.com/qSiC3fh.png)
- Vậy em có thể kết luận chiều dài của bảng là 5 ký tự

Từ đây em có thể bắt đầu brute force từ ký tự của bảng với payload sau: 
`username=admin' AND substr((SELECT name from sqlite_master where type='table'),1,1)='a' --&password=1`
- Hàm `substr()` dùng để từ vị trí thứ nhất của chuỗi tên table được lấy ra hãy lấy ra 1 ký tự
- Rồi em đem so sánh ký tự vừa lấy ra đấy với ký tự em brute force
- Ở Intruder trung burp, ta set up như sau
- ![](https://i.imgur.com/ZCmoabx.png)
- Attack type là Cluser bomb để chạy cả 2 payload độc lập
- Add marker vào param thứ 2 của hàm substr để em tăng dần vị trí lấy ký tự ra so sánh ở payload set số 1, và add marker vào ký tự mà em muốn so sánh để thay đổi ký tự đó ở payload set số 2.
- Tại payload set 1:![](https://i.imgur.com/5Ynopzu.png)
Payload type: là Number và cho nó chạy từ 1 đến 5 với step là 1 vì độ dài table chỉ có 5 ký tự
- Tại payload set 2: ![](https://i.imgur.com/TGvo1kv.png)
Payload type: là Simple list rồi em add list các ký tự cho phép đặt trong tên của table.

Start Attack và em có được kết quả: ![](https://i.imgur.com/enuidug.png)
Vậy tên table là `users`


Bây giờ đến xử lý tên cột, em dùng payload sau để xác định độ dài của tên các cột trong bảng:
`username=admin' AND (SELECT length(name) FROM pragma_table_info('users') LIMIT 0,1)>0 --&password=1`

- Tăng dần thành `LIMIT 1,1`,`LIMIT 2,1`,`LIMIT 3,1`,... ![](https://i.imgur.com/BIFRgoa.png)
- Em thấy rằng tại `LIMIT 3,1` response trả về sai ![](https://i.imgur.com/KaJqlXg.png)
 Nên em suy ra bảng này có 3 cột
- Tại `LIMIT 0,1` lấy ra tên cột thứ nhất của bảng rồi em đem so sánh với 1 số ![](https://i.imgur.com/zKlf6h8.png)
 Tăng dần số đó lên thấy đến 8 thì response trả về sai ![](https://i.imgur.com/QDZwJdh.png)
 Nên em suy ra ở cột từ nhất có tên dài 8 ký tự
 Tương tự như các trên em tìm độ dài của 2 cột còn lại: cột thứ hai cũng 8 ký tự và cột thứ ba 4 ký tự

Đã có độ dài của cột em bắt đầu brute force tên từng cột từ cột thứ nhất với payload:
`username=admin%27 AND substr((SELECT name FROM pragma_table_info('users') LIMIT 0,1),§1§,1)='§a§' --&password=1`

Ta được kết quả tên cột thứ nhất là `username`: ![](https://i.imgur.com/ZvLcH7w.png)

Tương tự với cột thứ hai, thứ ba tthifem tăng thành LIMIT 1,1 và LIMIT 2,1
Ta được tên cột thứ 2 là `password` ![](https://i.imgur.com/Sa2X2ex.png)
Và cột thứ 3 là `Year` ![](https://i.imgur.com/N7Ybr0l.png)

Đã có được tên bảng em đi đến những bước cuối cùng là tìm độ dài password của admin và brute force nó với payload:
`username=admin%27 AND (SELECT length(password) from users where username='admin')>0 --&password=1`
Cũng tăng dần nó lên và với độ dài > 8 trả về response sai ![](https://i.imgur.com/M7A1ybz.png)
Em xác định được độ dài password của admin là 8 ký tự giờ chỉ còn việc brute force nó nữa thuiii, gét gô~~~

Em xử dụng payload: `username=admin%27 AND substr((SELECT password from users where username='admin'),§1§,1)='§a§' --&password=1`

Tén tén tén tennnn: ![](https://i.imgur.com/wUgnwZ7.png)

Em đã brute force được password của admin là: `e2azO93i`
Lấy password là vào solve thoiiiiii