'use strict';

// 继承
var inherit = (function() {
    var F = function() {};
    return function(Target, Origin) {
        F.prototype = Origin.prototype;
        Target.prototype = new F();
        Target.prototype.constuctor = Target;
        Target.prototype.uber = Origin.prototype;
    }
}());

// 判断引用值和原始值类型
function type(target) {
    var typeStr = typeof(target),
        toStr = Object.prototype.toString,
        objStr = {
            "[object Array]" : "array",
            "[object Object]" : "object",
            "[object Number]" : "number - object",
            "[object Boolean]" : "boolean - object",
            "[object String]" : "string - object",
        }
    if (target === null) {
        return "null";
    }else if (typeStr !== "object") {
        return typeStr;
    }else{
        return objStr[toStr.call(target)];
    }
}

// 网络去抖动
function debounce(hander, delay = 1000) {
    var timer = null;
    return function() {
        var self = this,
        arg = arguments;
        clearTimeout(timer);

        timer = setTimeout(function() {
            hander.apply(self, arg);
        }, delay);
    }
}

// 求字符串的长度
function retByteslen(target) {
    var count,
        len;
        count = len = target.length;
    for (var i = 0; i < len; i++) {
        if (target.charCodeAt(i) > 255) {
            count ++;
        }
    }
    return count;
}

// 深层拷贝
// 1. 遍历对象 for (var prop in obj)
// 2. 判断是不是原始值 typeof() object
// 3. 判断是数组还是对象 instanceof toString constructor
// 4. 建立相应的数组或对象
// 5. 递归
function deepClone(origin, target) {
    var target = target || {},
        toStr = Object.prototype.toString,
        arrStr = "[object Array]";

    for (var prop in origin) {
        if (origin.hasOwnProperty(prop)) {
            if (origin[prop] !== "null" && typeof(origin[prop]) == "object") {
                target[prop] = toStr.call(origin[prop]) == arrStr ? [] : {};
                deepClone(origin[prop], target[prop]);
            }else{
                target[prop] = origin[prop];
            }
        }
    }
    return target;
}

// 数组去重
Array.prototype.unique = function () {
    var temp = {},
        arr = [],
        len = this.length;
    for (var i = 0; i < len; i ++) {
        if (!temp[this[i]]) {
            temp[this[i]] = 'tmp';
            arr.push(this[i]);
        }
    }
    return arr;
}

// 返回一个节点的子元素
function retElementChild(node) {
    var tmp = {
            length : 0,
            push : Array.prototype.push,
            splice : Array.prototype.splice
        },
        child = node.childNodes,
        len = child.length;
    for (var i = 0; i < len; i++) {
        if (child[i].nodeType === 1) {
            tmp.push(child[i]);
        }
    }
    return tmp;
}

/**
 * 求滚动条滚动距离
 * @Author   hflxhn.com
 * @DateTime 2019-11-03T14:09:06+0800
 */
function getScrollOffset() {
    if (window.pageXOffset) {
        return {
            x : window.pageXOffset,
            y : window.pageYOffset
        }
    }
    return {
        x : document.body.scrollLeft + document.documentElement.scrollLeft,
        y : document.body.scrollTop + document.documentElement.scrollTop
    }
}

/**
 * 获取可视窗口的大小
 * @Author   hflxhn.com
 * @DateTime 2019-11-03T14:29:24+0800
 */
function getViewportOffset() {
    if (window.innerWidth) {
        return {
            w : window.innerWidth,
            h : window.innerHeight
        }
    }else{
        if (document.compatMode == "BackCompat") {
            return {
                w : document.body.clientWidth,
                h : document.body.clientHeight
            }
        }else{
            return {
                w : document.documentElement.clientWidth,
                h : document.documentElement.clientHeight
            }
        }
    }
}

/**
 * 获取元素的属性值 getComputedStyle
 * getComputedStyle 的第二个参数 是获取伪元素的样式表
 * @Author   hflxhn.com
 * @DateTime 2019-11-03T15:19:24+0800
 * @param    {obj}                 elem 元素
 * @param    {str}                 prop 样式属性 eg: width
 */
function getStyle(elem, prop) {
    if (window.getComputedStyle) {
        return window.getComputedStyle(elem, null)[prop];
    }else{
        return elem.currentStyle[prop];
    }
}

/**
 * 封装事件处理函数
 * 解决兼容性问题
 * @Author   hflxhn.com
 * @DateTime 2019-11-03T16:17:23+0800
 * @param    {obj}                 elem  绑定在谁身上
 * @param    {str}                 type   事件类型
 * @param    {fun}                 handle 事件处理函数
 */
function addEvent(elem, type, handle) {
    if (elem.addEventListener) {
        elem.addEventListener(type, handle, false);
    }else if(elem.attachEvent) {
        elem.attachEvent('on' + type, handle);
    }else{
        elem['on' + type] = handle;
    }
}

/**
 * 移除事件处理函数
 * @Author   hflxhn.com
 * @DateTime 2019-11-03T18:05:32+0800
 * @param    {obj}                 elem  绑定在谁身上
 * @param    {str}                 type   事件类型
 * @param    {fun}                 handle 事件处理函数
 */
function removeEvent(elem, type, handle) {
    if (elem.removeEventListener) {
        elem.removeEventListener(type, handle, false);
    }else{
        elem.attachEvent('on' + type, handle);
    }
}

/**
 * 阻止事件冒泡
 * @Author   hflxhn.com
 * @DateTime 2019-11-03T16:56:16+0800
 * @param    {事件}                 event 事件对象
 */
