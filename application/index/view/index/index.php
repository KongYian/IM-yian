<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no,  initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>Lay chat</title>

    <link rel="stylesheet" href="/static/css/common.css">
    <link rel="stylesheet" href="/static/dist/css/layui.mobile.css">
    <link href="https://cdn.bootcss.com/layer/3.0.3/mobile/need/layer.min.css" rel="stylesheet">

</head>
<body>
<script src="/static/dist/layui.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/layer/3.0.3/mobile/layer.js"></script>
<script src="/static/js/common.js"></script>
<script>
    layui.config({
        version: true
    }).use('mobile', function(){
        var wsServer = 'ws://47.94.11.157:8081';
        var websocket = new WebSocket(wsServer);
        var mobile = layui.mobile
            ,layim = mobile.layim
            ,layer = mobile.layer;

        websocket.onopen = function () {
            var data = {};
            data.id = "<?php echo $mine['id'];?>";
            sendWhenOpen(websocket,data);
            console.log('handle success');
        };
        websocket.onmessage = function (evt) {
            data = evt.data;
            var dataArr =JSON.parse(data);
            var type = dataArr['type'];
            var data = dataArr['data'];
            switch (type){
                case 'open':{

                }
                case 'close':{

                }
                case 'msg':{
                    console.log(data);
                    layim.getMessage(data);
                    break;
                }
                case 'system_inform':{

                }
                case 'friend_online':{
                    layim.setFriendStatus(data.id, 'online');
                    break;
                }
                case 'friend_offline':{
                    layim.setFriendStatus(data.id, 'offline');
                    break;
                }
                default:
                    console.log('unknown type')
            }
        };



        layim.config({
            //上传图片接口
            uploadImage: {
                url: '/index/index/uploadImage' //（返回的数据格式见下文）
                ,type: '' //默认post
            }
            //上传文件接口
            ,uploadFile: {
                url: '/upload/file' //（返回的数据格式见下文）
                ,type: '' //默认post
            }
            //,brief: true
            ,init: {
                mine: {
                    "username": "<?php echo $mine['nickname'];?>" //我的昵称
                    ,"id": "<?php echo $mine['id'];?>" //我的ID
                    ,"avatar":  "<?php echo $mine['avatar'];?>"//我的头像
                    ,"sign": "<?php echo $mine['sign'];?>"
                    }
        //我的好友列表
                 ,friend: [{
                     "groupname": "好友列表"
                    ,"id": 1
                    ,"list": <?php echo json_encode($friendsList);?>
                }]
            }
            //扩展聊天面板工具栏
            ,tool: [{
                alias: 'code'
                ,title: '代码'
                ,iconUnicode: '&#xe64e;'
            }]

            //扩展更多列表
            ,moreList: [{
                alias: 'find'
                ,title: '发现'
                ,iconUnicode: '&#xe628;' //图标字体的unicode，可不填
                ,iconClass: '' //图标字体的class类名
            },{
                alias: 'share'
                ,title: '分享与邀请'
                ,iconUnicode: '&#xe641;' //图标字体的unicode，可不填
                ,iconClass: '' //图标字体的class类名
            },{
                alias: 'blog'
                ,title: '我的博客'
                ,iconUnicode: '&#xe62b;' //图标字体的unicode，可不填
                ,iconClass: '' //图标字体的class类名
            },{
                alias: 'marathon'
                ,title: '马拉松'
                ,iconUnicode: '&#xe600;' //图标字体的unicode，可不填
                ,iconClass: '' //图标字体的class类名
            }]

            ,tabIndex: 1 //用户设定初始打开的Tab项下标
            //,isNewFriend: false //是否开启“新的朋友”
            ,isgroup: false //是否开启“群聊”
            //,chatTitleColor: '#c00' //顶部Bar颜色
            ,title: 'LayChat' //应用名，默认：我的IM
        });


        //监听点击“新的朋友”
//        layim.on('newFriend', function(){
//            layim.panel({
//                title: '新的朋友' //标题
//                ,tpl: '<div style="padding: 10px;">自定义模版，{{d.data.test}}</div>' //模版
//                ,data: { //数据
//                    test: '么么哒'
//                }
//            });
//        });

        //查看聊天信息
//        layim.on('detail', function(data){
//            //console.log(data); //获取当前会话对象
//            layim.panel({
//                title: data.name + ' 聊天信息' //标题
//                ,tpl: '<div style="padding: 10px;">自定义模版，<a href="http://www.layui.com/doc/modules/layim_mobile.html#ondetail" target="_blank">参考文档</a></div>' //模版
//                ,data: { //数据
//                    test: '么么哒'
//                }
//            });
//        });

        //监听点击更多列表
        layim.on('moreList', function(obj){
            switch(obj.alias){
                case 'find':
                    layer.msg('Hello World');

                    layim.showNew('More', false);
                    layim.showNew('find', false);
                    break;
                case 'share':
                    layer.msg('Fuck World');
                    return false;
                    layim.panel({
                        title: '邀请好友' //标题
                        ,tpl: '<div style="padding: 10px;">自定义模版，{{d.data.test}}</div>' //模版
                        ,data: { //数据
                            test: '么么哒'
                        }
                    });
                    break;
                case 'blog':
                    layim.panel({
                        title: 'my blog'
                        ,tpl: '<div><iframe src="http://blog.blueyian.top" style="height:800px; width:100%; frameborder:0"></div>'
                        ,data: { //数据
                        }
                    });
                    break;
                case 'marathon':
                    layim.panel({
                        title: 'marathon'
                        ,tpl: '<div><iframe src="http://demo.blueyian.top/marathon/index.php" style="height:800px; width:100%; frameborder:0"></div>'
                        ,data: { //数据
                        }
                    });
                    break;
            }
        });

        //监听返回
        layim.on('back', function(){
            //如果你只是弹出一个会话界面（不显示主面板），那么可通过监听返回，跳转到上一页面，如：history.back();
        });

        //监听发送消息
        layim.on('sendMessage', function(data){
            sendPrivateChat(websocket,data);
        });

        //监听自定义工具栏点击，以添加代码为例
        layim.on('tool(code)', function(insert, send){
            insert('[pre class=layui-code]123[/pre]'); //将内容插入到编辑器
            send();
        });


        //监听查看更多记录
//        layim.on('chatlog', function(data, ul){
//            console.log(data);
//            layim.panel({
//                title: '与 '+ data.name +' 的聊天记录' //标题
//                ,tpl: '<div style="padding: 10px;">这里是模版，{{d.data.test}}</div>' //模版
//                ,data: { //数据
//                    test: 'Hello'
//                }
//            });
//        });

        //模拟"更多"有新动态
        layim.showNew('More', true);
        layim.showNew('find', true);

    });

    function sendPrivateChat(ws,data) {
        data.type = 'msg';
        ws.send(JSON.stringify(data));
    }

    function sendWhenOpen(ws,data) {
        data.type = 'open';
        ws.send(JSON.stringify(data));
    }


</script>
</body>
</html>
