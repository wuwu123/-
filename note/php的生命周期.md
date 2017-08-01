### 我们平时接触的最多的是web模式下的php，当然你也肯定知道php还有个CLI模式
SAPI就是PHP和外部环境的代理器。它把外部环境抽象后, 为内部的PHP提供一套固定的, 统一的接口,
使得PHP自身实现能够不受错综复杂的外部环境影响，保持一定的独立性

### PHP程序的启动 和 关闭
* 一个是PHP作为Apache(拿它举例，板砖勿扔)的一个模块的启动与终止， 这次启动php会初始化一些
必要数据，比如与宿主Apache有关的，并且这些数据是常驻内存的！ 终止与之相对

* 还有一个概念上的启动就是当Apache分配一个页面请求过来的时候，PHP会有一次启动与终止，这也是我们最常讨论的一种

### 最初的初始化时候，PHP_MINIT_FUNCTION
就是PHP随着Apache的启动而诞生在内存里的时候， 它会把自己所有已加载扩展的MINIT方法都执行一
遍；扩展可以定义一些自己的常量、类、资源等所有会被用户端的PHP脚本用到的东西。

### 当一个页面请求到来时候，PHP_RINIT_FUNCTION
PHP会迅速的开辟一个新的环境，并重新扫描自己的各个扩展， 遍历执行它们各自的RINIT方法

### 页面请求执行 结束 RSHUTDOWN
这时候扩展可以抓紧利用内核中的变量表之类的做一些事情， 因为一旦PHP把所有扩展的RSHUTDOWN方
法执行完， 便会释放掉这次请求使用过的所有东西， 包括变量表的所有变量、所有在这次请求中申请的
内存等等。

 ### Apache通知PHP自己要Stop PHP_MSHUTDOWN_FUNCTION
 PHP便进入MSHUTDOWN（俗称Module Shutdown）阶段。这时候PHP便会给所有扩展下最后通牒，如果
 哪个扩展还有未了的心愿，就放在自己MSHUTDOWN方法里，这可是最后的机会了，一旦PHP把扩展
 的MSHUTDOWN执行完，便会进入自毁程序，这里一定要把自己擅自申请的内存给释放掉，否则就杯具了

 这四个宏都是在walu.c里完成最终实现的，而他们的则是在/main/php.h里被定义的
 ```c
 int time_of_minit;//在MINIT中初始化，在每次页面请求中输出，看看是否变化
 PHP_MINIT_FUNCTION(walu)
 {
     time_of_minit=time(NULL);//我们在MINIT启动中对他初始化
     return SUCCESS;
 }

 int time_of_rinit;//在RINIT里初始化，看看每次页面请求的时候变不。
 PHP_RINIT_FUNCTION(walu)
 {
     time_of_rinit=time(NULL);
     return SUCCESS;
 }

 PHP_RSHUTDOWN_FUNCTION(walu)
 {
     FILE *fp=fopen("/cnan/www/erzha/time_rshutdown.txt","a+");//请确保文件可写，否则apache会莫名崩溃
     fprintf(fp,"%d\n",time(NULL));//让我们看看是不是每次请求结束都会在这个文件里追加数据
     fclose(fp);
     return SUCCESS;
 }

 PHP_MSHUTDOWN_FUNCTION(walu)
 {
     FILE *fp=fopen("/cnan/www/erzha/time_mshutdown.txt","a+");//请确保文件可写，否则apache会莫名崩溃
     fprintf(fp,"%d\n",time(NULL));
     return SUCCESS;
 }

 //我们在页面里输出time_of_minit和time_of_rinit的值
 PHP_FUNCTION(walu_test)
 {
     php_printf("%d&lt;br /&gt;",time_of_minit);
     php_printf("%d&lt;br /&gt;",time_of_rinit);
     return;
 }
 ```

 ### PHP是用什么sapi与宿主通信的。最常见的四种方式如下所列
* 直接以CLI/CGI模式调用
* 多进程模块
* 多线程模
* Embedded(嵌入式，在自己的C程序中调用Zend Engine)

##### CLI/CGI
CLI和CGI的SAPI是相当特殊的，因为这时PHP的生命周期完全在一个单独的请求中完成

##### 多进程模式
当Apache启动的时候，会立即把自己fork出好几个子进程，每一个进程都有自己独立的内存空间， 也就代表了有自己独立的变量、函数等。在每个进程里的PHP的工作方式如下图所示：

因为是fork出来的，所以各个进程间的数据是彼此独立，不会受到外界的干扰

##### 多线程模式
只有一个服务器进程在运行着，但会同时运行很多线程，这样可以减少一些资源开销， 像Module init和Module shutdown就只需要运行一次就行了，一些全局变量也只需要初始化一次， 因为线程独具的特质，使得各个请求之间方便的共享一些数据成为可能。

##### Embed
