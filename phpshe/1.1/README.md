## 启动环境

```
docker-compose build
docker-compose up -d
```

phpshe v1.1系统重装漏洞

## 0x01 getshell
直接访问http://127.0.0.1/install

在安装时，在数据表前缀写入：');phpinfo();
![](README/5C9E7C83-C177-411D-AAAA-3523AC17B55E.png)


看一下config.php

![](README/4D7D62E2-B41D-489C-97FB-A04DFF70C545.png)

![](README/495F968C-5003-488D-9B8C-47C4B5557C35.png)


