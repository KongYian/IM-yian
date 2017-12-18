<html>
<head>
    <meta charset="UTF-8">
    <meta name="mobile-web-app-capable" content="yes">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, height=device-height, user-scalable=no,  initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="renderer" content="webkit">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="msapplication-TileColor" content="#0e90d2">
    <title>Login</title>
    <link rel="stylesheet" href="/static/css/login.css">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/layer/3.0.3/mobile/layer.js"></script>
</head>
<body>
    <div class="container">
        <div class="container title">第三方账号登录</div>
        <div class="container login-icon">
            <img src="/static/images/social-qq.png" alt="" data-type="qq" onclick="login($(this))">
            <img src="/static/images/social-baidu.png" alt="" data-type="baidu" onclick="login($(this))">
            <img src="/static/images/social-github-outline.png" alt="" data-type="github" onclick="login($(this))">
        </div>
    </div>
</body>
</html>
<script>
    $(function () {
        if(isMobile()===false){
            $('body').css('background',"#5C6D81");
        }

        function isMobile(){
            var ua = navigator.userAgent;
            var ipad = ua.match(/(iPad).*OS\s([\d_]+)/),
                isIphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/),
                isAndroid = ua.match(/(Android)\s+([\d.]+)/),
                isMobile = isIphone || isAndroid;
            if(isMobile) {
                return true;
            }else {
                return false;
            }
        }
    })
    function login(e) {
        var type = e.data('type');
        window.location.href = '/index/login/login/?type='+type;
    }

</script>