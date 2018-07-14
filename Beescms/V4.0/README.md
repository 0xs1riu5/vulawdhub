## 启动环境

```
docker-compose build
docker-compose up -d
```

## 0x01 系统内置木马

利用D盾扫描网站目录，在site/sitemap.php中存在后门
![](luffy1.png)

## 0x02 后台获取webshell

通过弱口令admin:admin进入http://127.0.0.1:8002/admin/admin.php后台
找到图片上传处
![](luffy2.png)
尝试是否可以上传wenshell，发现直接上传不行，尝试将content type改为image/jpeg,即可上传成功：
![](luffy3.png)

服务器会返回路径地址
![](luffy4.png)



[链接一](https://www.secpulse.com/archives/69910.html)

[链接二](https://www.anquanke.com/post/id/98574)


