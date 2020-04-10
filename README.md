# Laravel 阿里云ACM配置中心客户端

该客户端实现从ACM拉取配置，存放一个配置文件acm.json到根目录。并在启动时更新配置

## 安装

 ```
  composer require donjan-deng/laravel-acm-client
 ```
该包已实现自动注册service provider，你也可以手动注册，编辑`config/app.php`

```
'providers' => [
    // ...
    Donjan\AcmClient\Providers\AcmServiceProvider::class,
];
```
### Lumen

修改`bootstrap/app.php`

```
$app->register(Donjan\AcmClient\Providers\AcmServiceProvider::class);
```

到 https://acm.console.aliyun.com 添加配置，JSON格式,这个例子修改了app.name和mysql的数据库名

```
{
    "app.name":"new name",
    "database.connections.mysql.database":"new-database"
}
```

.env文件添加环境变量

```
//命名空间ID
ALIYUN_ACM_NAMESPACE=ef3948fa-d0d5-4119-bc75-33a5b76126fe
//Data ID
ALIYUN_ACM_DATA_ID=laravel
//GROUP
ALIYUN_ACM_GROUP=LARAVEL
//访问的ak与sk
ALIYUN_ACM_AK=******
ALIYUN_ACM_SK=******
```
## 使用

手动执行命令`php artisan acm:get-config`拉取配置。

如果你启用了任务调度，修改`App\Console\Kernel`,添加一个任务

```
$schedule->command('acm:get-config')->everyMinute();
```

或者通过crontab

`* * * * * www php /projectpath/artisan acm:get-config`

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
