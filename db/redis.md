```php
$redis = \Yii::$app->redis;
$redis->set("2018012507392150459", 1);
$redis->expire("2018012507392150459", 3);
$redis->psubscribe(['__keyevent@6__:expired'] ,  function($redis, $pattern, $channel, $msg) {
    var_dump($msg);
    $order = Order::getById($msg);
    var_dump($order);
    var_dump($order->formatForUserDetail);
});
```