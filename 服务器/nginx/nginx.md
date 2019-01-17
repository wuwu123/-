### 什么是nginx
```
Nginx 最初是作为一个 Web 服务器创建的,但是还可以做反向代理使用

Nginx 体系结构由 master 进程和其 worker 进程组成。

master 读取配置文件，并维护 worker 进程，而 worker 则会对请求进行实际处理。
```

### 基本命令
```
nginx 启动

nginx -s stop - 快速关闭

nginx -s quit - 优雅关闭 (等待 worker 线程完成处理)

nginx -s reload - 重载配置文件

nginx -s reopen - 重新打开日志文件
```

### 配置文件加载顺序
```
/etc/nginx/nginx.conf,

/usr/local/etc/nginx/nginx.conf，或

/usr/local/nginx/conf/nginx.conf
```

### 配置文件
```nginx
user  www www;
worker_processes  auto;

error_log  logs/error.log;
error_log  logs/error.log  notice;
error_log  logs/error.log  info;

pid        logs/nginx.pid;

#打开的文件数目，一般为worker_connections*2
worker_rlimit_nofile 2048;
events {
    use epoll;

    #它指定一个工作进程可以一次打开多少个连接
    worker_connections  2048;
}


http {
    include       mime.types;
    default_type  application/octet-stream;

    #log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
    #                  '$status $body_bytes_sent "$http_referer" '
    #                  '"$http_user_agent" "$http_x_forwarded_for"';

    #access_log  logs/access.log  main;

    #关闭键 对象复制到缓存，缓存复制到缓冲区；现在对象复制到缓存，缓存传递指针到socket
    sendfile        on;

    
    # 每次发送的数据包，都等待达到最大长度在发送
    sendfile on;
    tcp_nopush     on;


    #关闭tcp 200ms的延迟，当tcp太小的时候，会等200ms延迟ask应答时间
    tcp_nodelay on;

    keepalive_timeout  65;

  # gzip压缩功能设置
    gzip on;
    gzip_min_length 1k;
    gzip_buffers    4 16k;
    gzip_http_version 1.0;
    gzip_comp_level 6;
    gzip_types text/html text/plain text/css text/javascript application/json application/javascript application/x-javascript application/xml;
    gzip_vary on;

  # http_proxy 设置
    client_max_body_size   10m;
    client_body_buffer_size   128k;
    proxy_connect_timeout   75;
    proxy_send_timeout   75;
    proxy_read_timeout   75;
    proxy_buffer_size   4k;
    proxy_buffers   4 32k;
    proxy_busy_buffers_size   64k;
    proxy_temp_file_write_size  64k;
    proxy_temp_path   /usr/local/nginx/proxy_temp 1 2;

  # 设定负载均衡后台服务器列表 
    upstream  backend  { 
              #ip_hash; 
              server   192.168.10.100:8080 max_fails=2 fail_timeout=30s ;  
              server   192.168.10.101:8080 max_fails=2 fail_timeout=30s ;  
    }

    #很重要的虚拟主机配置
    server {
        listen       80;
        server_name  itoatest.example.com;
        root   /apps/oaapp;

        charset utf-8;
        access_log  logs/host.access.log  main;

        #对 / 所有做负载均衡+反向代理
        location / {
            root   /apps/oaapp;
            index  index.jsp index.html index.htm;

            proxy_pass        http://backend;  
            proxy_redirect off;
            # 后端的Web服务器可以通过X-Forwarded-For获取用户真实IP
            proxy_set_header  Host  $host;
            proxy_set_header  X-Real-IP  $remote_addr;  
            proxy_set_header  X-Forwarded-For  $proxy_add_x_forwarded_for;
            proxy_next_upstream error timeout invalid_header http_500 http_502 http_503 http_504;

        }

        #静态文件，nginx自己处理，不去backend请求tomcat
        location  ~* /download/ {  
            root /apps/oa/fs;  

        }
        location ~ .*\.(gif|jpg|jpeg|bmp|png|ico|txt|js|css)$   
        {   
            root /apps/oaapp;   
            expires      7d; 
        }
        location /nginx_status {
            stub_status on;
            access_log off;
            allow 192.168.10.0/24;
            deny all;
        }

        location ~ ^/(WEB-INF)/ {   
            deny all;   
        }
        #error_page  404              /404.html;

        # redirect server error pages to the static page /50x.html
        #
        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
        }
    }

    # https 的配置
    server {
        #开始监听端口 443
        listen       443;
        ssl on;
        ssl_certificate cert/knowyourself.pem;
        ssl_certificate_key cert/knowyourself.key;
        
        #为了在 TLS 握手期间节省一个 roundtrip 时间，以及生成新密钥的计算开销，我们可以重x新在第一个请求期间生成的会话参数。客户端和服务器可以将会话参数存储在会话 ID 密钥的后面。在接下来的 TLS 握手过程中，客户端可以发送会话 ID，如果服务器在缓存中仍然有正确的条目，那么会重用前一个会话所生成的参数
        ssl_session_timeout 5m;
        ssl_protocols TLSv1 TLSv1.1 TLSv1.2;

        
        ssl_ciphers ECDHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES128-GCM-SHA256:DHE-RSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-SHA384:ECDHE-RSA-AES128-SHA256:ECDHE-RSA-AES256-SHA:ECDHE-RSA-AES128-SHA:DHE-RSA-AES256-SHA:DHE-RSA-AES128-SHA;
        ssl_session_cache shared:SSL:50m;
        ssl_prefer_server_ciphers on;

        server_name  wechat-mapi-test.knowyourself.cc;
        access_log  /data/logs/nginx/wechat-mapi-test.log access;

        set $root /home/www/wechat_api/admin/web;
        set $index index.php;
        index $index;
        root $root;
        add_header 'Access-Control-Allow-Origin' "$http_origin";
        add_header 'Access-Control-Allow-Credentials' 'true';
        add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS';
        add_header 'Access-Control-Allow-Headers' 'Accept,Authorization,Cache-Control,Content-Type,DNT,If-Modified-Since,Keep-Alive,Origin,User-Agent,X-Mx-ReqToken,X-Requested-With,topsession,responsetype,content-type';
        if ($request_method = 'OPTIONS') {
            return 204;
        }
        location / {
            try_files $uri $uri/ /index.php?$args;
        }
        location ~ \.php$ {
            root $root;
            fastcgi_pass 127.0.0.1:9000;
            fastcgi_index $index;
            fastcgi_param SCRIPT_FILENAME $root$fastcgi_script_name;
            fastcgi_param SERVER_ENV     local;
            include    fastcgi_params;
        }
    }

  ## 其它虚拟主机，server 指令开始
}
```


### 文章连接来源
[https://www.oschina.net/translate/nginx-tutorial-basics-concepts](https://www.oschina.net/translate/nginx-tutorial-basics-concepts)
