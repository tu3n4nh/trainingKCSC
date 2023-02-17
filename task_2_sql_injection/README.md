# Task 2 SQL Injection

## SQL injection (SQLi) là gì?

SQL injection (SQLi) là một lỗ hổng bảo mật web cho phép kẻ tấn công can thiệp vào các truy vấn mà ứng dụng thực hiện đối với cơ sở dữ liệu của nó. Nó thường cho phép kẻ tấn công xem dữ liệu mà thông thường không thể truy xuất được. Điều này có thể bao gồm dữ liệu thuộc về người dùng khác hoặc bất kỳ dữ liệu nào khác mà bản thân ứng dụng có thể truy cập. Trong nhiều trường hợp, kẻ tấn công có thể sửa đổi hoặc xóa dữ liệu này, gây ra những thay đổi liên tục đối với nội dung hoặc hành vi của ứng dụng.

Trong một số trường hợp, kẻ tấn công có thể leo thang một cuộc tấn công SQL injection để xâm phạm máy chủ hoặc cơ sở back-end khác hoặc thực hiện một cuộc tấn công từ chối dịch vụ. 

## Tác động của một cuộc tấn công SQL injection thành công là gì?

Một cuộc tấn công SQL injection thành công có thể dẫn đến truy cập trái phép vào dữ liệu nhạy cảm, chẳng hạn như mật khẩu, chi tiết thẻ tín dụng hoặc thông tin người dùng cá nhân. Nhiều vụ vi phạm dữ liệu nghiêm trọng trong những năm gần đây là kết quả của các cuộc tấn công SQL injection, dẫn đến thiệt hại về uy tín và tiền phạt theo quy định. Trong một số trường hợp, kẻ tấn công có thể chiếm được một backdoor vào hệ thống của tổ chức, dẫn đến một sự xâm phạm lâu dài có thể không được chú ý trong một thời gian dài.

## Ví dụ SQL injection

Có rất nhiều lỗ hổng SQL injection, các cuộc tấn công và kỹ thuật phát sinh trong các tình huống khác nhau. Một số ví dụ SQL injection phổ biến bao gồm:

- Retrieving hidden data, nơi bạn có thể sửa đổi truy vấn SQL để trả về kết quả bổ sung.
- Subverting application logic, nơi bạn có thể thay đổi truy vấn để can thiệp vào logic của ứng dụng.
- UNION attacks, nơi bạn có thể truy xuất dữ liệu từ các bảng cơ sở dữ liệu khác nhau.
- Examining the database, nơi bạn có thể trích xuất thông tin về phiên bản và cấu trúc của cơ sở dữ liệu.
- Blind SQL injection, trong đó kết quả của truy vấn mà bạn kiểm soát không được trả về trong phản hồi của ứng dụng.

### Retrieving hidden data

Hãy xem xét một ứng dụng mua sắm hiển thị các sản phẩm trong các danh mục khác nhau. Khi người dùng nhấp vào danh mục Quà tặng, trình duyệt của họ sẽ yêu cầu URL:
```sql
https://insecure-website.com/products?category=Gifts
```

Điều này khiến ứng dụng tạo một truy vấn SQL để truy xuất thông tin chi tiết về các sản phẩm có liên quan từ cơ sở dữ liệu:

```sql
SELECT * FROM products WHERE category = 'Gifts' AND released = 1
```

Truy vấn SQL này yêu cầu cơ sở dữ liệu trả về:

- tất cả giá trị (*)
- từ bảng sản phẩm
- ở đâu có category là Gifts
- và released là 1.

Sự hạn chế `released = 1` đang được sử dụng để ẩn các sản phẩm không được phát hành. Đối với các sản phẩm chưa được phát hành, có lẽ là `released = 0`.

Ứng dụng không thực hiện bất kỳ biện pháp bảo vệ nào chống lại các cuộc tấn công SQL injection, vì vậy kẻ tấn công có thể tạo ra một cuộc tấn công như:

```sql
https://insecure-website.com/products?category=Gifts'--
```

Điều này dẫn đến truy vấn SQL:
```sql
SELECT * FROM products WHERE category = 'Gifts'--' AND released = 1
```

Điều quan trọng ở đây là hai dấu gạch ngang -- là một comment trong SQL và có nghĩa là phần còn lại của truy vấn được diễn giải dưới dạng một comment. Thao tác này sẽ loại bỏ phần còn lại của truy vấn một cách hiệu quả, vì vậy nó không còn bao gồm `AND released = 1` Điều này có nghĩa là tất cả các sản phẩm được hiển thị, bao gồm cả các sản phẩm chưa được phát hành.

