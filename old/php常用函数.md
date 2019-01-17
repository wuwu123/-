### 抽象类和接口相同点
1)	都是上层的抽象层。

2)	都不能被实例化

3)	都能包含抽象的方法，这些抽象的方法用于描述类具备的功能，但是不比提供具体的实现。

### 区别：
1)	接口里面只能对方法进行声明，抽象类既可以对方法进行声明也可以对方法进行实现；
在抽象类中可以写非抽象的方法，从而避免在子类中重复书写他们，这样可以提高代码的复用性，这是抽象类的优势；接口中只能有抽象的方法。
2)	一个类只能继承一个直接父类，这个父类可以是具体的类也可是抽象类；但是一个类可以实现多个接口。
Java语言中类的继承是单继承原因是：
当子类重写父类方法的时候，或者隐藏父类的成员变量以及静态方法的时候，JVM使用不同的绑定规则。
如果一个类有多个直接的父类，那么会使绑定规则变得更复杂。为了简化软件的体系结构和绑定机制，java语言禁止多继承。

接口可以多继承，是因为接口中只有抽象方法，没有静态方法和非常量的属性，只有接口的实现类才会重写接口
中方法。

因此一个类有多个接口也不会增加JVM的绑定机制和复杂度。

对于已经存在的继承树，可以方便的从类中抽象出新的接口，但是从类中抽象出新的抽象类就不那么容易了，因
此接口更有利于软件系统的维护和重构。

```
抽象级别（从高到低）：接口>抽象类>实现类。
```

### php文件函数
```
fopen() 的第一个参数包含被打开的文件名，第二个参数规定打开文件的模式。
fclose() 函数用于关闭打开的文件
fread() 的第一个参数包含待读取文件的文件名，第二个参数规定待读取的最大字节数。
fgets() 函数用于从文件读取单行。
feof() 函数检查是否已到达 "end-of-file" (EOF)
fgetc() 函数用于从文件中读取单个字符。

//显示带有文件扩展名的文件名
echo basename($path);
//显示不带有文件扩展名的文件名
echo basename($path,".php");

pathinfo("/testweb/test.txt");
[
    [dirname] => /testweb
    [basename] => test.txt
    [extension] => txt
]
```
```php
<?php

function my_scandir($dir)    
{    
     $files = array();    
     if ( $handle = opendir($dir) ) {    
         while ( ($file = readdir($handle)) !== false ) {    
             if ( $file != ".." && $file != "." ) {    
                 if ( is_dir($dir . "/" . $file) ) {    
                     $files[$file] = my_scandir($dir . "/" . $file);     
                 }else {    
                     $files[] = $file;    
                 }    
             }    
         }    
         closedir($handle);    
         return $files;    
     }    
}  

?>

```

