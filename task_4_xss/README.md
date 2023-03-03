# Cross-site scripting (XSS)

## Cross-site scripting (XSS) là gì?

Cross-site scripting (còn được gọi là XSS) là một lỗ hổng bảo mật web cho phép kẻ tấn công xâm phạm các tương tác mà người dùng có với một ứng dụng dễ bị tấn công. Nó cho phép kẻ tấn công phá vỡ same-origin policy (SOP cách phân chia, ngăn cách giữa các website với nhau nhằm tránh tình huống website này có khả năng lấy dữ liệu từ website), được thiết kế để tách biệt các trang web khác nhau với nhau. Các lỗ hổng xss thường cho phép kẻ tấn công giả dạng người dùng nạn nhân, thực hiện bất kỳ hành động nào mà người dùng có thể thực hiện và truy cập vào bất kỳ dữ liệu nào của người dùng. Nếu người dùng nạn nhân có quyền truy cập đặc quyền trong ứng dụng, thì kẻ tấn công có thể có toàn quyền kiểm soát tất cả chức năng và dữ liệu của ứng dụng.

![](https://i.imgur.com/wwGpsF3.png)

## XSS hoạt động như thế nào?

Lỗi XSS xảy ra khi các ứng dụng web không kiểm tra và sàng lọc đầu vào của người dùng đúng cách. Khi một kẻ tấn công chèn vào đầu vào của người dùng, chẳng hạn như trong các trường hợp nhập liệu vào các trường biểu mẫu, thông tin độc hại có thể được thêm vào các trang web. Khi người dùng truy cập vào trang web, thông tin độc hại sẽ được thực thi trên trình duyệt của họ, cho phép kẻ tấn công tiếp cận các thông tin nhạy cảm của người dùng hoặc chiếm quyền điều khiển trang web.

![](https://media.geeksforgeeks.org/wp-content/uploads/20190516152959/Cross-Site-ScriptingXSS.png)

Bạn có thể xác nhận hầu hết các loại lỗ hổng XSS bằng cách tiêm một tải trọng khiến trình duyệt của riêng bạn thực thi một số JavaScript tùy ý. Từ lâu, người ta thường sử dụng hàm alert() cho mục đích này vì nó ngắn gọn, vô hại và khá khó bỏ lỡ khi nó được gọi thành công.


## Các dạng tấn công XSS:

Có ba loại tấn công XSS chính. Đó là:

- **Reflected XSS**, trong đó tập lệnh độc hại đến từ yêu cầu HTTP hiện tại.
- **Stored XSS**, nơi tập lệnh độc hại đến từ cơ sở dữ liệu của trang web.
- **DOM-based XSS**, trong đó lỗ hổng tồn tại trong mã phía máy khách chứ không phải mã phía máy chủ.

### Reflected cross-site scripting

![](https://media.geeksforgeeks.org/wp-content/uploads/20190516153002/reflectedXSS.png)

Reflected XSS là loại xss đơn giản nhất. Nó phát sinh khi một ứng dụng nhận dữ liệu trong một yêu cầu HTTP và bao gồm dữ liệu đó trong phản hồi ngay lập tức theo cách không an toàn.

Đây là một ví dụ đơn giản về lỗ hổng XSS được phản ánh:
```html
https://insecure-website.com/status?message=All+is+well.

<p>Status: All is well.</p>
```

Ứng dụng không thực hiện bất kỳ quá trình xử lý dữ liệu nào khác, vì vậy kẻ tấn công có thể dễ dàng thực hiện một cuộc tấn công như sau:
```html
https://insecure-website.com/status?message=<script>/*+Bad+stuff+here...+*/</script>

<p>Status: <script>/* Bad stuff here... */</script></p>
```

Nếu người dùng truy cập URL do kẻ tấn công tạo, thì tập lệnh của kẻ tấn công sẽ thực thi trong trình duyệt của người dùng, trong bối cảnh phiên của người dùng đó với ứng dụng. Tại thời điểm đó, tập lệnh có thể thực hiện bất kỳ hành động nào và truy xuất bất kỳ dữ liệu nào mà người dùng có quyền truy cập.

### Stored cross-site scripting

![](https://media.geeksforgeeks.org/wp-content/cdn-uploads/20190516153259/StoredXSS.png)

Stored XSS (còn được gọi là persistent XSS hoặc second-order XSS) phát sinh khi một ứng dụng nhận dữ liệu từ một nguồn không đáng tin cậy và bao gồm dữ liệu đó trong các phản hồi HTTP sau này theo cách không an toàn.

Dữ liệu được đề cập có thể được gửi tới ứng dụng thông qua các yêu cầu HTTP; 
Ví dụ: nhận xét về bài đăng trên blog, biệt hiệu của người dùng trong phòng trò chuyện hoặc chi tiết liên hệ trên đơn đặt hàng của khách hàng. Trong các trường hợp khác, dữ liệu có thể đến từ các nguồn không đáng tin cậy khác; 
Ví dụ: ứng dụng webmail hiển thị thư nhận được qua SMTP, ứng dụng tiếp thị hiển thị bài đăng trên mạng xã hội hoặc ứng dụng giám sát mạng hiển thị dữ liệu gói từ lưu lượng truy cập mạng.

Đây là một ví dụ đơn giản về lỗ hổng stored XSS. Ứng dụng bảng tin cho phép người dùng gửi tin nhắn, tin nhắn này sẽ được hiển thị cho người dùng khác:

```html
<p>Hello, this is my message!</p>
```

Ứng dụng không thực hiện bất kỳ quá trình xử lý dữ liệu nào khác, vì vậy kẻ tấn công có thể dễ dàng gửi thông báo tấn công người dùng khác:

```html
<p><script>/* Bad stuff here... */</script></p>
```

### DOM-based cross-site scripting

![](https://assets.website-files.com/5ff66329429d880392f6cba2/60b35d9d49c3ceace71aa233_DOM%20(Document%20Object%20Model)%20based%20XSS%20attacks.png)

DOM-based XSS (còn được gọi là DOM XSS) phát sinh khi một ứng dụng chứa một số JavaScript phía máy khách xử lý dữ liệu từ một nguồn không đáng tin cậy theo cách không an toàn, thường bằng cách ghi dữ liệu trở lại DOM.

Trong ví dụ sau, một ứng dụng sử dụng một số JavaScript để đọc giá trị từ trường đầu vào và ghi giá trị đó vào một phần tử trong HTML:

```javascript
var search = document.getElementById('search').value;
var results = document.getElementById('results');
results.innerHTML = 'You searched for: ' + search;
```

Nếu kẻ tấn công có thể kiểm soát giá trị của trường đầu vào, chúng có thể dễ dàng xây dựng một giá trị độc hại khiến tập lệnh của chính chúng thực thi:

```html
You searched for: <img src=1 onerror='/* Bad stuff here... */'>
```

Trong trường hợp điển hình, trường đầu vào sẽ được điền từ một phần của yêu cầu HTTP, chẳng hạn như tham số chuỗi truy vấn URL, cho phép kẻ tấn công thực hiện cuộc tấn công bằng URL độc hại, theo cách tương tự như reflected XSS.
## Cách khai thác XSS:
#### XSS có thể được sử dụng để làm gì?
Kẻ tấn công khai thác lỗ hổng cross-site scripting thường có thể:

- Mạo danh hoặc giả làm người dùng nạn nhân.
- Thực hiện bất kỳ hành động nào mà người dùng có thể thực hiện.
- Đọc bất kỳ dữ liệu nào mà người dùng có thể truy cập.
- Nắm bắt thông tin đăng nhập của người dùng.
- Thực hiện deface ảo của trang web.
- Tiêm chức năng trojan vào trang web.

#### Cách tìm và kiểm tra lỗ hổng XSS

Kiểm tra XSS được phản ánh và lưu trữ theo cách thủ công thường liên quan đến việc gửi một số đầu vào duy nhất đơn giản (chẳng hạn như chuỗi chữ và số ngắn) vào mọi điểm nhập trong ứng dụng, xác định mọi vị trí mà đầu vào đã gửi được trả về trong các phản hồi HTTP và kiểm tra từng vị trí riêng lẻ để xác định liệu đầu vào được chế tạo phù hợp có thể được sử dụng để thực thi JavaScript tùy ý hay không. Bằng cách này, bạn có thể xác định bối cảnh XSS xảy ra và chọn một payload phù hợp để khai thác nó.

Kiểm tra thủ công DOM-based XSS phát sinh từ các tham số URL bao gồm một quy trình tương tự: đặt một số đầu vào duy nhất đơn giản vào tham số, sử dụng các công cụ dành cho nhà phát triển của trình duyệt để tìm kiếm DOM cho đầu vào này và kiểm tra từng vị trí để xác định xem nó có thể khai thác được hay không. Tuy nhiên, các loại DOM XSS khác khó phát hiện hơn. Để tìm các lỗ hổng DOM-based trong đầu vào không dựa trên URL (chẳng hạn như document.cookie) hoặc phần chìm không dựa trên HTML (như setTimeout), không có cách nào khác là xem xét mã JavaScript, việc này có thể cực kỳ tốn thời gian.

#### Khai thác Reflected XSS
[Link to lab](https://github.com/tu3n4nh/trainingKCSC/blob/main/task_4_xss/reflected_xss.md)

#### Khai thác Stored XSS
[Link to lab](https://github.com/tu3n4nh/trainingKCSC/blob/main/task_4_xss/dom_based_xss.md)

#### Khai thác Dom-based XSS
[Link to lab](https://github.com/tu3n4nh/trainingKCSC/blob/main/task_4_xss/stored_xss.md)

## Cách ngăn chặn lỗi XSS:

1. **Kiểm tra và sàng lọc đầu vào của người dùng:** Ứng dụng web cần kiểm tra và sàng lọc tất cả các đầu vào của người dùng, bao gồm các trường nhập liệu, phần mềm người dùng và URL. Kiểm tra xác thực đầu vào của người dùng, bao gồm kiểm tra định dạng, giá trị và kích thước của đầu vào, đồng thời sàng lọc các ký tự đặc biệt và mã độc.

2. **Mã hóa dữ liệu trên trang web:** Để ngăn chặn lỗi XSS, các ứng dụng web cần mã hóa tất cả các dữ liệu trên trang web, bao gồm các thông tin nhạy cảm như tên người dùng, mật khẩu và thông tin tài khoản. Sử dụng mã hóa bảo vệ dữ liệu giúp ngăn chặn các kẻ tấn công tiếp cận thông tin nhạy cảm của người dùng.

3. **Sử dụng các thư viện mã hóa và chống giả mạo thông tin:** Các ứng dụng web có thể sử dụng các thư viện mã hóa và chống giả mạo thông tin để đảm bảo an toàn cho dữ liệu của người dùng. Các thư viện này bao gồm các công cụ để xác thực dữ liệu, mã hóa dữ liệu và kiểm tra các ký tự đặc biệt và mã độc.

4. **Đảm bảo rằng các trình duyệt của người dùng luôn được cập nhật mới nhất:** Khi các trình duyệt của người dùng không được cập nhật mới nhất, chúng có thể có các lỗ hổng bảo mật, cho phép kẻ tấn công khai thác các lỗ hổng này để thực hiện các cuộc tấn công XSS. Vì vậy, các ứng dụng web cần đảm bảo rằng các trình duyệt của người dùng luôn được cập nhật mới nhất để tránh các lỗ hổng bảo mật.

5. **Sử dụng các response header thích hợp:** Để ngăn XSS trong các response HTTP không có ý định chứa bất kỳ HTML hoặc JavaScript nào, bạn có thể sử dụng các tiêu đề Content-Type và X-Content-Type-Options để đảm bảo rằng các trình duyệt diễn giải các response theo cách bạn muốn.

6. **Sử dụng CSP (Content Security Policy):** CSP là một cơ chế bảo mật cho phép các quản trị viên cấu hình chính sách bảo mật trên trang web để hạn chế hoặc ngăn chặn việc chèn vào các đoạn mã độc và các trang web có nguồn gốc không an toàn. CSP định nghĩa một danh sách các nguồn có thể tr

