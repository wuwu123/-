### echo(),print(),print_r()的区别？
```
echo 和 print 不是一个函数，是一个语言结构；
print(string $arg) 只有一个参数；
echo arg1,arg2 可以输出多个参数，返回 void ；
echo 和 print 只能打印出string，不能打印出结构；
print_r能打印出结构。比如:
```

### 如何在PHP中定义常量
```
defined('YII_DEBUG') or define('YII_DEBUG',true);
```

### <? echo 'hello tusheng' ; ?> 没有输出结果, 可能是什么原因
```
可能服务器上面没有开启短标签short_open_tag =设置为Off，,php.ini开启短标签控制参数：
short_open_tag = On
```

### include和require的区别是什么?
```
include 产生一个 warning ，而 require 直接产生错误中断；
include 在运行时载入；
require 在运行前载入；
require_once 和 include_once 可以避免重复包含同一文件
```

### 用PHP写出显示客户端IP与服务器IP的代码
```
打印客户端IP:echo $_SERVER[‘REMOTE_ADDR’]; 或者: getenv(‘REMOTE_ADDR’);
打印服务器IP:echo gethostbyname(“www.bolaiwu.com”)
```

### 简述两种屏蔽php程序的notice警告的方法
```
1) 在程序中添加：error_reporting (E_ALL & ~E_NOTICE);
2) 修改php.ini中的：error_reporting = E_ALL 改为：error_reporting = E_ALL &
~E_NOTICE
3)error_reporting(0);或者修改php.inidisplay_errors=Off

```

### 用PHP打印出前一天的时间
```php
<?php
echo date("Y-m-dH:i:s",time()-(3600*24));或echodate("Y-m-d H:i:s",strtotime("-1 day"));
?>
```

### $GLOBALS和global
```
PHP超全局变量有很多，如下的都属于超全局变量(Superglobal)：
$GLOBALS，$_SERVER，$_GET，$_POST，$_FILES，$_COOKIE，$_SESSION，$_REQUEST，$_ENV。
```
###### global 和 $GLOBALS的区别
```
$GLOBALS — 引用全局作用域中可用的全部变量。
一个包含了全部变量的全局组合数组。变量的名字就是数组的键。所以将$GLOBALS['var1'] 删除后，该变量
已不存在
$GLOBALS[]确确实实调用是外部的变量，函数内外会始终保持一致。
```
```
global在函数产生一个指向函数外部变量的别名变量，而不是真正的函数外部变
即使在函数内将别名变量删除也不会影响外面的变量，但是可以修改函数外部变量的值
```
```php
<?php
function t1(){
    global $var1;
    $var1=2;
    unset($var1);
}
function t2(){
    $GLOBALS['var1']=3;
    unset($GLOBALS['var1']);
}
$var1=1;
t1();
print $var1."\n";
t2();
print $var1."\n";
//输出
2
?>

```

### 遍历一个文件夹下的所有文件和子文件夹
```php
<?php
function listDir($dir = '.'){
	if ($handle = opendir($dir)) {
		while (false !== ($file = readdir($handle))) {
			if($file == '.' || $file == '..'){
				continue;
			}
			if(is_dir($sub_dir = realpath($dir.'/'.$file))){
				echo 'FILE in PATH:'.$dir.':'.$file.'<br>';
				listDir($sub_dir);
			}else{
				echo 'FILE:'.$file.'<br>';
			}
		}
		closedir($handle);
	}
}
listDir('e:\www\abc');

## realpath — 返回规范化的绝对路径名
chdir('/var/www/');
echo realpath('./../../etc/passwd');
/etc/passwd

## Windows
echo realpath('/windows/system32');
C:\WINDOWS\System32
?>

```

### 算出两个文件的相对路径
```php
<?php
/** by www.manongjc.com */
    $a = '/a/b/c/d/e.php';
    $b = '/a/b/13/34/c.php';
    echo getRelativePath($a, $b); //"../../12/34/"
    function getRelativePath($a,$b){
        $a2array = explode('/', $a);
        $b2array = explode('/', $b);
        $relativePath   = '';
        for( $i = 1; $i <= count($b2array)-2; $i++ ) {
            $relativePath .= $a2array[$i] == $b2array[$i] ? '../' : $b2array[$i].'/';
        }
        return $relativePath;
    }
?>
```

### PHP中的错误类型有哪些？
```
PHP中遇到的错误类型大致有3类。
    提示：这都是一些非常正常的信息，而非重大的错误，有些甚至不会展示给用户。比如访问不存在的变量。
    警告：这是有点严重的错误，将会把警告信息展示给用户，但不会影响代码的输出，比如包含一些不存在的
文件。
    错误：这是真正的严重错误，比如访问不存在的PHP类。

```

### HTTP协议中几个状态码的含义
```
200 : 请求成功，请求的数据随之返回。
301 : 永久性重定向。
302 : 暂时行重定向。
401 : 当前请求需要用户验证。
403 : 服务器拒绝执行请求，即没有权限。
404 : 请求失败，请求的数据在服务器上未发现。
500 : 服务器错误。一般服务器端程序执行错误。
503 : 服务器临时维护或过载。这个状态时临时性的。
```