### php数组
```
array()	创建数组。
array_change_key_case()	把数组中所有键更改为小写或大写。CASE_LOWER  | CASE_UPPER
array_chunk()	把一个数组分割为新的数组块。
    $age=array("Bill"=>"60","Steve"=>"56","Mark"=>"31","David"=>"35");
    print_r(array_chunk($age,2,true));
array_column()	返回输入数组中某个单一列的值。
    $a = array(
      array(
        'id' => 5698,
        'first_name' => 'Bill',
        'last_name' => 'Gates',
      ),
      array(
        'id' => 4767,
        'first_name' => 'Steve',
        'last_name' => 'Jobs',
      ),
      array(
        'id' => 3809,
        'first_name' => 'Mark',
        'last_name' => 'Zuckerberg',
      )
    );

    $last_names = array_column($a, 'last_name');
    print_r($last_names);
    Array
    (
      [0] => Gates
      [1] => Jobs
      [2] => Zuckerberg
    )
array_combine()	通过合并两个数组来创建一个新数组。
array_count_values()	用于统计数组中所有值出现的次数。
array_diff()	比较数组，返回差集（只比较键值）。
array_diff_assoc()	比较数组，返回差集（比较键名和键值）。
array_diff_key()	比较数组，返回差集（只比较键名）。
array_diff_uassoc()	比较数组，返回差集（比较键名和键值，使用用户自定义的键名比较函数）。
array_diff_ukey()	比较数组，返回差集（只比较键名，使用用户自定义的键名比较函数）。
array_fill()	用给定的键值填充数组。
    array_fill(3,4,"blue");
array_fill_keys()	用指定键名的给定键值填充数组。
array_filter()	用回调函数过滤数组中的元素。
array_flip()	交换数组中的键和值。
array_intersect()	比较数组，返回交集（只比较键值）。
array_intersect_assoc()	比较数组，返回交集（比较键名和键值）。
array_intersect_key()	比较数组，返回交集（只比较键名）。
array_intersect_uassoc()	比较数组，返回交集（比较键名和键值，使用用户自定义的键名比较函数）。
array_intersect_ukey()	比较数组，返回交集（只比较键名，使用用户自定义的键名比较函数）。
array_key_exists()	检查指定的键名是否存在于数组中。
array_keys()	返回数组中所有的键名。
array_map()	把数组中的每个值发送到用户自定义函数，返回新的值。
array_merge()	把一个或多个数组合并为一个数组。
array_merge_recursive()	递归地合并一个或多个数组。
array_multisort()	对多个数组或多维数组进行排序。
array_pad()	用值将数组填补到指定长度。
array_pop()	删除数组的最后一个元素（出栈）。
array_product()	计算数组中所有值的乘积。
array_push()	将一个或多个元素插入数组的末尾（入栈）。
array_rand()	返回数组中一个或多个随机的键。
array_reduce()	通过使用用户自定义函数，以字符串返回数组。
array_replace()	使用后面数组的值替换第一个数组的值。
array_replace_recursive()	递归地使用后面数组的值替换第一个数组的值。
array_reverse()	以相反的顺序返回数组。
array_search()	搜索数组中给定的值并返回键名。
array_shift()	删除数组中首个元素，并返回被删除元素的值。
array_slice()	返回数组中被选定的部分。
array_splice()	删除并替换数组中指定的元素。
array_sum()	返回数组中值的和。
array_udiff()	比较数组，返回差集（只比较值，使用一个用户自定义的键名比较函数）。
array_udiff_assoc()	比较数组，返回差集（比较键和值，使用内建函数比较键名，使用用户自定义函数比较键值）。
array_udiff_uassoc()	比较数组，返回差集（比较键和值，使用两个用户自定义的键名比较函数）。
array_uintersect()	比较数组，返回交集（只比较值，使用一个用户自定义的键名比较函数）。
array_uintersect_assoc()	比较数组，返回交集（比较键和值，使用内建函数比较键名，使用用户自定义函数比较键值）。
array_uintersect_uassoc()	比较数组，返回交集（比较键和值，使用两个用户自定义的键名比较函数）。
array_unique()	删除数组中的重复值。
array_unshift()	在数组开头插入一个或多个元素。
array_values()	返回数组中所有的值。
array_walk()	对数组中的每个成员应用用户函数。
array_walk_recursive()	对数组中的每个成员递归地应用用户函数。
arsort()	对关联数组按照键值进行降序排序。
asort()	对关联数组按照键值进行升序排序。
compact()	创建包含变量名和它们的值的数组。
count()	返回数组中元素的数目。
current()	返回数组中的当前元素。
each()	返回数组中当前的键／值对。
end()	将数组的内部指针指向最后一个元素。
extract()	从数组中将变量导入到当前的符号表。
in_array()	检查数组中是否存在指定的值。
key()	从关联数组中取得键名。
krsort()	对数组按照键名逆向排序。
ksort()	对数组按照键名排序。
list()	把数组中的值赋给一些变量。
natcasesort()	用“自然排序”算法对数组进行不区分大小写字母的排序。
natsort()	用“自然排序”算法对数组排序。
next()	将数组中的内部指针向前移动一位。
pos()	current() 的别名。
prev()	将数组的内部指针倒回一位。
range()	创建包含指定范围单元的数组。
reset()	将数组的内部指针指向第一个元素。
rsort()	对数组逆向排序。
shuffle()	将数组打乱。
sizeof()	count() 的别名。
sort()	对数组排序。
uasort()	使用用户自定义的比较函数对数组中的键值进行排序。
uksort()	使用用户自定义的比较函数对数组中的键名进行排序。
usort()	使用用户自定义的比较函数对数组进行排序。
```

