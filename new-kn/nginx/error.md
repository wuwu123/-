### 413 Request Entity Too Large
```
在上传你图片的时候，提示，原因nginx  和 php上传大小限制

解决：
在nginx http{}里添加
    client_max_body_size 2m;

修改php.ini ，默认2M
    post_max_size = 2M  
    upload_max_filesize = 2M  
```

http://paysvr.qianshengqian.com/withdraw/notifybaofoo
