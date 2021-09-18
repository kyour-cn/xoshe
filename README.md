# 小猿社H5源码 后端+前端 (不再维护)

> `注意` 该项目将不再更新维护，已转向其他类似项目开发，后期可能将成熟的经验用于此项目的重构！

> 小猿社是一款兴趣社区平台系统，采用PHP语言、Swoole扩展的常驻内存方式开发，前端支持H5和Apicloud的手机App。

> 该系统支持文字图片、视频的发布，拥有多种互动模式，点赞、评论、关注、收藏功能，更有话题、社区等模式。
> 该系统运用了单页面、Websocket、Vue、Vant等技术和框架，更具良好体验。

### 技术栈

- PHP7.3
- Swoole4.5
- EasySwoole3.4
- Redis
- Mysql5.7+
- Vue2
- Vant2

### 项目结构
```
PATH  部署目录
├─App           应用目录
│  ├─HttpController      Http控制器目录
│  ├─WebSocket           WebSocket控制器目录
│  ├─Crontab             定时器、计划任务
│  ├─Common              其他公共类
│  ├─Model               模型目录
│  ├─Views               html视图目录
│  └─helper.php          公共函数文件
├─Config              配置文件目录
│  ├─app.php              应用配置
│  ├─database.php         数据库配置
│  ├─redis.php            redis服务器配置
│  └─session.php          Session配置
├─Public         Web静态资源目录
├─Temp           临时信息、缓存目录
├─vendor         PHP包源码目录
├─bootstrap.php  bootstrap事件
├─composer.json  Composer包配置信息
├─dev.php        服务器配置信息
├─EasySwooleEvent.php    服务器事件处理
├─nginx_make.php    Nginx代理生成工具
├─sword             快捷启动可执行文件
├─...
```

数据库文件在“文档”目录xoshe.sql

### 运行环境及条件
> PHP 7.1+
> Swoole 4.4+
> EasySwoole 3.4+

...更多查看 “文档”中的详细说明

## 配置Nginx代理
由于Swoole不能友好的处理静态资源访问、ssl证书等等
所以使用nginx是再好不过的选择
1.打开`nginx_make.php`文件
2.编辑config数组变量信息，根据注释提示填写
3.命令行运行 `./nginx_make.php`
4.将同目录生成的`nginx.conf`文件配置到nginx中
5.重启nginx服务，成功

## 项目启动

1. 使用命令行cd到项目目录(App文件夹同路径)
2. 运行 `./sword server start` 启动项目
3. 后台运行 `./sword server start -d`
4. 重启 `./sword server restart`
5. 停止 `./sword server stop`

---

## 更新记录

详细查看 /文档/更新日志.md
[/文档/更新日志.md](https://github.com/kyour-cn/xoshe/blob/main/%E6%96%87%E6%A1%A3/%E6%9B%B4%E6%96%B0%E6%97%A5%E5%BF%97.md)


## 参与开发

> 直接提交PR或者Issue即可

## 版权信息

本项目遵循Apache2.0 开源协议发布，并提供免费使用。

本项目包含的第三方源码和二进制文件之版权信息另行标注。

更多细节参阅 [LICENSE.txt](LICENSE.txt)
