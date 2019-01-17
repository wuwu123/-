## 概念
[鸟哥学习地址](http://www.laruence.com/2015/05/28/3038.html)
```
协程可以理解为纯用户态的线程，其通过 [协作] 而不是抢占来进行切换。相对于进程或者线程，协程所有的操作都可以在用户态完成，创建和切换的消耗更低。<br>
总的来说，协程为协同任务提供了一种运行时抽象，这种抽象非常适合于协同多任务调度和数据流处理。在现代操作系统和编程语言中，因为用户态线程切换代价比内核态线程小，协程成为了一种轻量级的多任务模型<br>
tip
PHP从5.5引入了yield关键字，增加了迭代生成器和协程的支持，但并未在语言本身级别实现一个完善的协程解决方案。
PHP协程也是基于Generator，Generator可以视为一种“可中断”的函数，而 yield 构成了一系列的“中断点”
```
### 迭代和迭代器
```
在了解生成器之前我们先来看一下迭代器和迭代。迭代是指反复执行一个过程，每执行一次叫做迭代一次。比如普通的遍历便是迭代 foreach
在需要的时候我们可以去重写迭代器的接口
```

### PHP提供了统一的迭代器接口
```php
Iterator extends Traversable {

    // 返回当前的元素
    abstract public mixed current(void)
    // 返回当前元素的键
    abstract public scalar key(void)
    // 向下移动到下一个元素
    abstract public void next(void)
    // 返回到迭代器的第一个元素
    abstract public void rewind(void)
    // 检查当前位置是否有效
    abstract public boolean valid(void)
}
```
### 生成器的概念
```
生成器提供了一种更容易的方法来实现简单的对象迭代;性能开销和复杂性大大降低。
生成器允许你在 foreach 代码块中写代码来 [迭代一组数据] 而不需要在内存中创建一个数组, 那会使你的内存达到上限，或者会占据可观的处理时间。
相反，你可以写一个生成器函数，就像一个普通的自定义函数一样, 不同的是普通函数返回一个值，而一个生成可以yield生成许多它所需要的值，并且每一次的生成返回值只是暂停当前的执行状态，当下次调用生成器函数时，PHP会从上次暂停的状态继续执行下去
```

### 生成器函数返回的是一个Generator对象
```
Generator implements Iterator {
    public mixed current(void)
    public mixed key(void)
    public void next(void)
    public void rewind(void)
    // 向生成器传入一个值;并且当做yield表达式的结果
    public mixed send(mixed $value)
    public void throw(Exception $exception)
    public bool valid(void)
    // 序列化回调
    public void __wakeup(void)
}
```
可以看到出了实现`Iterator`的接口之外`Generator`还添加了`send`方法，用来向生成器传入一个值，并且当做yield表达式的结果，然后继续执行生成器，直到遇到下一个yield后会再次停住。
### Example
```php
function printer() {
    $i = 1;
    while(true) {
        echo 'this is the yield ' . $i . "\n";
        echo 'receive: ' . yield . "\n";   //yield 代替传入的值（接受者）
        $i++;
    }
}

$printer = printer();
$printer->send('Hello');
$printer->send('world');
```
输出
```
this is the yield 1
receive: hello
this is the yield 2
receive: world
this is the yield 3
```
tip
```

1:在上面的例子中，经过第一个send()方法，yield表达式的值变为Hello，之后执行echo语句，输出第一条结果receive: Hello，输出完毕后继续执行到第二个yield处，
2:只不过当前的语句没有执行到底，不会执行输出。

```


### 
### Example2
```php
function printer() {
    $i = 1;
    while(true) {
        echo 'this is the yield ' . (yield $i) . "\n";//同时进行接收和发送
        $i++;
    }
}

$printer = printer();
var_dump($printer->current());
var_dump($printer->send('first'));
var_dump($printer->send('second'));
```
输出
```
int(1)
this is the yield first
int(2)
this is the yield second
int(3)
```
tip
```
    可以看到在第一次调用生成器函数的时候，生成器已经执行到了第一个yield表达式处，
所以在$printer->send('first')之前，生成器便已经yield 1出来了，只是没有对这个生成的值进行接收处理，在send()了之后，echo语句便会紧接着完整的执行，执行完毕继续执行$i++，下次循环便是var_dump(2)。
    至此，我们看到了yield不仅能够返回数据而且还可以接收数据，而且两者可以同时进行，
此时yield便成了数据双向传输的工具，这就为了实现协程提供了可能性。
```


#### Example
更多参考 [生成器语法](http://php.net/manual/zh/language.generators.syntax.php)
```PHP
<?php
function xrange($start, $limit, $step = 1) {
    if ($start < $limit) {
        if ($step <= 0) {
            throw new LogicException('Step must be +ve');
        }

        for ($i = $start; $i <= $limit; $i += $step) {
            yield $i;
        }
    } else {
        if ($step >= 0) {
            throw new LogicException('Step must be -ve');
        }

        for ($i = $start; $i >= $limit; $i += $step) {
            yield $i;
        }
    }
}

/*
 * 注意下面range()和xrange()输出的结果是一样的。
 */

echo 'Single digit odd numbers from range():  ';
foreach (range(1, 9, 2) as $number) {
    echo "$number ";
}
echo "\n";

echo 'Single digit odd numbers from xrange(): ';
foreach (xrange(1, 9, 2) as $number) {
    echo "$number ";
}
?>
```
