## include 和 require
include 和 require 是PHP中引入文件的两个基本方法。
在小规模开发中直接使用 include 和 require 没哟什么不妥，但在大型项目中会造成大量的
include 和 require 堆积。这样的代码既不优雅，执行效率也很低，而且维护起来也相当困难。

#### 区别
* include 和 require 功能是一样的，它们的不同在于 include 出错时只会产生警告，而 require 会抛出错误终止脚本。
* include_once 和 include 唯一的区别在于 include_once 会检查文件是否已经引入，如果是则不会重复引入。

## PHP5 之后

### __autoload
当需要使用的类没有被引入时，这个函数会在PHP报错前被触发，未定义的类名会被当作参数传入。至于函
数具体的逻辑，这需要用户自己去实现。
```php
<?php
// 类未定义时，系统自动调用
function __autoload($class)
{
    /* 具体处理逻辑 */
    echo $class;// 简单的输出未定义的类名
}

new HelloWorld();
/**
 * 输出 HelloWorld 与报错信息
 * Fatal error: Class 'HelloWorld' not found
 */
?>
```

#### 一个自动加载案例
```php
<?php
/* 模拟系统实例化过程 */
function instance($class)
{
    // 如果类存在则返回其实例
    if (class_exists($class, false)) {
        return new $class();
    }
    // 查看 autoload 函数是否被用户定义
    if (function_exists('__autoload')) {
        __autoload($class); // 最后一次引入的机会
    }
    // 再次检查类是否存在
    if (class_exists($class, false)) {
        return new $class();
    } else { // 系统：我实在没辙了
        throw new Exception('Class Not Found');
    }
}
?>
```

### spl_autoload_register
spl_autoload_register 函数的功能就是把传入的函数（参数可以为回调函数或函数名称形式）注册到
SPL __autoload 函数队列中，并`移除`系统默认的 __autoload() 函数
一旦调用 spl_autoload_register() 函数，当调用未定义类时，系统就会按顺序调用注册到
spl_autoload_register() 函数的所有函数，而不是自动调用 __autoload() 函数

#### 案例
```php
<?php
spl_autoload_register(function ($class) { // class = os\Linux
    /* 限定类名路径映射 */
    $class_map = array(
        // 限定类名 => 文件路径
        'os\\Linux' => './Linux.php',
    );
    /* 根据类名确定文件名 */
    $file = $class_map[$class];
    /* 引入相关文件 */
    if (file_exists($file)) {
        include $file;
    }
});

new \os\Linux();
?>
```

### PSR-4规范
文件结构
```
\<顶级命名空间>(\<子命名空间>)*\<类名>
```
PSR-4 规范中必须要有一个顶级命名空间，它的意义在于表示某一个特殊的目录（文件基目录）。子命名空间代
表的是类文件相对于文件基目录的这一段路径（相对路径），类名则与文件名保持一致（注意大小写的区别）。

### 阿里oos的自动加载机制
```php
<?php
function classLoader($class)
{
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR . $path . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}
spl_autoload_register('classLoader');
```

#### 来源地址
http://www.cnblogs.com/woider/p/6443854.html
