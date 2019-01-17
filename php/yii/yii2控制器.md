### 控制前的操作

    在Yii中，当请求一个Url的时候，
    首先在application中获取request信息，
    然后由request通过urlManager解析出route，
    再在Module中根据route来创建controller并处理request。

#### Yii中总共有三种控制器类

    base\Controller.php        这个是下面两个的基类
    console\Controller.php   这个是控制台控制器
    web\Controller.php        这个是web控制器


##### 先看看基类base\Controller.php，在基类中大致可分为三个部分

    class Controller extends Component implements ViewContextInterface
    和action相关的功能
    和render相关的功能
    其它功能
