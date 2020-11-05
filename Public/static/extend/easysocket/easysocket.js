"use strict";
/**
 * Ws模块封装 EasySocket.js v0.0.3
 * 该模块目前仅支持单列模式，不能同时启用多个连接
 * @authar kyour@vip.qq.com
 * @site blog.kyour.cn
 */
(function(w){

    /**
     * 创建对象构造函数
     * new Swoft(conf)
     * @param object conf 连接参数
     */
    var sw = function(conf){
        //更新配置
        for(var k in conf){
            this.conf[k] = conf[k];
        }
        if("WebSocket" in w){
            this.log('Your browser support WebSocket!');
        }else{
            this.log('Your browser doesn\'t support WebSocket!');
        }
        window.EasySocketConn = this;

        setInterval(this.sendHeart,this.conf.heartSleep*1000);
        return this;
    };

    sw.prototype = {
        //版本
        version: '0.0.3',
        //默认配置
        conf: {
            url:'', //ws链接
            heart: 'heart', //心跳内容
            heartSleep: 60, //心跳间隔-秒
            sessionName: '', //session的Cookie名称
            sessionId: '', //优先使用sessionId,若为空再从cookie获取sessionName的值
            ajaxToken: 'ES_TOKEN',
            reconn: true, //是否自动重连
            eventKey: 'cmd', //事件名称的键名
            debug: false //是否开启调试模式
        },
        socket: null, //socket对象
        createTime: 0, //创建时间
        reConnData: [],
        listener: {}, //监听列表
        ajaxQueue: {}, //ajax消息队列
        // 可注册的ws事件
        event: {
            open: null,
            close: null
        },
        //打印日志
        log: function(data){
            if(this.conf.debug)
                console.log(data);
        },
        /**
         * 初始化并连接服务器
         * @param open 连接成功的回调闭包函数
         */
        init: function(openFunc) {

            this.event.open = openFunc;
            this.log('Websocket Connecting: '+this.conf.url);
            var ws = new WebSocket(this.conf.url);
            this.socket = ws;
            //ws事件注册
            ws.onopen = this.onOpen;
            ws.onmessage = this.onMessage;
            ws.onclose = this.onClose;
        },
        /**
         * 添加后端事件监听
         * @param eventName 事件名称
         * @param func 回调闭包函数
         */
        addListener: function(eventName,func) {
            this.listener[eventName] = func;
        },

        /**
         * 向服务器发送数据
         * @param data 发送的数据内容
         * @param isReconn 是否重连发送
         */
        send: function(data,isReconn) {

            if(isReconn){
                this.reConnData.push(data);
            }
            if(typeof data == 'object'){
                //添加session值
                if(this.conf.sessionId){
                    data[this.conf.sessionName] = this.conf.sessionId;
                }else if(this.conf.sessionName){
                    data[this.conf.sessionName] = this.sessionId();
                }
                data = JSON.stringify(data);
            }

            // alert('发送'+data)
            this.socket.send(data);
        },
        /**
         * 模拟ajax请求的方式
         * 用于单一请求，就不再添加事件监听
         * @param param 发送的数据内容
         * @param callBack 收到会话的回调闭包函数
         */
        ajax: function(param, callBack) {

            //生成随机Token -32位
            var token = this.randomString();

            var req = {
                data: param.data?param.data:{}
            };
            //添加ajax的Token
            req[this.conf.ajaxToken] = token;
            //事件名赋值，与配置的eventKey相同
            req[this.conf.eventKey] = param[this.conf.eventKey];
            //发送请求
            try{
                this.send(req);
            }catch (e) {
                callBack(null, {code: 300, message: e})
                return;
            }

            //储存回调闭包函数
            this.ajaxQueue[token] = callBack;

            //超时检测
            if(param.outtime){
                setTimeout(function (){
                    var conn = window.EasySocketConn;
                    if(conn.ajaxQueue[token]){
                        var msg = 'Response timeout.';
                        conn.log(msg);
                        conn.ajaxQueue[token](null, {code: 1, message: msg});
                        delete conn.ajaxQueue[token];
                    }
                },param.outtime * 1000);
            }
        },
        /**
         * 发送心跳
         */
        sendHeart: function() {
            var conn = window.EasySocketConn;
            if(conn.socket !== null){
                //执行闭包函数
                if(typeof conn.conf.heart.data == 'function'){

                    var d = {};
                    for(var i in conn.conf.heart){
                        d[i] = conn.conf.heart[i];
                    }
                    d.data = conn.conf.heart.data()
                    conn.send(d);
                }else{
                    conn.send(conn.conf.heart);
                }
            }
        },
        /**
         * ws事件：连接成功
         */
        onOpen: function() {
            var conn = window.EasySocketConn;

            conn.log('Websocket Connected.');

            //重连发送数据
            for(var k in conn.reConnData){
                conn.send(conn.reConnData[k]);
            }
            if(typeof conn.event.open == 'function'){
                conn.event.open();
                conn.event.open = null;
            }
        },
        /**
         * ws事件：收到服务端消息
         */
        onMessage: function(evt) {
            var conn = window.EasySocketConn;
            //解析服务器数据
            var data = conn.deJson(evt.data);
            if(data === false){
                if(conn.conf.debug)
                    conn.log("Websocket Raw Data："+evt.data);
                return;
            }

            //判断是否ajax返回的消息
            var token = conn.conf.ajaxToken;
            if(data[token] && conn.ajaxQueue[data[token]]){
                conn.ajaxQueue[data[token]](data);
                delete conn.ajaxQueue[data[token]];
                return;
            }

            //判断是否有此事件监听
            if(data[conn.conf.eventKey] && conn.listener[data[conn.conf.eventKey]]){
                conn.listener[data[conn.conf.eventKey]](data);
            }
        },
        /**
         * ws事件：连接成功
         */
        onClose: function() {
            var conn = window.EasySocketConn;
            conn.socket = null;

            //断开事件回调
            if(typeof conn.event.close == 'function'){
                conn.event.close();
                // conn.event.close = null;
            }

            //自动重连
            if(conn.conf.reconn){
                conn.log("Websocket Disconnected, reconnecting...");
                setTimeout(function(){
                    conn.log("Websocket Disconnected, reconnecting...");
                    conn.init()
                },1000);
            }
        },
        //字符串转json
        deJson: function(str) {
            if (typeof str == 'string') {
                try {
                    var obj = JSON.parse(str);
                    if(typeof obj == 'object' && obj ){
                        return obj;
                    }
                } catch(e) {
                    return false;
                }
            }
            return false;
        },
        //生成随机字符串
        randomString: function(e) {
            e = e || 32;
            var t = "ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678",
            a = t.length,
            n = "";
            for (var i = 0; i < e; i++) n += t.charAt(Math.floor(Math.random() * a));
            return n;
        },
        //获取cookie中的sessionId
        sessionId: function(){
            var name = this.conf.sessionName;

            var cookieArr = document.cookie.split("; ");
            for(var i = 0; i < cookieArr.length; i++){
                var arr = cookieArr[i].split("=");
                if (arr[0] == name){
                    return arr[1];
                }
            }
            return "";
        }
    };

    //es类
    w.EasySocket = sw;

    //es的实列化对象
    w.EasySocketConn = null;
})(window);
