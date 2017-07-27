### 新特性
* 命名空间
* 匿名函数
* 采用数组短语的形式；[[]]
* 标准PHP库(SPL) 类和接口
* 使用PHP intl 扩展实现国际化支持
* 特性(Traits)

### 基础类
* Yii1.1
```
基础类 CComponent ，提供了属性支持等基本功能，因此几乎所有的Yii核心类都派生自该类。
```
* Yii2.0
```
拆分成了 yii\base\Object 和 yii\base\Component
这一功能上的明确划分，带来了效率上的提升
yii\base\Object 与 yii\base\Component 两者并不是同一层级的，前者是后者父类。
```

### 别名
* Yii1.1
```
别名以 . 的形式使用
RootAlias.path.to.target
```
* Yii2.0
```
别名以 @ 前缀的方式使用
@yii/jui

不仅有路径别名，还有URL别名
// 路径别名
Yii::setAlias('@foo', '/path/to/foo');
// URL别名
Yii::setAlias('@bar', 'http://www.example.com');

```



### 视图
* 渲染
```
$this->render(）
    Yii1.0：直接输出返回的文章
    Yii2.0：返回结果但是不输出

静态文件加载

```


### 模型
* Yii1.1
```php
<?php
if (isset($_POST['Post'])) {
    $model->attributes = $_POST['Post'];
}


class UserForm extends CFormModel
{
    public $username;
    public $email;
    public $password;
    public $password_repeat;
    public $rememberMe=false;

    public function rules()
    {
        return array(
            // username 和 password 在所有场景中都要验证
            array('username, password', 'required'),

            // email 和 password_repeat 只在注册场景中验证
            array('email, password_repeat', 'required', 'on'=>'Registration'),
            array('email', 'email', 'on'=>'Registration'),

            // rememberMe 仅在登陆场景中验证
            array('rememberMe', 'boolean', 'on'=>'Login'),
        );
    }
}
?>

```
* Yii2.0
```
去除了CFormModel的表单限制

$model = new Post;
if ($model->load($_POST)) {
    ... ...
}

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord
{
    public function scenarios()
    {
        return [
            'login' => ['username', 'password', 'rememberMe'],
            'registration' => ['username', 'email', 'password', 'password_repeat'],
        ];
    }
}
```
### Active Record
* Yii1.1
```
数据库查询被分散成 CDbCommand ， CDbCriteria 和 CDbCommandBuilder
```
* Yii2.0
```
Yii2.0，采用 yii\db\Query 来表示数据库查询

```
