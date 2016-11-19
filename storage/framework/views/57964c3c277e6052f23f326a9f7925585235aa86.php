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
    <script src="/js/user.js"></script>
    <script src="/js/common.js"></script>
    <script src="/js/question.js"></script>
    <title>Daily</title>
</head>
<body>
<div class="navbar clearfix">
    <?php /*<a href="" ui-sref="page">首页</a> <!-- 这里应该使用state的名称-->*/ ?>
    <div class="container">
        <div class="fl">
            <div class="navbar-item brand" ui-sref="home">知乎</div>
            <form id="quick ask" ng-controller="QuestionAddController" ng-submit="Question.go_add_question()">
                <div class="navbar-item" >
                <input type="text" ng-model="Question.new_question.title">
                </div>
                <div class="navbar-item">
                <button>提问</button>
                </div>
            </form>
        </div>
        <div class="fr">
            <div ui-sref="home" class="navbar-item">首页</div>
            <?php if(is_logged_in()): ?>
                <div ui-sref="login" class="navbar-item"><?php echo e(session('username')); ?></div>
                <a href="<?php echo e(url('/api/user/logout')); ?>" class="navbar-item">退出</a>
            <?php else: ?>
                <div ui-sref="login" class="navbar-item">登录</div>
                <div ui-sref="signup" class="navbar-item">注册</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="page">
    <div ui-view></div>
</div>
</body>
</html>