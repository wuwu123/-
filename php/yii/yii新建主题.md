### yii2 创建新的应用主体
1 yii高级版本的安装
```
composer create-project --prefer-dist yiisoft/yii2-app-advanced projectName
```

2 项目复制
```
wx
```

3 api环境
```
cp -a environments/dev/frontend environments/dev/api
cp -a environments/prod/frontend environments/prod/api
```

4 修改 environments/index.php 文件之后的代码
```php
<?php
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'api/runtime',
            'api/web/assets',
        ],
        'setExecutable' => [
            'yii',
            'yii_test',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'api/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'backend/runtime',
            'backend/web/assets',
            'frontend/runtime',
            'frontend/web/assets',
            'api/runtime',
            'api/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'frontend/config/main-local.php',
            'api/config/main-local.php',
        ],
    ],
];
```

5 项目初始化
```
php init
```

6 添加api文件夹别名，去 common/config/bootstrap.php 最后一行添加如下代码
```
Yii::setAlias('api', dirname(dirname(__DIR__)) . '/api');
```