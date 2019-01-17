### 查看数据表结构
```
1：显示表结构
describe table_name;
desc table_name;

2: 显示结构信息
show create table table_name;

3:显示详细信息
select * from information_schema.columns
where table_schema = 'db'  #表所在数据库
and table_name = 'tablename' ; #你要查的表
```

### 权限
```
查看当前用户的权限
show grants;

查看用户权限
show grants for 'cactiuser'@'%';  
```

### mysql8.0 创建用户 并 赋值权限

```
先查看端口是否开放


#先创建一个用户
create user 'wujie'@'%' identified by 'wujie123';
#再进行授权
grant all privileges on *.* to 'wujie'@'%' with grant option;

flush privileges;

修改密码
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '你的密码';  
```



##### 创建用户并赋予权限
```
grant all privileges on *.* to wujie@'%' identified by "wujie123" with grant option;

GRANT命令说明：
1: ALL PRIVILEGES 是表示所有权限，你也可以使用select、update等权限。
2: ON 用来指定权限针对哪些库和表。
3: *.* 中前面的*号用来指定数据库名，后面的*号用来指定表名。
4: TO 表示将权限赋予某个用户。
5: jack@'localhost' 表示jack用户，@后面接限制的主机，可以是IP、IP段、域名以及%，%表示任何地方。注意：这里%有的版本不包括本地，以前碰到过给某个用户设置了%允许任何地方登录，但是在本地登录不了，这个和版本有关系，遇到这个问题再加一个localhost的用户就可以了。
6: IDENTIFIED BY 指定用户的登录密码。
7: WITH GRANT OPTION 这个选项表示该用户可以将自己拥有的权限授权给别人。注意：经常有人在创建操作用户的时候不指定WITH GRANT OPTION选项导致后来该用户不能使用GRANT命令创建用户或者给其它用户授权。

备注：可以使用GRANT重复给用户添加权限，权限叠加，比如你先给用户添加一个select权限，然后又给用户添加一个insert权限，那么该用户就同时拥有了select和insert权限。
```

```

grant all privileges on eat.* to wujie@'%' identified by "wujie123" with grant option;

grant all privileges on eat.* to wujie@'%' with grant option;

ALTER USER 'wujie'@'%' IDENTIFIED WITH mysql_native_password BY 'wujie123';
```

##### 刷新权限

```
flush privileges;
```


firewall-cmd --reload
firewall-cmd --permanent --add-port=3306/tcp
firewall-cmd --query-port=3306/tcp

##### 收回权限

```
revoke delete on *.* from 'jack'@'localhost';
```

##### 删除用户

```
drop user 'jack'@'localhost';
```

##### 修改用户密码

```
SET PASSWORD FOR 'root'@'localhost' = PASSWORD('123456');

直接更新权限
update user set PASSWORD = PASSWORD('1234abcd') where user = 'root';

sh
```

### 导出
```
mysqldump -uroot -pmysql databasefoo table1 table2 > foo.sql
```


### sql语句

