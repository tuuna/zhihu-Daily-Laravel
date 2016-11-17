<html lang="zh" ng-app="zhihu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <link rel="stylesheet" href="/css/base.css">
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/js/base.js"></script>
    <title>Daily</title>
</head>
<body>
<div class="navbar clearfix">
    {{--<a href="" ui-sref="home">首页</a> <!-- 这里应该使用state的名称-->--}}
    <div class="container">
        <div class="fl">
            <div class="navbar-item brand">知乎</div>
            <div class="navbar-item">
                <input type="text">
            </div>
        </div>
        <div class="fr">
            <div ui-sref="home" class="navbar-item">首页</div>
            <div ui-sref="login" class="navbar-item">登录</div>
            <div ui-sref="register" class="navbar-item">注册</div>
        </div>
    </div>
</div>
<div class="page">
    <div ui-view></div>
</div>
</body>
<script type="text/ng-template" id="home.tpl">
    <div class="home container">
        fjsldkjflkdsjlfj
    </div>
</script>

<script type="text/ng-template" id="login.tpl">
    <span>这是登录页面</span>

</script>
</html>