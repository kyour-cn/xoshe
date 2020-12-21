"use strict";
// 应用对象
const App = {
    //应用名 -在入口文件页直接修改
    name: '',
    // 默认元素选择器
    el: '#v-page',
    host: '', //域名
    href: '', //服务端地址
    local: '', //本地地址
    res: '', //资源地址
    back_history: [],
    //临时数据
    temp: {},
    // 加载的资源
    $res: {
        css: [],
        js: []
    },
    //窗口数组
    // win: [],
    //是否App
    isApp: false,
    // 路由 -历史路径
    route: [],
    // 公共vue实例对象
    view: null,
    //全端通用ajax-http
    ajax(conf, callback){

        if('base' in conf){
            conf.url = conf.base + conf.url;
        }else{
            conf.url = this.href + conf.url;
        }

        if(this.isApp){
            var _data = conf.data;
            conf.data = {values: _data}
            api.ajax(conf, callback);
        }else{
            var _conf = {
                url: conf.url,
                method: conf.method? conf.method: 'get',
                timeout: conf.timeout? conf.timeout: 5 * 1000
            };

            if(conf.data){
                if(_conf.method === 'get'){
                    _conf.params = conf.data;
                }else{
                    _conf.data = new FormData();
                    for(var i in conf.data){
                        _conf.data.append(i, conf.data[i]);
                    }
                }
            }

            axios.request(_conf)
                .then(function(res){
                    callback(res.data, null);
                })
                .catch(function(err){
                    callback(null, err);
                });

        }

    },
    //模拟jq中的load
    load(el, url, callback){
        var _this = this;
        if(this.isApp){
            api.ajax({
                url: url,
                dataType: 'text',
                method: 'get'
            },function(ret, err){
                if(ret) {
                    _this.html(el, ret);
                }
                if(callback) callback(ret, err);
            });
        }else{
            axios.get(url)
                .then(function(res){
                    _this.html(el, res.data);
                    if(callback) callback(ret, null);
                })
                .catch(function(err){
                    if(callback) callback(null, err);
                });
        }
    },
    //获取模板标签
    getTemplateAttr(t, e) {
        var n = "<" + e + ">", r = "</" + e + ">";
        if ("" === t || t.indexOf(n) < 0 || t.indexOf(r) < 0) return "";
        return t.substring(t.indexOf(n) + n.length, t.lastIndexOf(r));
    },
    //模拟jq中的html
    html(el, html){
        el = this.dom(el);
        el.innerHTML = this.getTemplateAttr(html,'template');

        var js = this.getTemplateAttr(html,'script');
        if(js && 'execScript' in window){
            window.execScript(js);
        }else if(js){
            window.eval(js);
        }
    },
    // 动态加载资源文件
    loadRes(fname, ftype, callback){
        let fr;
        if (ftype === "js"){
            fr = document.createElement('script');
            fr.setAttribute("type","text/javascript");
            fr.setAttribute("src",fname);
        } else if (ftype === "css"){
            fr = document.createElement("link");
            fr.setAttribute("rel","stylesheet");
            fr.setAttribute("type","text/css");
            fr.setAttribute("href",fname);
        }else return;

        if(typeof fr != "undefined"){
            document.getElementsByTagName("head")[0].appendChild(fr);
        }
        this.$res[ftype].push(fname);

        if(callback){
            callback();
        }
    },
    //移除单个资源文件
    remRes(fname, ftype){
        let tgel = (ftype === "js") ? "script" : (ftype === "css") ? "link" : "none";
        let tga = (ftype === "js") ? "src" : (ftype === "css") ? "href" : "none";
        let asp = document.getElementsByTagName(tgel);
        for (let i = asp.length; i >= 0; i--){
            if (asp[i] && asp[i].getAttribute(tga) !== null && asp[i].getAttribute(tga).indexOf(fname) !== -1) {
                asp[i].parentNode.removeChild(asp[i]);
            }
        }
    },
    //移除当前全部资源文件 -css
    remAllRes(){
        for(let i in this.$res.css){
            this.remRes(this.$res.css[i], 'css');
            delete this.$res.css[i];
        }
    },
    //设置标题
    setTitle(t){
        window.document.title = t;
    },
    //批量use组件
    use(arr, ns){
        for(let i in arr){
            Vue.use(ns[arr[i]]);
        }
    },
    //打开新页面
    open(url, isRoute = true){
        // 显示遮罩
        // App.view.overlay = true;
        // 销毁上一页
        if(page && page.$destroy) page.$destroy();
        if(isRoute){
            // 改变地址栏
            window.location.href = "#" + url;
            // 添加路由记录
            this.route.push(url);
        }
        let realUrl = this.formatRoute(url)['url'];
        this.load('#v-page',realUrl, function (res, err) {
            // 新页面加载完成后
            App.view.overlay = false;
        });
        // $('#v-page').load(realUrl, null, (res, status, xhr) => {
        //     if(status == 'success'){
        //         // 新页面加载完成后
        //         App.view.overlay = false;
        //     }
        // });
    },
    //返回上一页
    toBack(){
        if(this.route.length <= 1){
            window.location.href = '/';
            return console.warn('No previous page');
        }
        //去除当前页记录
        this.route.pop();
        //取最后一条
        let last = this.route.pop();
        this.open(last);
    },
    addBack: function(callback){
        App.back_history.push(callback);
    },
    goBack: function(exec){
        var pop = App.back_history.pop();
        if(exec === true) return true;
        if(exec === -1){
            App.back_history = [];
            return true;
        }
        if(!pop){
            // vant.Toast('即将退出应用');
            return false;
        }
        pop();
        return true;
    },
    //重定向，修改当前url
    redirect(url){
        this.route[this.route.length -1] = url;
        window.location.href = "#" + url;
    },
    //解析路径
    formatRoute(url){
        let index1 = url.indexOf("#") +1;
        let index2 = url.indexOf("-");
        if(index2 === -1){index2 = url.length;}
        return {
            //url路径
            url: url.substr(index1,index2),
            //二级参数 -符号后面的
            param: url.substr(index2 +1)
        };
    },
    //获取当前路径
    getRoute(){
        return this.route[this.route.length -1];
    },
    //获取cookie
    getCookie(name){
        let cookieArr = document.cookie.split("; ");
        for(let i = 0; i < cookieArr.length; i++){
            let arr = cookieArr[i].split("=");
            if (arr[0] == name){
                return arr[1];
            }
        }
        return "";
    },
    //元素选择器
    dom(el){
        return document.querySelector(el);
    },
    openApp(url = 'open', scheme = 'xoshe'){
        var iframe = document.createElement('iframe');
        iframe.style.cssText='display:none;width=0;height=0';
        document.body.appendChild(iframe);
        iframe.src = scheme + '://' + url;
        setTimeout(function() {
            window.location.href = '/download';
        }, 500);
    },
    getYmd(ns, str = '.'){
        var d, s;
        d =  new Date(parseInt(ns)*1000);
        s = d.getFullYear() + str;
        s += ("0"+(d.getMonth()+1)).slice(-2) + str;
        s += ("0"+d.getDate()).slice(-2);
        return s;
    },
    //打开新的App窗口
    openWin(name = 'appwin', param){
        if(!param.root){
            param.root = 'html/app/appwin.html';
        }
        if(this.isApp){
            api.openWin({
                name: name,
                url: 'widget://'+param.root,
                pageParam: param
            });
        }else{
            vant.Dialog.confirm({
                title: '抱歉',
                message: '该功能在浏览器中不可用，是否启动App？',
            })
                .then(() => {
                    App.openApp();
                })
                .catch(() => {
                });
        }
    }

};
