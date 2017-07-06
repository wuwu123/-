## 概念
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
相反，你可以写一个生成器函数，就像一个普通的自定义函数一样, 和普通函数只返回一次不同的是, 生成器可以根据需要 yield 多次，以便生成需要迭代的值
```
#### Example
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
