## 启动环境


```
docker-compose build
docker-compose up -d
```


## 漏洞一:

在/app下存在菜刀木马，可通过D盾扫描获得
地址:http://127.0.0.1:8002/admin/trojan.php 密码:pass

## 漏洞二:

在http://127.0.0.1:8002/show.php?id=33存在sql注入漏洞,可以执行sqlmap -u "http://127.0.0.1:8002/show.php?id=33"

## 漏洞三:

在后台登录地址http://127.0.0.1:8002/admin/login.php 的用户名参数存在注入 利用万能密码admin' or '1'='1'#可以登录




 