```sql

数据导出
select t.`thera_id` ,t.`name`,t.`mobile` ,t.`email`  ,o.total,o.total_fee
from (SELECT `thera_id` ,COUNT(*) as total ,sum(`total_fee`)*0.01 as total_fee FROM `ky_order` WHERE `order_status` = 5 and `order_end_time` <= 1524844799 GROUP BY `thera_id` ) o
left JOIN `ky_therapis` t on t.`thera_id` =o.thera_id
LEFT JOIN `ky_therapis_data` d on d.`thera_id` =o.thera_id


//订单分类统计
select
count(*) as "订单总数",
sum(IF (`order_status`=0 or order_status=1 or `order_status`=2  , 1 , 0)) as "取消的订单",
sum(IF (`order_status`=3 and `total_fee`=0  , 1 , 0)) as "免费成功订单数",
sum(IF (`order_status`=3 and `total_fee`>0  , 1 , 0)) as "收费成功订单数",
sum(IF (`order_status`=3  , `total_fee` , 0))/100 as gmv,
from_unixtime(`create_time`,'%Y-%m-%d') as day
from `ky_order` WHERE  `create_time`  > UNIX_TIMESTAMP('2018-12-01 0:0')   GROUP BY day

//咨询师数据导出
select t.thera_id ,t.`name`  , `mobile`,`email` ,FROM_UNIXTIME( `create_time` ,' %Y-%m-%d') as "创建时间" , c.hours as "小时",
CASE t.`level`
	WHEN "1" THEN  "执业"
	WHEN   "3" THEN  "新手"
ELSE  "其他" END as "等级"
from `ky_therapis` t
left join (select thera_id , sum(hours) as hours from ky_therapis_consultation group by thera_id) c on t.thera_id = c.thera_id
where `create_time`  <= UNIX_TIMESTAMP('2018-08-06 12:0') and `review`= 1
order by t.thera_id desc

//订单导出数据
SELECT
`order_id`  ,
`user_id`  ,
t.`name`  as "咨询师名字" ,
from_unixtime(o.`accept_time`)  as "预约时间" ,
o.`total_fee` /100 as "订单价格" ,
IF (o.`method` = 1 , "面对面" , "视频") as "咨询方式"  ,
from_unixtime(o.`create_time`)   as "创建时间",
CASE o.`order_status`
	WHEN "4" THEN  "确认"
	WHEN   "5" THEN  "完成"
ELSE  "其他" END as "订单状态"
FROM `ky_consultant`.`ky_order`  o LEFT JOIN `ky_therapis` t on o.`thera_id`  = t.`thera_id`
WHERE  o.`create_time`  >= UNIX_TIMESTAMP('2018-11-12 0:0')  and o.`create_time`  < UNIX_TIMESTAMP('2018-11-19 0:0')  and o.`order_status`  IN (5)  ORDER BY `order_id` ASC

//导出已结算的订单
select order_id as "orderId" , total_fee/100 as "订单单价" , from_unixtime(`order_end_time`) as "订单完成时间", t.`name`  as "咨询师名字"  , t.`thera_id`  as "咨询师ID" , t.mobile as "咨询师联系方式"
FROM `ky_consultant`.`ky_order`  o LEFT JOIN `ky_therapis` t on o.`thera_id`  = t.`thera_id`
where funds_id in (select id from ky_therapis_funds WHERE  `status`  = 1 and `type` =2  and success_time < UNIX_TIMESTAMP('2019-01-01 0:0')) and order_status = 5

select count(*) from `ky_consultant`.`ky_order` where funds_id in (select id from ky_therapis_funds WHERE  `status`  = 1 and `type` =2  and success_time < UNIX_TIMESTAMP('2019-01-01 0:0')) and order_status = 5

//重复日期
SELECT *  , count(*) as a FROM `ky_consultant`.`ky_schedule` WHERE day > unix_timestamp(now())  GROUP BY  `thera_id` ,`start_time`  HAVING  a>1


//每个月交易
SELECT  SUM(total_fee/100) , from_unixtime(`create_time` ,  '%Y-%m') as m  FROM `ky_order` WHERE `order_status` =3  and `pay_status` = 2 GROUP BY  m


SELECT
`id` , user_id , `name` as "姓名" , contact_info as "联系方式" ,
if(`gender`=1,"男" ,"女") as "性别" , `age` as "年纪" ,  if(`marriage`=1,"有"  , "无") as "婚姻" , if(children=1 , "有" , "无") as "孩子" ,
urgent_name as "紧急联系人姓名" , `urgent_phone` as "联系人电话" , if(has_mental_healing=1 , "有" , "无") as "接受过精神科相关的诊断和治疗" ,
if(has_psychotherapy=1 , "有" , "无") as "心理咨询" , `reason`as "原因" , `other` as "其他" , from_unixtime(`create_time`)   as "创建时间"
FROM `ky_consultant`.`ky_order_user` ORDER BY `id` DESC


SELECT
t.* , from_unixtime(o.`accept_time`) as cc ,  (o.accept_time - unix_timestamp(now())) as ot , IF (t.`last_order_id` , t.`last_order_id`  , t.`first_order_id` ) as sorderid
from `ky_therapis_user` t LEFT join `ky_order` o on t.`last_order_id` = o.`order_id` where t.`thera_id` = 504 and  t.`first_order_id` > 0 order by o.`order_status` ASC  , ot asc
```

ps aux | grep "php ServerLast.php" | awk '{print $2}' | xargs kill -9



时间格式化函数
select from_unixtime(`create_time`)


SELECT * , t.a FROM  `ky_therapis_data` d  
LEFT JOIN  (SELECT `thera_id`  , SUM(`total_fee`) as a FROM  `ky_order`  WHERE `order_status`  = 5 and `funds_id` =0 GROUP BY  `thera_id`  ) t on  d.`thera_id`  = t.thera_id
WHERE  d.`valid_balance`  != t.a
