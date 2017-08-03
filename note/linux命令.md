### 常用命令
```
别名alias、unalias 分组cut -dfc 、 grep
head -n num filemame
	选取文件的前几行内容
tail -n num filename
	读取文件的最后几行
touch
	创建文件，修改文件的时间
chgrp group filemame
	修改文件所属的组
查看ip地址
	ifconfig | grep "inet "
查看进程
    ps -ef | grep mysqld
    	查看当前所有的进程
    	-e显示所有的程序
    ps aux | grep mysqld
查看文件大小
    df可以查看一级文件夹大小、使用比例、档案系统及其挂入点，但对文件却无能为力。
    du可以查看文件及文件夹的大小
    du -h .
    	查看文件的大小
    du -ch -d 1
    	-c 计算文件的总得大小
    du -sh abc
    	-s表示总结的意思，即只列出一个总结的值
    du -h –exclude=’*xyz*’
    	列出所有abc目录中的目录名不包括xyz字符串的目录的大小
    linux
        du -h --max-depth=1 /home
    mac
        du -h -d depth=1 www/
tput命令
	http://www.ibm.com/developerworks/cn/aix/library/au-learningtput/
	移动或更改光标、更改文本属性，以及清除终端屏幕的特定区域
	tput sc
		存储当前的光标的位置
	tput cup 23 45
		将光标移动到第23列第45行
	tput rc
		光标必须返回到使用 tput sc 保存的原始位置
	tput el
		从当前光标位置到行尾的数据
	tput ed
		清除从当前光标位置到设备末尾的数据
	tput cols
		您在目标设备上可以使用的宽度
	tput lines
		要查找行数（即行当前的高度）
	tput civis
		将光标转换为不可见可以使数据滚动时的屏幕看起来更整洁
	tput cnorm
		将光标再次转变为可见

rsync同步文件
	rsync main.c machineB:/home/userB
		0：只要源和目标不一致就会触发
		1：文件的权限可能不一样【若是目标文件不存在的时候会保持一样】
		2：只会在当前登录的用户下面创建文件，但是为root用户是会同步
		3：不会同步文件的“modify time”
	-t
		rsync -t main.c machineB:/home/userB
		1：当文件的时间戳和文件大小不一样的时候会触发，保持文件的时间戳不一样
	-I
		rsync -I main.c machineB:/home/userB
		1：挨个文件去同步，速度比较慢
		2：无论情况如何，目的端的文件的modify time总会被更新到当前时刻
	-v
		rsync -vI main.c machineB:/home/userB
		展示文件的同步信息
		v阅多现实的文件信息越多
		sent 81 bytes  received 42 bytes  246.00 bytes/sec
		total size is 11  speedup is 0.09
	-z
		rsync -z main.c machineB:/home/userB
		文件的传输进行压缩
	-r
		rsync -r superman machineB:/home/userB
		递归循环的同步数据，在第一次的时候需要加上。
	-l
		当文件是软连接的时候，将会自动过滤掉，
		加上-l则会一样的同步数据
	-p
		保持目标文件和当前的文件的权限完全一致
	-u
		更新update
		仅仅进行更新，也就是跳过所有已经存在于DST，并且文件时间晚于要备份的文件。(不覆盖更新的文件)
	-g  -o
		这两个选项是一对，用来保持文件的属组(group)和属主（owner），作用应该很清晰明了
	–delete选项、–delete-excluded选项和–delete-after选项
		1 –delete：如果源端没有此文件，那么目的端也别想拥有，删除之。（如果你使用这个选项，就必须搭配-r选项一起）
		2 –delete-excluded：专门指定一些要在目的端删除的文件。
		3 –delete-after：默认情况下，rsync是先清理目的端的文件再开始数据同步；如果使用此选项，则rsync会先进行数据同步，都完成后再删除那些需要清理的文件。
			rsync -n --delete -r . machineB:/home/userB/
			那就是-n选项，它是一个吓唬人的选项，它会用受影响的文件列表来警告你，但不会真的去删除
	–exclude选项和–exclude-from选项
		如果你不希望同步一些东西到目的端的话，可以使用–exclude选项来隐藏。
		如果你要隐藏的隐私太多的话，–exclude-from选项，让你可以把隐私一一列在一个文件里，然后让rsync直接读取这个文件就好了
	–partial选项
		断点续传
		人们总是要手动写–partial –progress，觉得太费劲了，倒不如用一个新的选项来代替，于是-P应运而生了。
	–progress选项

find
	find . -name abc
		.当前文件和所有子文件
	find . -type d -name xyz
		-type：表示设定类型，d表示文件夹类型，可以替换为f(普通文件)、l(链接文件)
	find . -user roc
		-user：用于设定所属用户的名称，此处可替换为-group，即所属用户组的名称
	find . -perm 755
		-perm 检查具有某权限的文件的文件
	find . -regex ‘.*b.*3’
		regex：表示使用正则表达式进行匹配。请注意，此命令会和“全路径”进行匹配，也就是说前面要加.*，因为输出结果中会有“./”符号
	find . -amin -5
		5分钟访问过的文件
	find . -size +10000000c
		＋表示大于某个数，－表示小于某个数；c表示单位是字节，你可以将c换成k,M,G。
	find . -maxdepth 1 -name “*.c”
tar 解压缩命令
	tar -xzvf abc.tgz
		x选项表示解压缩
		z表示用gzip工具进行解压缩
		v表示在解压缩时显示详细信息
		f表示指定文件（请注意，这个选项一定要放在各个选项的最后哦～～，也就是要和所指定的文件名挨得最近哦）
	tar -czvf dirabc.tar.gz dirabc
		想将一个文件夹dirabc压缩成.tar.gz的压缩文件
	gzip -d xyz.gz
		-d表示解压缩
	gzip -1 abc.tar
		将文件二次压缩
		-1也可以换成–fast；-9表示压缩比高，但速度最慢，-9也可以用–best代替。默认的是-6
	tar -xzvpf abc.tar.gz
		解压abc.tar.gz时我想保留原来被压缩文件的权限（常用于备份）
	zip命令可以用来将文件压缩成为常用的zip格式。
	unzip命令则用来解压缩zip文件
wget
	文件下载
	wget支持HTTP、HTTPS和FTP协议的下载。支持慢网速下载以及断点下载；在下载的时候会查询软连接下载关联的所有的文件
	wegt文档查看说明/etc/wgetrc文件和家目录下的.wgetrc文件；排除文件下载exclude_directories=wukong,bajie
	-X 排除某些文件继续下载
		wget -X wukong , bajie
		wget -r -X ” -X wukong,bajie ftp:  删除某些排除文件继续下载
	-x
		即–force-directories。这个选项和–nd是完全相反的。–no-directories是要求绝 对不能下载和创建任何文件夹，同时所有文件都平铺在当前目录中
	-r
		远程文件遍历下载，但是ftp下载的时候会在本地遍历【生成相同的文件夹目录结构】
		wget -r ftp://my.test.server:/home/wupengchong/img
	-nd
	 	即–no-directories。当我们下载远程的数据时，可以要求wget只下载文件，不下载文件夹，所有下载的文件都平铺在当前目录下；相同的文件将以1，2，3的形式命名
chkconfig
	chkconfig在命令行操作时会经常用到。它可以方便地设置各个系统运行级别启动的服务
	想列出系统所有的服务启动情况：
		# chkconfig –list
	想列出mysqld服务设置情况：
		#chkconfig –list mysqld
	设定mysqld在等级3和5为开机运行服务：
		# chkconfig –level 35 mysqld on
		–level 35表示操作只在等级3和5执行
		on表示启动，off表示关闭
		等级设置
			# chkconfig mysqld on
			“各等级”包括2、3、4、5等级
			等级0表示：表示关机
			等级1表示：单用户模式
			等级2表示：无网络连接的多用户命令行模式
			等级3表示：有网络连接的多用户命令行模式
			等级4表示：不可用
			等级5表示：带图形界面的多用户模式
			等级6表示：重新启动
	如何增加一个服务：
		首先，服务脚本必须存放在/etc/ini.d/目录下；
		其次，需要用chkconfig –add servicename来在chkconfig工具服务列表中增加此服务，此时服务会被在/etc/rc.d/rcN.d中赋予K/S入口了。
		最后，你就可以上面教的方法修改服务的默认启动等级了
	删除一个服务：
		# chkconfig –del servicename
vi编辑器
	替换命令
		:s/abc/xyz/
			将abc替换为xyz；替换当前行，并且只替换一次
		:s/S\./S->/g
			替换当前行所有的
		:1,$s/S\./S->/g
			全文$表示最后一行
		:s#http://roclinux\.cn/index\.php#http://www\.sohu\.com#
			替换命令
who
	who an i
		显示当前"操作用户"的用户名
	who -m
		和who an i一样
	who -Hu
		显示列的信息，并且展示名称描述
	who -q
		显示当前登录的用户个数
sed
	sed是一个很好的文件处理工具，本身是一个管道命令，主要是以行为单位进行处理，可以将数据行进行替换、删除、新增、选取等特定工作
	命令格式
		sed [-nefri] ‘command’ 输入文本
	常用选项：
        -n∶使用安静(silent)模式。在一般 sed 的用法中，所有来自 STDIN的资料一般都会被列出到萤幕上。但如果加上 -n 参数后，则只有经过sed 特殊处理的那一行(或者动作)才会被列出来。
        -e∶直接在指令列模式上进行 sed 的动作编辑；
        -f∶直接将 sed 的动作写在一个档案内， -f filename 则可以执行 filename 内的sed 动作；
        -r∶sed 的动作支援的是延伸型正规表示法的语法。(预设是基础正规表示法语法)
        -i∶直接修改读取的档案内容，而不是由萤幕输出。
	常用命令：
        a   ∶新增， a 的后面可以接字串，而这些字串会在新的一行出现(目前的下一行)～
        c   ∶取代， c 的后面可以接字串，这些字串可以取代 n1,n2 之间的行！
        d   ∶删除，因为是删除啊，所以 d 后面通常不接任何咚咚；
        i   ∶插入， i 的后面可以接字串，而这些字串会在新的一行出现(目前的上一行)；
        p  ∶列印，亦即将某个选择的资料印出。通常 p 会与参数 sed -n 一起运作～
        s  ∶取代，可以直接进行取代的工作哩！通常这个 s 的动作可以搭配正规表示法！例如 1,20s/old/new/g 就是啦！
    删除某行
    	sed -n "2 , $d" ab
    显示某行
    	sed -n "2 , $p" ab
    在第一行后增加多行
    	sed '1a drink tea\nor coffee' ab
    替换多行
    	sed '1,2c Hi' ab
    替换一行中的某部分
    	sed -n '/ruby/p' ab | sed 's/ruby/bird/g'    #替换ruby为bird
    在最后一行添加东西
    	sed -i '$a bye' ab
uname
	uname -a打印所有系统信息
	uname -s打印内核名称
	uname -n 打印网络节点主机名
	uname -r打印内核发信版本号
	uname –help 获得帮助信息
	查看系统版本号的方法还有：
		cat /proc/version
		cat /etc/redhat-release
		cat /etc/issue
tr
	tr指令从标准输入设备读取数据，经过字符串转译后，输出到标准输出设备
	cat filename |tr u n ：用于在屏幕上将filename文件中的u替换为n，而实际文件中未作替换
	cat filename | tr -d abc 在屏幕上将filename内容中的所有出现的a或b或c字符删去，并显示出来
service
	1：此命令位于/sbin目录下，用file命令查看此命令会发现它是一个脚本命令
	2：分析脚本可知此命令的作用是去/etc/init.d目录下寻找相应的服务，进行开启和关闭等操作。
	3：开启httpd服务器：service httpd start
		start可以换成restart表示重新启动，stop表示关闭，reload表示重新载入配置
用户
	创建用户
		adduser www
	向用户创建密码
		passwd www
	创建工作组
		groupadd www
	新建用户同时增加工作组
		useradd -g test phpq
	groups 查看当前登录用户的组内成员
	groups gliethttp 查看gliethttp用户所在的组,以及组内成员
	whoami 查看当前登录用户名
	查看/etc/group
用户相关
	groupadd nginx
    useradd -g nginx -M nginx
	http://blog.csdn.net/beitiandijun/article/details/41678251
	删除
		userdel  删除用户
		只删除用户：
			sudo   userdel   用户名
		2）连同用户主目录一块删除：
			sudo  userdel   -r   用户名
	添加
		adduser： 会自动为创建的用户指定主目录、系统shell版本，会在创建时输入用户密码。
		useradd：需要使用参数选项指定上述基本设置，如果不使用任何参数，则创建的用户无密码、无主目录、没有指定shell版本。
	修改密码
		sudo passwd git

	创建用户
		查看配置 useradd -D
		useradd -u UID -g group 用户名称   指定uid和用户组
		删除用户 userdel -r username  删除相关的主文件
		相关文件
			/etc/passwd  、 shadow 、 group

查看监听的端口
    semanage port -l | grep '^http_port_t'
注册端口
    semanage port -a -t http_port_t -p tcp 8888

批量删除
	ps -aux | grep php | xargs kill -9

显示硬盘、分区、CPU、内存信息
	df -lh                         显示所有硬盘的使用状况
	mount                          显示所有的硬盘分区挂载
	mount partition path           挂在partition到路径path
	umount partition               卸载partition
	sudo fdisk -l                  显示所有的分区
	sudo fdisk device              为device(比如/dev/sdc)创建分区表。 进入后选择n, p, w
	sudo mkfs -t ext3 partition    格式化分区patition(比如/dev/sdc1)
	                               修改 /etc/fstab，以自动挂载分区。增加行：
	                               /dev/sdc1  path(mount point) ext3 defaults 0 0
	arch                           显示架构
	cat /proc/cpuinfo              显示CPU信息
	cat /proc/meminfo              显示内存信息
	free                           显示内存使用状况
文件
	touch filename    如果文件不存在，创建一个空白文件；如果文件存在，更新文件读取和修改时间。
	ls -l path        显示文件和文件相关信息
	mkdir -p path     递归创建路径path上的所有文件夹
	file filename     文件filename的类型描述
	chown username:groupname filename    更改文件的拥有者为owner，拥有组为group
	chmod 755 filename更改文件的权限为755: owner r+w+x, group: r+x, others: r+x
	head -1 filename  显示文件第一行
	tail -5 filename  显示文件倒数第五行
	sort -f filename  排序时，不考虑大小写
	sort -u filename  排序，并去掉重复的行
	uniq filename     显示文件filename中不重复的行 (内容相同，但不相邻的行，不算做重复)
	wc filename       统计文件中的字符、词和行数
	wc -l filename    统计文件中的行数
进程
	top               显示进程信息，并实时更新
	ps                显示当前shell下的进程
	ps -lu username   显示用户username的进程
	ps -ajx           以比较完整的格式显示所有的进程
网络
	ifconfig      显示网络接口以及相应的IP地址。ifconfig可用于设置网络接口
	ifup eth0     运行eth0接口
	ifdown eth0   关闭eth0接口
	iwconfig      显示无线网络接口
	route         显示路由表。route还可以用于修改路由表
	netstat       显示当前的网络连接状态
	traceroute IP 探测前往地址IP的路由路径
	dhclient      向DHCP主机发送DHCP请求，以获得IP地址以及其他设置信息。
	host domain   DNS查询，寻找域名domain对应的IP
	host IP       反向DNS查询
	wget url      使用wget下载url指向的资源
	wget -m url   镜像下载

```

