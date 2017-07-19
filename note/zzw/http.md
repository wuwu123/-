## HTTP
HTTP是一种 **通用的、无状态** 的 **应用层**  协议，适用于分布式、协同的、超媒体信息系统。通
过一些扩展（如请求方法、错误码、头信息），HTTP可用于超文本外的其他用途，例如命名服务器、分布式
对象管理。HTTP的特点在于数据表示的类型与协商，允许建立系统时不必考虑数据是如何传输的。

HTTP/1.1连接应该是 **持久连接**。这不仅能够减小TCP连接的内存、CPU开销，减小了TCP包的数量，
同时提供了更短的延迟和及时的错误反馈。此时，请求与应答可以形成管道而不必等上一个连接的关闭。


### 代理
是指转发代理，它接受URI请求，重写部分消息，然后把重写过的消息转发至URI标识的服务器。
### 网关
是指接收代理，它运行在其他服务器之上，需要时可以为背后的服务器翻译请求。
### 隧道
相当于两个连接的中转站，但不会改变消息。当通信需要通过一个中介时可以使用隧道，即使该中介不理解消息内容。