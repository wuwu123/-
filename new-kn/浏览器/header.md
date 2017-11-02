### X-Forwarded-For 和 X-Real-IP
```
一般来说，X-Forwarded-For是用于记录代理信息的，每经过一级代理(匿名代理除外)，代理服务器都会把这次请求的来源IP追加在X-Forwarded-For中

来自4.4.4.4的一个请求，header包含这样一行

X-Forwarded-For: 1.1.1.1, 2.2.2.2, 3.3.3.3
代表 请求由1.1.1.1发出，经过三层代理，第一层是2.2.2.2，第二层是3.3.3.3，而本次请求的来源IP4.4.4.4是第三层代理

而X-Real-IP，没有相关标准，上面的例子，如果配置了X-Read-IP，可能会有两种情况

// 最后一跳是正向代理，可能会保留真实客户端IP
X-Real-IP: 1.1.1.1
// 最后一跳是反向代理，比如Nginx，一般会是与之直接连接的客户端IP
X-Real-IP: 3.3.3.3
所以 ，如果只有一层代理，这两个头的值就是一样的


X-Forwarded-For确实是一般的做法
1. 他在正向(如squid)反向(如nginx)代理中都是标准用法，而正向代理中是没有x-real-ip相关的标准的，也就是说，如果用户访问你的 nginx反向代理之前，还经过了一层正向代理，你即使在nginx中配置了x-real-ip，取到的也只是正向代理的IP而不是客户端真实IP
2. 大部分nginx反向代理配置文章中都没有推荐加上x-real-ip ，而只有x-forwarded-for，因此更通用的做法自然是取x-forwarded-for
3. 多级代理很少见，只有一级代理的情况下二者是等效的
4. 如果有多级代理，x-forwarded-for效果是大于x-real-ip的，可以记录完整的代理链路
```
[X-Forwarded-For](https://imququ.com/post/x-forwarded-for-header-in-http.html)