### MySQL存储引擎 MyISAM 和 InnoDB 的区别
```
a. MyISAM类型不支持事务处理等高级处理，而InnoDB类型支持.
b. MyISAM类型的表强调的是性能，其执行速度比InnoDB类型更快.
c. InnoDB不支持NULL , TEXT类型的索引.
d. InnoDB中不保存表的具体行数，也就是说，执行select count(*) from table时，InnoDB要扫描一遍
整个表来计算有多少行，但是MyISAM只要简单的读出保存好的行数即可.
e. 对于AUTO_INCREMENT类型的字段，InnoDB中必须包含只有该字段的索引，但是在MyISAM表中，可以和其他
字段一起建立联合索引。
f. DELETE FROM table时，InnoDB不会重新建立表，而是一行一行的删除。
g. LOAD TABLE FROM MASTER操作对InnoDB是不起作用的，解决方法是首先把InnoDB表改成MyISAM表，导
入数据后再改成InnoDB表，但是对于使用的额外的InnoDB特性(例如外键)的表不适用.
h. MyISAM支持表锁，InnoDB支持行锁。
```

### php抽象与接口的区别
[来源](http://blog.csdn.net/sunlylorn/article/details/6124319)
###### 抽象类：【abstract】 【实现的标准】
1) 是基于类来说，其本身就是类，只是一种特殊的类，不能直接实例，可以在类里定义方法，属
性。类似于模版，规范后让子类实现详细功能。
2) 如果子类需要实例化，前提是它实现了抽象类中的所有抽象方法;如果子类没有全部实现抽象类中的所有抽象
方法，那么该【子类也是一个抽象类】，必须在 class 前面加上 abstract 关键字，并且不能被实例化
3) 抽象方法的实现，范围只能 **更广泛** ；如父类为protected 子类实现 为 protected 和 public
抽象方法的定义不能为**private**
```php
<?php
abstract class A
{
    /** 抽象类中可以定义变量 */
    protected $value1 = 0;
    private   $value2 = 1;
    public    $value3 = 2;

    /** 也可以定义非抽象方法 */
    public function my_print()
    {
        echo "hello,world/n";
    }

    /**
     * 大多数情况下，抽象类至少含有一个抽象方法。抽象方法用abstract关键字声明，其中不能有具体内容。
     * 可以像声明普通类方法那样声明抽象方法，但是要以分号而不是方法体结束。也就是说抽象方法在抽象类中不能被实现，也就是没有函数体“{some codes}”。
     */
    abstract protected function abstract_func1();

    abstract protected function abstract_func2();
}

abstract class B extends A
{
    public function abstract_func1()
    {
        echo "implement the abstract_func1 in class A/n";
    }
    /** 这么写在zend studio 8中会报错*/
    //abstract protected function abstract_func2();  
}
?>
```


######接口：【interface】【纯粹的模版】
1）主要基于方法的规范，有点像抽象类里的抽象方法，只是其相对于抽象方法来说，更加独立。可让某个类通过组合多个方法来形成新的类。
2） 是完全抽象的，只能声明方法，而且 **只能** 声明 public 的方法，不能声明 private 及 protected 的方法，**不能定义方法体**
3） interface 却可以声明常量变量；但将常量变量放在 interface 中违背了其作为接口的作用而存在的宗旨
```php
<?php
interface iA  
{  
    const AVAR=3;  
    public function iAfunc1();  
    public function iAfunc2();  
}

class E implements iA  
{  
    public function iAfunc1(){echo "in iAfunc1";}  
    public function iAfunc2(){echo "in iAfunc2";}  
}
 ?>
```

###### 抽象类与接口的相同点：
```
1、都是用于声明某一种事物，规范名称、参数，形成模块，未有详细的实现细节。
2、都是通过类来实现相关的细节工作
3、语法上，抽象类的抽象方法与接口一样，不能有方法体，即｛｝符号
4、都可以用继承，接口可以继承接口形成新的接口，抽象类可以继承抽象类从而形成新的抽象类
```


###### 抽象类与接口的不同点：
```
1、抽象类可以有属性、普通方法、抽象方法，但接口不能有属性、普通方法、可以有常量
2、抽象类内未必有抽象方法，但接口内一定会有“抽象”方法
3、interface 需要实现，要用 implements ，而 abstract class 需要继承，要用 extends
4、一个类可以实现多个 interface ，但一个类只能继承一个 abstract class
5、interface 强调特定功能的实现，而 abstract class 强调所属关系
3、语法上有不同
    1、抽象类用abstract关键字在类前声明，且有class声明为类，接口是用interface来声明，但不能
    用class来声明，因为接口不是类。
    2、抽象类的抽象方法一定要用abstract来声明，而接口则不需要
    3、抽象类是用extends关键字让子类继承父类后，在子类实现详细的抽象方法。而接口则是
    用implements让普通类在类里实现接口的详细方法，且接口可以一次性实现多个方法，用逗号分开各个接
    口就可
```

###### 各自的特点：
```
1)抽象类内未必有抽象方法，但有抽象方法的类，则必是抽象类
2)抽象类内，即便全是具体方法，也不能够实例化，只要新建类来继承后，实例继承类才可以

3)接口可以让一个类一次性实现多个不同的方法
4)接口本身就是抽象的，但注意不是抽象类，因为接口不是类，只是其方法是抽象的。所以，其也是抽象的
```
###### 应用与结合：
```php
<?php

//接口
interface base{
    public function say();
}

//抽象
abstract class a implements work{
    public function showlove(){
        echo 'love you<br />';
    }
}
class b extends a{
    public function say(){
        echo 'hello, i m in b';
    }
}
$k=new b();
$k->say();
/*
以上程序能正常执行
普通类implements接口后，就变成了抽象类了，这就好像是直接给抽象类增加了一个抽象方法。
*/
```

### 来源
[网址](http://www.manongjc.com/article/1524.html)