### linux命令
##### touch
创建文件，修改文件的时间
##### tar 解压缩命令
```
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
```

### 从URL请求到数据返回做了哪些事情
* DNS解析
* TCP连接
* 发送HTTP请求
* 服务器处理请求并返回HTTP报文
* 浏览器解析渲染页面
* 连接结束

### set_time_limit
当此函数被调用时，set_time_limit()会从零开始重新启动超时计数器。
换句话说，如果超时默认是30秒，在脚本运行了了25秒时调用 set_time_limit(20)，那么，脚本
在超时之前可运行总时间为45秒。

### HTTP 状态消息
##### 1xx: 信息
```
100 Continue	服务器仅接收到部分请求，但是一旦服务器并没有拒绝该请求，客户端应该继续发送其余的请求。
101 Switching Protocols	服务器转换协议：服务器将遵从客户的请求转换到另外一种协议。
```

##### 2xx: 成功
```
200 OK	请求成功（其后是对GET和POST请求的应答文档。）
201 Created	请求被创建完成，同时新的资源被创建。
202 Accepted	供处理的请求已被接受，但是处理未完成。
203 Non-authoritative Information	文档已经正常地返回，但一些应答头可能不正确，因为使用的是文档的拷贝。
204 No Content	没有新文档。浏览器应该继续显示原来的文档。如果用户定期地刷新页面，而Servlet可以确定用户文档足够新，这个状态代码是很有用的。
205 Reset Content	没有新文档。但浏览器应该重置它所显示的内容。用来强制浏览器清除表单输入内容。
206 Partial Content	客户发送了一个带有Range头的GET请求，服务器完成了它。

```

##### 3xx: 重定向
```
300 Multiple Choices	多重选择。链接列表。用户可以选择某链接到达目的地。最多允许五个地址。
301 Moved Permanently	所请求的页面已经转移至新的url。
302 Found	所请求的页面已经临时转移至新的url。
303 See Other	所请求的页面可在别的url下被找到。
304 Not Modified	未按预期修改文档。客户端有缓冲的文档并发出了一个条件性的请求（一般是提供If-Modified-Since头表示客户只想比指定日期更新的文档）。服务器告诉客户，原来缓冲的文档还可以继续使用。
305 Use Proxy	客户请求的文档应该通过Location头所指明的代理服务器提取。
306 Unused	此代码被用于前一版本。目前已不再使用，但是代码依然被保留。
307 Temporary Redirect	被请求的页面已经临时移至新的url。
```


##### 4xx: 客户端错误
```
400 Bad Request	服务器未能理解请求。
401 Unauthorized	被请求的页面需要用户名和密码。
402 Payment Required	此代码尚无法使用。
403 Forbidden	对被请求页面的访问被禁止。
404 Not Found	服务器无法找到被请求的页面。
405 Method Not Allowed	请求中指定的方法不被允许。
406 Not Acceptable	服务器生成的响应无法被客户端所接受。
407 Proxy Authentication Required	用户必须首先使用代理服务器进行验证，这样请求才会被处理。
408 Request Timeout	请求超出了服务器的等待时间。
409 Conflict	由于冲突，请求无法被完成。
410 Gone	被请求的页面不可用。
411 Length Required	"Content-Length" 未被定义。如果无此内容，服务器不会接受请求。
412 Precondition Failed	请求中的前提条件被服务器评估为失败。
413 Request Entity Too Large	由于所请求的实体的太大，服务器不会接受请求。
414 Request-url Too Long	由于url太长，服务器不会接受请求。当post请求被转换为带有很长的查询信息的get请求时，就会发生这种情况。
415 Unsupported Media Type	由于媒介类型不被支持，服务器不会接受请求。
416 	服务器不能满足客户在请求中指定的Range头。
417 Expectation Failed	 
```
##### 5xx: 服务器错误
```
500 Internal Server Error	请求未完成。服务器遇到不可预知的情况。
501 Not Implemented	请求未完成。服务器不支持所请求的功能。
502 Bad Gateway	请求未完成。服务器从上游服务器收到一个无效的响应。
503 Service Unavailable	请求未完成。服务器临时过载或当机。
504 Gateway Timeout	网关超时。
505 HTTP Version Not Supported	服务器不支持请求中指明的HTTP协议版本。
```
