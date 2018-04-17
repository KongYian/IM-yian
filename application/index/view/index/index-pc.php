<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no,  initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <title>Lay chat</title>

    <link rel="stylesheet" href="/static/css/common.css">
    <link rel="stylesheet" href="/static/css/layui.css">

</head>
<body>

</body>
<script src="/static/dist/layui.js"></script>
<script src="/static/js/common.js"></script>
<script>
    layui.config({

    }).use('layim', function(layim){

        var wsServer = 'ws://47.94.11.157:8081';
        var websocket = new WebSocket(wsServer);

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
//            brief: true,
            notice:true,
            min:true,
            title:'Blue-IM',
            init: {
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
            //上传图片接口
            ,uploadImage: {
                url: '/index/index/uploadImage' //（返回的数据格式见下文）
                ,type: '' //默认post
            }
            //上传文件接口
            ,uploadFile: {
                url: '/upload/file' //（返回的数据格式见下文）
                ,type: '' //默认post
            }
            //扩展聊天面板工具栏
            ,tool: [{
                alias: 'code'
                ,title: '代码'
                ,icon: '&#xe64e;'
            }]
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
</html>
