## ab测试

### 软件安装
```
yum install httpd-tools
```

### 使用demo
```
ab -n 100 -c 10 http://test.com/
－n  在测试会话中所执行的请求个数（本次测试总共要访问页面的次数），
－c  一次产生的请求个数（并发数）
```

### 返回结果说明
```
Server Software:        nginx/1.12.1          服务器类型
Server Hostname:        fanstalk.wxapp.com
Server Port:            80

Document Path:          /
Document Length:        31 bytes

Concurrency Level:      10              并发数
Time taken for tests:   1.769 seconds   总的请求时间
Complete requests:      100             完成请求数
Failed requests:        16              失败的请求数
   (Connect: 0, Receive: 0, Length: 16, Exceptions: 0)

 
Total transferred:      19283 bytes     整个场景中的网络传输量
HTML transferred:       3083 bytes      整个场景中的HTML内容传输量
Requests per second:    56.54 [#/sec] (mean)       吞吐率，相当于 LR 中的每秒事务数, mean 表示这是一个平均值
Time per request:       176.880 [ms] (mean)        用户平均请求等待时间
Time per request:       17.688 [ms] (mean, across all concurrent requests)   服务器平均请求处理时间
Transfer rate:          10.65 [Kbytes/sec] received  平均每秒网络上的流量，可以帮助排除是否存在网络流量过大导致响应时间延长的问题

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.3      0       2
Processing:    60  170  34.4    166     244
Waiting:       60  170  34.4    166     244
Total:         60  170  34.4    167     244

Percentage of the requests served within a certain time (ms)
  50%    167
  66%    180
  75%    198
  80%    203
  90%    222
  95%    230
  98%    239
  99%    244
 100%    244 (longest request)

 #整个场景中所有请求的响应情况。在场景中每个请求都有一个响应时间，
```