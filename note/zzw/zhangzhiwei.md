## session 和 Cookie机制
### Cookie
```
    Cookie技术是客户端的解决方案，Cookie就是由服务器发给客户端的特殊信息，而这些信息以文本文件
的方式存放在客户端，然后客户端每次向服务器发送请求的时候都会带上这些特殊的信息。
```
```
    Web应用程序是使用HTTP协议传输数据的。HTTP协议是 **无状态** 的协议。一旦数据交换完毕，客户端与服
务器端的连接就会关闭，再次交换数据需要建立新的连接。这就意味着服务器`无法从连接上跟踪会话`。要跟踪
该会话，必须引入Cookie机制
```
```
    Cookie可以弥补HTTP协议无状态的不足;cookies就是http的一个扩展。有两个http头部是专门负责
设置以及发送cookie的,它们分别是Set-Cookie以及Cookie。当服务器返回给客户端一个http响应信息时，
其中如果包含Set-Cookie这个头部时，意思就是指示客户端建立一个cookie，并且在后续的http请求中自
动发送这个cookie到服务器端，直到这个cookie过期。
* 如果cookie的生存时间是整个会话期间的话，那么浏览器会将cookie保存在内存中，浏览器关闭时就会
自动清除这个cookie。
* 另外一种情况就是保存在客户端的硬盘中，浏览器关闭的话，该cookie也不会被清除，下次打开浏览器访问对
应网站时，这个cookie就会自动再次发送到服务器端。
```

#### cookie的设置以及发送过程分为以下四步
* 客户端发送一个http请求到服务器端 服务器端发送一个http响应到客户端，其中包含Set-Cookie头部
客户端发送一个http请求到服务器端，其中包含Cookie头部 服务器端发送一个http响应到客户端
* 客户端像服务器传送数据可以通过get、post、get and post 一起使用

#### Cookie的工作原理
```
    由于HTTP是一种无状态的协议，服务器单从网络连接上无从知道客户身份。怎么办呢？就给客户端们颁
发一个通行证吧，每人一个，无论谁访问都必须携带自己通行证。这样服务器就能从通行证上确认客户身份了
```
#### Cookie属性
* String name：该Cookie的名称。Cookie一旦创建，名称便不可更改。
* Object value：该Cookie的值。如果值为Unicode字符，需要为字符编码。如果值为二进制数据，则需要使用BASE64编码。
* int maxAge：该Cookie失效的时间，单位秒。默认为–1。
** 如果为正数，则该Cookie在maxAge秒之后失效。即写到对应的Cookie文件中.
** 如果为负数，该Cookie为临时Cookie，关闭浏览器即失效，浏览器也不会以任何形式保存该Cookie。
** 如果为0，表示删除该Cookie。
* boolean secure：该Cookie是否仅被使用安全协议传输。安全协议。
** 安全协议有HTTPS，SSL等，在网络上传输数据之前先将数据加密。默认为false。并不能对Cookie
内容加密;只会在HTTPS和SSL等安全协议中传输此类Cookie
* String path：该Cookie的使用路径。
** 如果设置为“/sessionWeb/”，则只有contextPath为“/sessionWeb”的程序可以访问该Cookie。
** 如果设置为“/”，则本域名下contextPath都可以访问该Cookie。注意最后一个字符必须为“/”。
* String domain：可以访问该Cookie的域名。
** 如果设置为“.google.com”，则所有以“google.com”结尾的域名都可以访问该Cookie。注意第一个字符必须为“.”。
* String comment：该Cookie的用处说明。浏览器显示Cookie信息的时候显示该说明。
* int version：该Cookie使用的版本号。
** 0表示遵循Netscape的Cookie规范，
** 1表示遵循W3C的RFC 2109规范。



### Session机制
```
    记录用户状态；Session是服务器端使用的一种记录客户端状态的机制
    如果说Cookie机制是通过检查客户身上的“通行证”来确定客户身份的话，那么Session机制就是
通过检查服务器上的“客户明细表”来确认客户身份。Session相当于程序在服务器上建立的一份客户档
案，客户来访的时候只需要查询客户档案表就可以了。
```
```
Session保存在服务器端。为了获得更高的存取速度，服务器一般把Session放在内存里。每个用户
都会有一个独立的Session。如果Session内容过于复杂，当大量客户访问服务器时可能会导致内存溢出。
```
#### 考察如何验证用户登录状态的问题
1：用户提交包含用户名和密码的表单，发送HTTP请求。
2：服务器验证用户发来的用户名密码。
3：如果正确则把当前用户名（通常是用户对象）存储到redis中，并生成它在redis中的ID。
这个ID称为Session ID，通过Session ID可以从Redis中取出对应的用户对象， 敏感数据（比如authed=true）都存储在这个用户对象中。
4：设置Cookie为sessionId=xxxxxx|checksum并发送HTTP响应， 仍然为每一项Cookie都设置签名。
5：用户收到HTTP响应后，便看不到任何敏感数据了。在此后的请求中发送该Cookie给服务器。
6：服务器收到此后的HTTP请求后，发现Cookie中有SessionID，进行放篡改验证。
7：如果通过了验证，根据该ID从Redis中取出对应的用户对象， 查看该对象的状态并继续执行业务逻辑。
#### Session对浏览器的要求
```
虽然Session保存在服务器，对客户端是透明的，它的正常运行仍然需要客户端浏览器的支持。这是
因为Session需要使用Cookie作为识别标志。HTTP协议是无状态的，Session不能依据HTTP连接来判断是否为同一客户，因此服务器向客户端浏览器发送一个名为JSESSIONID的Cookie，它的值该Session的id（也就是HttpSession.getId()的返回值）。Session依据该Cookie来识别是否为
同一用户。
```

#### URL地址重写
```
URL地址重写是对客户端不支持Cookie的解决方案。URL地址重写的原理是将该用户Session的id信
息重写到URL地址中。服务器能够解析重写后的URL获取Session的id。
```

### Cookie与Session的区别
1: cookie数据存放在客户的浏览器上，session数据放在服务器上；
2: cookie不是很安全，别人可以分析存放在本地的COOKIE并进行COOKIE欺骗，考虑到安全应当使用session；
3: session会在一定时间内保存在服务器上。当访问增多，会比较占用你服务器的性能。考虑到减轻服务器性能方面，应当使用COOKIE；
4: 单个cookie在客户端的限制是3K，就是说一个站点在客户端存放的COOKIE不能超过3K；


### 来源地址
https://my.oschina.net/xianggao/blog/395675
