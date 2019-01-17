### 什么是php的路由机制
1、路由机制就是把某一个特定形式的URL结构中提炼出来系统对应的参数。举个例子,如
：http://main.wopop.com/article/1  其中：/article/1  -> ?_m=article&id=1。

2、然后将拥有对应参数的URL转换成特定形式的URL结构，是上面的过程的逆向过程。

### PHP的URL路由方式
1：第一种是通过url参数进行映射的方式，一般是两个参数，分别代表控制器类和方法比
如index.php?c=index&m=index映射到的是index控制器的index方法。

2：是通过url-rewrite的方式，这样的好处是可以实现对非php结尾的其他后缀进行映射，当然通过rewrite也
可以实现第一种方式，不过纯使用rewrite的也比较常见，一般需要配置apache或者nginx的

3：就是通过pathinfo的方式，所谓的pathinfo，就是形如这样的ur。xxx.com/index.php/c/index/aa/cc
，apache在处理这个url的时候会把index.php后面的部分输入到环境变量$_SERVER['PATH_INFO']，它等
于/c/index/aa/cc。然后我们的路由器再通过解析这个串进行分析就可以了，

```
<Directory />  
Options FollowSymLinks  
AllowOverride All  
</Directory> 
```
