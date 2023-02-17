# Webhacking old 45

link to chall: https://webhacking.kr/challenge/web-22/

```php
  if($_GET['id'] && $_GET['pw']){
    $db = dbconnect();
    $_GET['id'] = addslashes($_GET['id']);
    $_GET['pw'] = addslashes($_GET['pw']);
    $_GET['id'] = mb_convert_encoding($_GET['id'],'utf-8','euc-kr');
    if(preg_match("/admin|select|limit|pw|=|<|>/i",$_GET['id'])) exit();
    if(preg_match("/admin|select|limit|pw|=|<|>/i",$_GET['pw'])) exit();
    $result = mysqli_fetch_array(mysqli_query($db,"select id from chall45 where id='{$_GET['id']}' and pw=md5('{$_GET['pw']}')"));
    if($result){
      echo "hi {$result['id']}";
      if($result['id'] == "admin") solve(45);
    }
    else echo("Wrong");
  }
```

Để solve bài nay ta phải inject được vào `id` = `admin`
## Solution

Sau khi đọc source code, và search em thấy rằng: 
Hàm `addlashes()` được sử dụng để thoát khỏi các ký tự đặc biệt trong một chuỗi nhằm ngăn chặn các cuộc tấn công SQL injection. Tuy nhiên, có nhiều cách để bỏ qua nó trong một số trường hợp nhất định.

Một cách khả thi để bỏ qua `addslashes()` là sử dụng ký tự byte rỗng (`\0`) để kết thúc chuỗi. Trong PHP, ký tự byte rỗng được coi là phần cuối của chuỗi, do đó, bất kỳ ký tự nào xuất hiện sau nó đều bị bỏ qua. Điều này có nghĩa là kẻ tấn công có thể đưa vào một chuỗi chứa byte rỗng, theo sau là mã SQL bổ sung và nó sẽ không bị escape bởi `addslashes()`. Ví dụ:

```sql
?id=1\0' OR 1=1 --
```

Trong trường hợp này, ký tự byte rỗng kết thúc chuỗi sau ký tự "1" và phần còn lại của chuỗi (`' OR 1=1 --`) được coi là mã SQL bổ sung không được escape bởi `addslashes()`.

Một cách khác để bỏ qua addslashes() là sử dụng mã hóa thay thế có chứa các ký tự có ý nghĩa đặc biệt trong SQL nhưng không được addslashes() nhận ra. Ví dụ: mã hóa ISO-8859-1 bao gồm một ký tự có giá trị `0x80` có thể được sử dụng để biểu thị một ký tự trích dẫn đơn (`'`) trong SQL. Kẻ tấn công có thể chèn một chuỗi sử dụng ký tự này để bỏ qua các addslashes(). Ví dụ:

```sql
?id=1\0' UNION SELECT 1,0x80data0x80,2 --
```

Trong trường hợp này, ký tự `0x80` được sử dụng để biểu thị một ký tự trích dẫn đơn, ký tự này thường được escape bằng `addslashes()`. Tuy nhiên, vì addlashes() không nhận ra ký tự `0x80` nên nó không escape và cuộc tấn công thành công.

Điều đáng chú ý là những kỹ thuật này chỉ là một vài ví dụ về những cách có thể bỏ qua các `addslashes()` và cũng có thể có các phương pháp khác. Do đó, thông thường nên sử dụng các phương pháp xác thực đầu vào và xây dựng truy vấn SQL an toàn hơn, chẳng hạn như prepared statements hoặc parameterized queries, để ngăn chặn các cuộc tấn công tiêm nhiễm SQL.

Quay lại với bài này sau một buổi hì hục đủ kiểu thử convert `\0` từ bộ mã `euc-kr` sang `utf-8` nhưng không được vì nó là null byte :<

Em thử theo hướng bypass khác: 

Em tìm thấy một kiểu tấn công SQL injection cụ thể tận dụng cách mà một số bộ ký tự nhiều byte nhất định được mã hóa (multi-byte character encode). Trong kiểu tấn công này, kẻ tấn công có thể sử dụng một ký tự multi-byte bao gồm ký tự dấu gạch chéo ngược (`\`) để bỏ qua các biện pháp bảo mật sử dụng hàm `addlashes()`.

Trong bài này sử dụng mã hóa EUC-KR, một số ký tự nhiều byte nhất định bao gồm ký tự dấu gạch chéo ngược (`\`) dưới dạng một trong các byte của chúng. Cụ thể, các byte trong phạm vi `%a1` đến `%fe` có thể được kết hợp với ký tự dấu gạch chéo ngược (`%5c`) để tạo một ký tự nhiều byte bao gồm ký tự dấu gạch chéo ngược. Ví dụ: chuỗi `%a1%5c` đại diện cho một ký tự nhiều byte bao gồm ký tự dấu gạch chéo ngược.

Để thực hiện cuộc tấn công, kẻ tấn công có thể tạo một payload bao gồm một ký tự multi byte bao gồm ký tự dấu gạch chéo ngược, theo sau là một payload SQL injection. Ví dụ: payload `%a1%5c' OR 1=1--` sẽ được hiểu là một ký tự đơn bao gồm dấu gạch chéo ngược theo sau là payload SQL injection sử dụng cú pháp comment (`--`) để comment phần còn lại của truy vấn . Điều này sẽ cho phép kẻ tấn công bỏ qua hàm addlashes() và chèn mã SQL tùy ý vào truy vấn.

Em thử payload với guest `?id=guest%a1' or 1 like 1#&pw=guest`
- `%a1` kết hợp với ký tự `\` (hay `%5c`) được tạo bởi hảm `addlashes()` sẽ tạo thành một ký tự khác và thừa ra ký tự `'`.
- Vì dấu `=` bị filter nên em dùng `like`. ![](https://i.imgur.com/RLfHL2N.png)

Vậy là đã có thể inject thành công!

giờ chỉ cần đổi `id` thành `admin` là được, nhưng chuỗi `admin` đã bị filter nên như đã đọc được ở trên em sẽ encode chuỗi `admin` sang dạng hex là `0x61646D696E`

Payload cuối cùng là: `?id=guest%a1%27%20or%20id%20like%200x61646D696E%23&pw=guest`
![](https://i.imgur.com/SBIoWWB.png)
