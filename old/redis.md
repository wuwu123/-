### 常见的数据类型
字符串
```
二进制安全，可以存放任何数据
一个键最大存放512M
```
###### 命令
```
exists mykey 键值是否存在
SET
GET
APPEND 向键后面追加
STRLEN 字符串长度


INCR/DECR/INCRBY/DECRBY

在获取计数器原有值的同时，并将其设置为新值，这两个操作原子性的同时完成
GETSET
    getset mycounter 0

SETEX
    setex mykey 10 "hello"   #设置指定Key的过期时间为10秒。
SETNX
    setnx mykey "hello"        #该键并不存在，因此该命令执行成功
MSET/MGET/MSETNX
    批量操作
    MSETNX 若是key已存在保存失败
    msetnx key3 "stephen" key4 "liu"
```



#### 哈希 Hashes
```
是一个键值对集合
是一个string类型的field和value的映射表，hash特别适合用于存储对象
```
###### 命令
```
HSET/HGET/HDEL/HEXISTS/HLEN/HSETNX
    hset myhash field1 "stephen"
    hsetnx myhash field1 stephen 若是不存在则赋值成功

HINCRBY


HGETALL/HKEYS/HVALS/HMGET/HMSET
    HVALS 获取所有的值
    hmget myhash field1 field2 field3 批量读取
```

#### 列表 list
```
List类型是按照插入顺序排序的字符串链表
如果链表中所有的元素均被移除，那么该键也将会被从数据库中删除。
List中可以包含的最大元素数量是4294967295
```
###### 命令
```
LPUSH/LPUSHX/LRANGE
     lrange mykey 0 -1
     #mykey2键此时并不存在，因此该命令将不会进行任何操作，其返回值为0。
     lpushx mykey2 e

 LPOP/LLEN
     从头部(left)向尾部(right)变量链表，删除2个值等于a的元素，返回值为实际删除的数量。
     lrem mykey 2 a

 LREM/LSET/LINDEX/LTRIM
     将索引值为1(头部的第二个元素)的元素值设置为新值e。
     lset mykey 1 e
```

### 集合Set
```
Set集合中不允许出现重复的元素
```
###### 命令
```
SADD/SMEMBERS/SCARD/SISMEMBER
    SCARD     获取Set集合中元素的数量
    SISMEMBER 判断f是否已经存在，返回值为0表示不存在
    SMEMBERS  过smembers命令查看插入的结果，从结果可以，输出的顺序和插入顺序无关
```
### 有序集合  Sorted-Sets

### 学习地址
[学习地址](http://www.cnblogs.com/stephen-liu74/archive/2012/04/16/2370212.html)
