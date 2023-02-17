# Webhacking old 49

link to chall: https://webhacking.kr/challenge/web-24/

![](https://i.imgur.com/yhUu84W.png)

```php
  if($_GET['lv']){
    $db = dbconnect();
    if(preg_match("/select|or|and|\(|\)|limit|,|\/|order|cash| |\t|\'|\"/i",$_GET['lv'])) exit("no hack");
    $result = mysqli_fetch_array(mysqli_query($db,"select id from chall49 where lv={$_GET['lv']}"));
    echo $result[0] ;
    if($result[0]=="admin") solve(49);
  }
```

Sau khi đọc source code ta thấy rằng input bị filter gồm: `select`, `or`, `and`, `(`,`)`,`limit`,`/`,`order`,`cash`,` `,`\t`,`'` và `"`
Để solve chall thì ta cần làm cho kết quả ở cột đầu tiên của hàng đầu tiên trả về `admin`

## Solution

Em tăng tử level lên rồi submit thấy rằng kết quả trả về đã thay đổi 
![](https://i.imgur.com/iYeAaic.png)

Tương tự với level 3, 4 cũng vậy. 

Sau một hồi đọc về điều kiện so sánh trong SQL em tìm được doc về toán tử `||` (cái mà không bị filter):

Cái `||` là toán tử trong SQL, là toán tử logic OR. Trong truy vấn này, nó được sử dụng để nối hai giá trị `0` và `admin` với nhau thành một chuỗi, ước tính thành chuỗi `"0admin"`. Sau đó, truy vấn sẽ so sánh cột id với chuỗi này.

Vì cột `lv` có khả năng là một cột số nguyên nên có thể quá trình so sánh sẽ không thành công và truy vấn sẽ không trả về bất kỳ hàng nào. Trong trường hợp này, `||` sẽ hoạt động như toán tử `OR` theo bit, sẽ thực hiện phép toán OR logic trên biểu diễn nhị phân của hai giá trị. Điều này sẽ tạo ra kết quả khác `0`, kết quả này sẽ được coi là đúng trong ngữ cảnh boolean, khiến truy vấn truy xuất tất cả các hàng từ bảng.

Cần lưu ý rằng truy vấn này dễ bị tấn công SQL injection do giá trị của tham số `lv` được nối trực tiếp vào truy vấn. Bạn nên sử dụng các truy vấn được tham số hóa và các câu lệnh đã chuẩn bị sẵn để ngăn chặn các cuộc tấn công SQL injection.

Em dùng payload: `?lv=0||id=0x61646D696E`

Truy vấn `SELECT id FROM chall49 WHERE lv=0||0x61646D696E` lúc này toán tử `||` sẽ `OR` theo bit dựa trên biểu diễn nhị phân của 2 giá trị trên tạo ra kết quả khác `0` và truy vấn sẽ truy xuất tất cả các hàng từ bảng chall49 trong đó cột `id` và lấy ra `id` ở vị trí đầu là `admin` nhờ `$result[0]`.

![](https://i.imgur.com/T6YiD2e.png)