Đi xa hơn, kẻ tấn công có thể khiến ứng dụng hiển thị tất cả các sản phẩm trong bất kỳ danh mục nào, kể cả những danh mục mà chúng không biết:

```sql
https://insecure-website.com/products?category=Gifts'+OR+1=1--
```

Điều này dẫn đến truy vấn SQL:

```sql
SELECT * FROM products WHERE category = 'Gifts' OR 1=1--' AND released = 1
```

Truy vấn đã sửa đổi sẽ trả về tất cả các mục có `category = Gifts` hoặc `1 = 1`. Mà `1 = 1` luôn đúng nên truy vấn sẽ trả về tất cả các mục.


### Lất đổ logic của ứng dụng

Hãy xem xét một ứng dụng cho phép người dùng đăng nhập bằng `username` và `password`. Nếu người dùng gửi username `wiener` và password `bluecheese`, ứng dụng sẽ kiểm tra thông tin đăng nhập bằng cách thực hiện truy vấn SQL sau:

```sql
SELECT * FROM users WHERE username = 'wiener' AND password = 'bluecheese'
```

Nếu truy vấn trả về thông tin chi tiết của người dùng thì đăng nhập thành công. Nếu không, nó bị từ chối.

Tại đây, kẻ tấn công có thể đăng nhập với tư cách là bất kỳ người dùng nào mà không cần `password` chỉ bằng cách sử dụng chuỗi comment `--` để xóa kiểm tra password khỏi mệnh đề WHERE của truy vấn. Ví dụ: gửi username `administrator'--` và `password` trống dẫn đến truy vấn sau:

```sql
SELECT * FROM users WHERE username = 'administrator'--' AND password = ''
```

Truy vấn này trả về người dùng có username là administrator và đăng nhập thành công với tư cách là người dùng đó.

### Lấy dữ liệu từ các bảng cơ sở dữ liệu khác

Trong trường hợp kết quả của truy vấn SQL được trả về trong các phản hồi của ứng dụng, kẻ tấn công có thể tận dụng lỗ hổng SQL injection để truy xuất dữ liệu từ các bảng khác trong cơ sở dữ liệu. Điều này được thực hiện bằng cách sử dụng từ khóa UNION, cho phép bạn thực hiện một truy vấn SELECT bổ sung và nối các kết quả vào truy vấn ban đầu.

Ví dụ: nếu một ứng dụng thực thi truy vấn sau có chứa thông tin nhập "Gifts" từ phía người dùng:

```sql
SELECT name, description FROM products WHERE category = 'Gifts'
```

sau đó kẻ tấn công có thể sửa payload thành:
```sql
' UNION SELECT username, password FROM users--
```

Điều này sẽ khiến ứng dụng trả về tất cả `username` và `password` trong bảng `users`.

