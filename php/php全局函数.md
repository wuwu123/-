### global与$GLOBALS的区别和使用
```
global的作用就相当于传递参数，在函数外部声明的变量，
如果在函数内想要使用，就用global来声明该变量，这样就相当于把该变量传递进来了，就可以引用该变量了。

```
```php
<?php

      $name="why";//声明变量$name,并初始化  
      function echoName1()  
      {  
          //在函数echoName1()里使用global来声明$name  
          global  $name;  
          echo "the first name is ".$name."<br>";  
      }  
      function echoName2()  
      {  
          //在函数echoName2()里没有使用global来声明$name  
          echo "the second name is ".$name."<br>";  
      }  
      echoName1();  
      echoName2();

结果为:
the first name is why
the second name is
 ?>

```
###### 注意事项
```
只能够先定义变量，在使用的时候在设置为 global；
只在局部函数内有效
```
