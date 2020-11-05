# 小猿社H5源码 后端+前端


### 技术栈

- PHP7.3
- Swoole4.5
- EasySwoole3.4
- Redis
- Mysql5.7+
- Jquery
- Vue2
- Vant2


### 项目结构
```
PATH  部署目录
├─App           应用目录
│  ├─HttpController      Http控制器目录
│  ├─WebSocket           WebSocket控制器目录
│  ├─Crontab             定时器、计划任务
│  ├─Config              配置文件目录
│  │  ├─app.php              应用配置
│  │  ├─db.php               数据库配置
│  │  ├─redis.php            redis服务器配置
│  │  └─session.php          Session配置
│  ├─Common              其他公共类
│  ├─Model               模型目录
│  ├─Views               html视图目录
│  └─helper.php          公共函数文件
├─Public         静态资源目录
├─Temp           临时信息、缓存目录
├─vendor         PHP包源码目录
├─dev.php        服务器配置信息
├─EasySwooleEvent.php    服务器事件处理
├─nginx_make.php    Nginx代理生成工具
├─run            快捷启动可执行文件
├─...

```

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
2. 运行 `./run start` 启动项目
3. 后台运行 `./run start -d`
4. 重启 `./run restart`
5. 停止 `./run stop`


--------

#### 更新记录
---
 `20-08-14` 首次提交
 - 初代版本

#### License
---
The Apache License.

Copyright (c) 2020 Kyour.cn