[Đọc thêm về tấn công UNION](https://portswigger.net/web-security/sql-injection/union-attacks)

### Kiểm tra cơ sở dữ liệu

Sau khi xác định ban đầu lỗ hổng SQL injection, thông thường sẽ rất hữu ích nếu có được một số thông tin về chính cơ sở dữ liệu. Thông tin này thường có thể mở đường cho việc khai thác thêm.

Bạn có thể truy vấn chi tiết phiên bản của cơ sở dữ liệu. Cách thực hiện điều này phụ thuộc vào loại cơ sở dữ liệu, vì vậy bạn có thể suy ra loại cơ sở dữ liệu từ bất kỳ kỹ thuật nào hoạt động. Ví dụ: trên Oracle, bạn có thể thực thi:

```sql
SELECT * FROM v$version
```

Bạn cũng có thể xác định những bảng cơ sở dữ liệu nào tồn tại và chúng chứa những cột nào. Ví dụ: trên hầu hết các cơ sở dữ liệu, bạn có thể thực hiện truy vấn sau để liệt kê các bảng:

```sql
SELECT * FROM information_schema.tables
```

[Đọc thêm về kiểm tra cơ sở dữ liệu](https://portswigger.net/web-security/sql-injection/examining-the-database)

### Lỗ hổng SQL injection mù

Nhiều trường hợp SQL injection là lỗ hổng mù. Điều này có nghĩa là ứng dụng không trả về kết quả của truy vấn SQL hoặc chi tiết về bất kỳ lỗi cơ sở dữ liệu nào trong các phản hồi của nó. Các lỗ hổng mù vẫn có thể bị khai thác để truy cập dữ liệu trái phép, nhưng các kỹ thuật liên quan thường phức tạp hơn và khó thực hiện hơn.

Tùy thuộc vào bản chất của lỗ hổng và cơ sở dữ liệu liên quan, các kỹ thuật sau đây có thể được sử dụng để khai thác lỗ hổng Blind SQL injection:


- Bạn có thể thay đổi logic của truy vấn để kích hoạt sự khác biệt có thể phát hiện được trong phản hồi của ứng dụng tùy thuộc vào độ chính xác của một điều kiện. Điều này có thể liên quan đến việc đưa một điều kiện mới vào logic Boolean nào đó hoặc kích hoạt một cách có điều kiện một lỗi chẳng hạn như chia cho số không.
- Bạn có thể kích hoạt thời gian trễ một cách có điều kiện trong quá trình xử lý truy vấn, cho phép bạn suy ra tính đúng đắn của điều kiện dựa trên thời gian mà ứng dụng cần để phản hồi.
- Bạn có thể kích hoạt tương tác out-of-band, sử dụng các kỹ thuật [OAST](https://portswigger.net/burp/application-security-testing/oast). Kỹ thuật này cực kỳ mạnh mẽ và hoạt động trong những tình huống mà các kỹ thuật khác không làm được. Thông thường, bạn có thể lọc dữ liệu trực tiếp qua out-of-band channel, chẳng hạn như bằng cách đặt dữ liệu vào tra cứu DNS cho miền mà bạn kiểm soát.

## Cách ngăn chặn SQL injection

May thay, mặc dù SQL rất nguy hại nhưng cũng dễ phòng chống. Gần đây, hầu như chúng ta ít viết SQL thuần mà toàn sử dụng ORM (Object-Relational Mapping) framework. Các framework web này sẽ tự tạo câu lệnh SQL nên hacker cũng khó tấn công hơn.

Tuy nhiên, có rất nhiều site vẫn sử dụng SQL thuần để truy cập dữ liệu. Đây chính là mồi ngon cho hacker. Để bảo vệ bản thân trước SQL Injection, ta có thể thực hiện các biện pháp sau.

- Lọc dữ liệu từ người dùng: Cách phòng chống này tương tự như XSS. Ta sử dụng filter để lọc các kí tự đặc biệt (; ” ‘) hoặc các từ khoá (SELECT, UNION) do người dùng nhập vào. Nên sử dụng thư viện/function được cung cấp bởi framework. Viết lại từ đầu vừa tốn thời gian vừa dễ sơ sót.
- Không cộng chuỗi để tạo SQL: Sử dụng parameter thay vì cộng chuỗi. Nếu dữ liệu truyền vào không hợp pháp, SQL Engine sẽ tự động báo lỗi, ta không cần dùng code để check.
- Không hiển thị exception, message lỗi: Hacker dựa vào message lỗi để tìm ra cấu trúc database. Khi có lỗi, ta chỉ hiện thông báo lỗi chứ đừng hiển thị đầy đủ thông tin về lỗi, tránh hacker lợi dụng.
- Phân quyền rõ ràng trong DB: Nếu chỉ truy cập dữ liệu từ một số bảng, hãy tạo một account trong DB, gán quyền truy cập cho account đó chứ đừng dùng account root hay sa. Lúc này, dù hacker có inject được sql cũng không thể đọc dữ liệu từ các bảng chính, sửa hay xoá dữ liệu.
- Backup dữ liệu thường xuyên: Các cụ có câu “cẩn tắc vô áy náy”. Dữ liệu phải thường xuyên được backup để nếu có bị hacker xoá thì ta vẫn có thể khôi phục được. Còn nếu cả dữ liệu backup cũng bị xoá luôn thì … chúc mừng bạn, update CV rồi tìm cách chuyển công ty thôi!

Ví dụ: Với việc xử lý login dưới đây web sẽ dễ dàng bị sqli
```php
    include 'db_conn.php';

    session_start();
    if(isset($_POST['register'])) {
        header('Location: register.php');
    }
    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $password = md5($password);

        $sql = "SELECT * FROM `task_1` WHERE username = '$username' AND password = '$password'";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        if($row == 0) {
            echo "<script type='text/javascript'>alert('Wrong username or password!');</script>";
        } else {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: index.php?id='.$row['id']);
        }
    }
```

Một cách đơn giản để ngăn chặn SQL inject là filter ký tự được nhập vào, ta có thể sửa code trên như sau:
```php
    include 'db_conn.php';

    session_start();
    if(isset($_POST['register'])) {
        header('Location: register.php');
    }
    if(isset($_POST['login'])) {
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);
        $password = md5($password);

        $sql = "SELECT * FROM `task_1` WHERE username = '$username' AND password = '$password'";
        $query = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($query);
        if($row == 0) {
            echo "<script type='text/javascript'>alert('Wrong username or password!');</script>";
        } else {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: index.php?id='.$row['id']);
        }
    }
```

Ta sử dụng `real_escape_string()` để escape tham số đầu vào `username` và `password` trước khi đưa chúng vào truy vấn SQL. Điều này có thể giúp ngăn chặn các cuộc tấn công SQL injection. Tuy nhiên, điều quan trọng cần lưu ý là việc sử dụng các prepared statements và ràng buộc tham số vẫn là cách an toàn hơn để lọc dữ liệu đầu vào và ngăn chặn việc tiêm SQL.

Hầu hết các trường hợp SQL injection có thể được ngăn chặn bằng cách sử dụng parameterized queries (còn được gọi là  prepared statements) thay vì nối chuỗi trong truy vấn.

Đoạn mã sau dễ bị tấn công bởi SQL injection vì đầu vào của người dùng được nối trực tiếp vào truy vấn:

```sql=
String query = "SELECT * FROM products WHERE category = '"+ input + "'";
Statement statement = connection.createStatement();
ResultSet resultSet = statement.executeQuery(query);
```

Mã này có thể được viết lại dễ dàng theo cách ngăn đầu vào của người dùng can thiệp vào cấu trúc truy vấn:

```sql=
PreparedStatement statement = connection.prepareStatement("SELECT * FROM products WHERE category = ?");
statement.setString(1, input);
ResultSet resultSet = statement.executeQuery();
```

Ta có thể thay bằng:

```php
    include 'db_conn.php';

    session_start();
    if(isset($_POST['register'])) {
        header('Location: register.php');
    }
    if(isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM task_1 WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 0) {
            echo "<script type='text/javascript'>alert('Wrong username or password!');</script>";
        } else {
            $row = $result->fetch_assoc();
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header('Location: index.php?id='.$row['id']);
        }
    }
```

Ta sử dụng các prepared statements để liên kết các biến `username` và `password` với các trình giữ chỗ trong truy vấn SQL. Phương thức bind_param() liên kết các biến với trình giữ chỗ và giúp ngăn chặn SQL injection bằng cách tự động thoát bất kỳ ký tự đặc biệt nào. Phương thức exec() chạy truy vấn và phương thức get_result() lấy kết quả. Sau đó, chúng tôi kiểm tra xem có bất kỳ hàng nào được trả về từ truy vấn hay không bằng cách sử dụng thuộc tính num_rows của đối tượng kết quả và nếu có, chúng tôi tìm nạp hàng đầu tiên bằng phương thức fetch_assoc() và đặt các biến phiên trước khi chuyển hướng người dùng.

Prepared statements có thể được sử dụng cho bất kỳ tình huống nào mà đầu vào không đáng tin cậy xuất hiện dưới dạng dữ liệu trong truy vấn, bao gồm mệnh đề WHERE và các giá trị trong câu lệnh INSERT hoặc UPDATE. Chúng không thể được sử dụng để xử lý đầu vào không đáng tin cậy trong các phần khác của truy vấn, chẳng hạn như tên bảng hoặc cột hoặc mệnh đề ORDER BY. Chức năng ứng dụng đặt dữ liệu không đáng tin cậy vào các phần đó của truy vấn sẽ cần thực hiện một cách tiếp cận khác, chẳng hạn như liệt kê trắng các giá trị đầu vào được phép hoặc sử dụng logic khác để cung cấp hành vi được yêu cầu.



Để một truy vấn được tham số hóa có hiệu quả trong việc ngăn chặn việc tiêm SQL, chuỗi được sử dụng trong truy vấn phải luôn là một hằng số được mã hóa cứng và không bao giờ được chứa bất kỳ dữ liệu biến đổi nào từ bất kỳ nguồn gốc nào. Đừng cố gắng quyết định từng trường hợp xem một mục dữ liệu có đáng tin cậy hay không và tiếp tục sử dụng nối chuỗi trong truy vấn cho các trường hợp được coi là an toàn. Tất cả đều quá dễ mắc sai lầm