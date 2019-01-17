### ssh 免密码登录
> 生成公钥和私钥

    ssh-keygen

>创建 ~/.ssh/authorized_keys 文件

    将id_rsa.pub 复制到里面

> 若是ssh无法登录，则修改权限


<hr>

### python3的安装
>依赖安装

```
yum -y install zlib*
```

>安装

```
sudo mkdir /usr/local/python3 # 创建安装目录

# 下载 Python 源文件
wget --no-check-certificate https://www.python.org/ftp/python/3.6.0/Python-3.6.0.tgz
# 注意：wget获取https的时候要加上：--no-check-certificate

tar -xzvf Python-3.6.0.tgz # 解压缩包

cd Python-3.6.0 # 进入解压目录

sudo ./configure --prefix=/usr/local/python3 # 指定创建的目录

sudo make

sudo make install
```

>软连

```
sudo ln -s /usr/local/python3/bin/python3 /usr/bin/python3
```

>pip安装

```
# 下载源代码
wget --no-check-certificate https://github.com/pypa/pip/archive/9.0.1.tar.gz

tar -zvxf 9.0.1 -C pip-9.0.1    # 解压文件

cd pip-9.0.1

# 使用 Python 3 安装
python3 setup.py install
```

> 创建快捷方式

```
sudo ln -s /usr/local/python3/bin/pip /usr/bin/pip3
```

<hr>

### php 集成环境 [连接](https://github.com/lj2007331/lnmp)
> 安装软件启动

```
Nginx/Tengine/OpenResty:

service nginx {start|stop|status|restart|reload|configtest}
MySQL/MariaDB/Percona:

service mysqld {start|stop|restart|reload|status}
PostgreSQL:

service postgresql {start|stop|restart|status}
MongoDB:

service mongod {start|stop|status|restart|reload}
PHP:

service php-fpm {start|stop|restart|reload|status}
HHVM:

service supervisord {start|stop|status|restart|reload}
Apache:

service httpd {start|restart|stop}
Pure-Ftpd:

service pureftpd {start|stop|restart|status}
Redis:

service redis-server {start|stop|status|restart|reload}
Memcached:

service memcached {start|stop|status|restart|reload}
```