function stopBubble(event) {
    if (event.stopPropagation) {
        event.stopPropagation();
    }else{
        event.cancelBubble();
    }
}

/**
 * 阻止默认事件
 * @Author   hflxhn.com
 * @DateTime 2019-11-03T16:56:16+0800
 * @param    {事件}                 event 事件对象
 */
function cancelHandler(event) {
    if (event.preventDefault) {
        event.preventDefault();
    }else{
        event.returnValue = false;
    }
}

/**
 * 拖拽元素
 * @Author   hflxhn.com
 * @DateTime 2019-11-03T18:06:11+0800
 * @param    {obj}                 elem [要拖拽元素]
 */
function drag(elem) {
    var dis_x,
        dis_y;
    addEvent(elem, 'mousedown', function(e) {
        var event = e || window.event;
        dis_x = event.clientX - parseInt(getStyle(elem, 'left'));
        dis_y = event.clientY - parseInt(getStyle(elem, 'top'));
        addEvent(document, 'mousemove', mouseMove);
        addEvent(document, 'mouseup', mouseUp);
        stopBubble(event);
        cancelHandler(event);
    });
    function mouseMove(e) {
        var event = e || window.event;
        elem.style.left = event.clientX - dis_x + 'px';
        elem.style.top = event.clientY - dis_y + 'px';
    }
    function mouseUp(e) {
        var event = e || window.event;
        removeEvent(document, 'mousemove', mouseMove);
        removeEvent(document, 'mouseup', mouseUp);
    }
}

/**
 * 封装原生 ajax
 * @Author   hflxhn     <18729081872@163.com>
 * @DateTime 2019-07-27
 * @param    {[type]}   server_url            [请求服务器地址]
 * @param    {Function} callback              [回调函数]
 * @param    {String}   params                [请求参数]
 * @param    {String}   type                  [请求类型]
 * @param    {String}   data_type             [返回数据格式]
 * @return   {[type]}                         [返回数据]
 */
var loadData = function (obj) {
    var server_url = obj.url,
        callback = obj.success,
        formObj = obj.formObj ? obj.formObj : '',
        params = obj.params ? obj.params : '',
        file = obj.file ? obj.file : '',
        type = obj.type ? obj.type : 'post',
        data_type = obj.data_type ? obj.data_type : 'json',
        onprogress = obj.onprogress ? obj.onprogress : '';

    // 创建formdata对象 数据处理
    if (formObj && !file && type != 'get') {
        params = new FormData(formObj);
    }else if(file && type != 'get'){
        params = new FormData();
        params.append('file', file);
    }

    // 1. 创建XMLHttpRequest这个对象, 这个步骤需要注意兼容处理
    var xhr = null;
    if (!window.XMLHttpRequest) {
        // IE6
        xhr = new ActiveXObject('Microsoft.XMLHTTP');
    }
    xhr = new XMLHttpRequest();

    // 2. 文件上传实时进度
    xhr.upload.onprogress = function (event) {
        if(event.lengthComputable){
            var result = event;
            return onprogress && typeof(onprogress) === 'function' && onprogress(result);
        }
    }

    // 3. 准备发送 并且 执行发送
    if (type === 'get') {
        server_url += '?' + params;
        xhr.open(type, server_url, true);
        xhr.send(null);
    }else if (type === 'post') {
        xhr.open(type, server_url, true);
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.send(params);
    }

    // 4. 设置回调函数
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            switch (xhr.status) {
                case 200:
                    var result = xhr.responseText;
                    if (data_type === 'json') {
                        result = JSON.parse(result);
                    }else if (data_type === 'xml') {
                        result = xhr.responseXML;
                    }
                    return callback && typeof(callback) === 'function' && callback(result);
                case 404:
                    return layer.msg('404 no request resources found', {icon: 2});
                case 500:
                    return layer.msg('500 server error !', {icon: 2});
                default :
                    return layer.msg('未知错误', {icon: 2});
            }
        }
    }
}

// 表单验证
var formValidata = {
    // isEmpty
    isEmpty: function(elem, value, errorMsg = '必填项不能为空') {
        if (value === '' || value == null) {
            elem.classList.add('is-invalid');
            elem.focus();
            layer.msg(errorMsg, {icon: 2});
            return true;
        }else{
            elem.classList.remove('is-invalid');
        }
    },

    // isMoblie
    isMoblie: function(elem, value) {
        if (!/^1(3|5|7|8|9)[0-9]{9}$/.test(value)) {
            elem.classList.add('is-invalid');
            elem.focus();
            layer.msg('手机号格式错误', {icon: 2});
            return true;
        }else{
            elem.classList.remove('is-invalid');
        }
    },

    // isEmail
    isEmail: function(elem, value) {
        if (!/^\w+([+-.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/.test(value)) {
            elem.classList.add('is-invalid');
            elem.focus();
            layer.msg('邮箱格式不合法', {icon: 2});
            return true;
        }else{
            elem.classList.remove('is-invalid');
        }
    },

    // 表单验证成功
    successAjax: function(formObj, serverUrl) {
        loadData({
            url : serverUrl,
            success : function(result){
                if (result.code == 0) {
                    layer.msg(result.msg ,{
                        icon: 16,
                        time: 300,
                    },function(){
                        if (result.data === 'close') {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                            window.parent.location.reload();
                        }
                        window.location.href = result.data;
                    });
                }else{
                    layer.msg(result.msg, {
                        icon: 2,
                    });
                }
            },
            formObj : formObj
        });
    }
}
