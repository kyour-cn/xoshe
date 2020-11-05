# EasySocket.js

**EasySocket.js** : 无需基于任组件在Web应用中规范、快速的使用WebSocket技术

### 主要特性

- 快速在你的网站中使用Websocket开发
- 事件监听机制
- 模拟ajax请求，像http使用一样方便
- 使用json格式传输数据
- 自动重连、心跳包管理
- 兼容主流浏览器

--------

**EasySocket.js** 是一个Web插件，无需基于任组件方便快捷的使用开发Ws应用，完美兼容Swoft、EasySwoole等后端框架。


#### Download & install

[Github download](https://github.com/pandao/editor.md/archive/master.zip)


```html
<script src="easysocket.min.js"></script>
```

##### 使用示例

```html
...
<script src="easysocket.min.js"></script>
...
<script>

    var ws_host = '127.0.0.1:8100';
    //实例化EasySocket
    var es = new EasySocket({
        //ws服务器链接
        url: (document.location.protocol == 'https:'?'wss':'ws')+'://'+ws_host,
        //心跳内容
        heart: {
            cmd: 'index.hello',
            data: function (){
                return {
                    key: 'testKey'
                };
            }
        },
        //心跳间隔时间-秒
        heartSleep: 5,
        //事件名称的键名
        eventKey: 'cmd',
        //断开是否自动重连
        reconn: true,
        //调试模式
        debug: true
    });

    //连接服务器
    es.init(function(){

        alert('连接成功');

    });

</script>
...
```

##### 添加事件监听

```javascript
...
    //连接服务器
    es.init(function(){

        // 连接成功

        //注册消息事件
        es.addListener('user.login',function(ret) {

            // console.log(ret);
            if(ret.data.status == 1){
                //发送获取数据请求
                es.send({
                    cmd: 'home.getData',
                    data: {
                        user: 'test'
                    }
                },true);
            }

        });
    });
...
```

##### 使用Ajax
ajax是对json数据传输的封装，解决过多事件注册的不便。
使用ajax功能，在后端需要返回上传的sessId参数和数据，这样前端才能绑定到会话中。

```javascript
...

    //模拟ajax请求
    es.ajax({
        cmd: 'index.hello',
        outtime: 5, //超时时间，秒
        data: {
            name: 'test'
        }
    },function(ret,err){
        if(ret){
            console.log(ret);
        }else{
            alert(err.msg);
        }
    });
...
```

#### 更新记录
---
 `v0.0.1` 20-08-14
 - 初代版本

 `v0.0.2` 20-09-29
 - 增加模拟ajax功能
 - 修复数据获取闭包回调不刷新数据的错误
 - 增加debug配置项，并将控制台log信息改为英文

 `v0.0.2` 20-10-22
 - 修复自动重连的异常
 - 新增自定义ajax的token
 - 新增session自动上传

#### License
---
The Apache License.

Copyright (c) 2020 Kyour.cn