### 目录结构
```
/bin -  二进制文件
	用户二进制文件ls while
/sbin - 二进制文件
	系统二进制文件iptables
/etc   
	包含所有程序所需的配置文件;也包含了用于启动/停止单个程序的启动和关闭shell脚本
/dev   
	设备文件；如usb
/proc  
	进程文件 这是一个虚拟的文件系统，包含有关正在运行的进程的信息。例如：/proc/{pid}目录中包含的与特定pid相关的信息;
       系统资源以文本信息形式存在：如meminfo内存文件
/var - 变量文件
	var代表变量文件。
	这个目录下可以找到内容可能增长的文件。
	这包括 - 系统日志文件（/var/log）;包和数据库文件（/var/lib）;电子邮件（/var/mail）;打印队列（/var/spool）;锁文件（/var/lock）;多次重新启动需要的临时文件（/var/tmp）;

/tmp - 临时文件
	包含系统和用户创建的临时文件。
	当系统重新启动时，这个目录下的文件都将被删除。

/usr - 用户程序
	包含二进制文件、库文件、文档和二级程序的源代码。
	/usr/bin中包含用户程序的二进制文件。如果你在/bin中找不到用户二进制文件，到/usr/bin目录看看。例如：at、awk、cc、less、scp。
	/usr/sbin中包含系统管理员的二进制文件。如果你在/sbin中找不到系统二进制文件，到/usr/sbin目录看看。例如：atd、cron、sshd、
	useradd、userdel。
	/usr/lib中包含了/usr/bin和/usr/sbin用到的库。
	/usr/local中包含了从源安装的用户程序。例如，当你从源安装Apache，它会在/usr/local/apache2中。

/home - HOME目录
	所有用户用home目录来存储他们的个人档案。
	例如：/home/john、/home/nikita

/boot - 引导加载程序文件
	包含引导加载程序相关的文件。
	内核的initrd、vmlinux、grub文件位于/boot下。
	例如：initrd.img-2.6.32-24-generic、vmlinuz-2.6.32-24-generic

/lib - 系统库
	包含支持位于/bin和/sbin下的二进制文件的库文件.
	库文件名为 ld*或lib*.so.*
	例如：ld-2.11.1.so，libncurses.so.5.7

/opt - 可选的附加应用程序
	opt代表可选的。
	包含从个别厂商的附加应用程序。
	附加应用程序应该安装在/opt/或者/opt/的子目录下。

/mnt - 挂载目录
	临时安装目录，系统管理员可以挂载文件系统。

/media - 可移动媒体设备
	用于挂载可移动设备的临时目录。
	举例来说，挂载CD-ROM的/media/cdrom，挂载软盘驱动器的/media/floppy;

/srv - 服务数据
	srv代表服务。
	包含服务器特定服务相关的数据。
	例如，/srv/cvs包含cvs相关的数据。

```
