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
    <?php /*<a href="" ui-sref="home">首页</a> <!-- 这里应该使用state的名称-->*/ ?>
    <div class="container">
        <div class="fl">
            <div class="navbar-item brand">知乎</div>
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
<script type="text/ng-template" id="home.tpl">
    <div class="home container">
        这里是主页面
    </div>
</script>

<script type="text/ng-template" id="login.tpl">
    <div ng-controller="LoginController" class="login container">
        <div class="card" >
            <h1>登录</h1>
            <form name="login_form" ng-submit="User.login()">
                <div class="input-group">
                    <label>用户名</label>
                    <input type="text" ng-model="User.login_data.username" name="username" ng-minlength="4" ng-maxlength="16" ng-model-options="{debounce: 500}" required>
                    <div class="input-error-set" ng-if="login_form.username.$touched">
                        <div ng-if="login_form.username.$error.required">用户名不能为空</div>
                        <div ng-if="login_form.username.$error.maxlength>16 || login_form.username.$error.minlength < 4" >用户名长度应该为4-16</div>
                    </div>
                </div>
                <div class="input-group">
                    <label>密码</label>
                    <input type="password" ng-model="User.login_data.password" ng-minlength="6" ng-maxlength="255"  required>
                    <div class="input-error-set" ng-if="login_form.password.$touched">
                        <div ng-if="login_form.password.$error.required ">密码不能为空</div>
                        <div ng-if="login_form.password.$error.maxlength > 255 || login_form.password.$error.minlength < 6">密码长度为6-255</div>
                        <div ng-if = "User.login_failed">用户名或密码不正确</div>
                    </div>
                </div>

                <button type="submit" class="primary" ng-disabled="login_form.$invalid">登录</button>
            </form>
        </div>
    </div>

</script>

<script type="text/ng-template" id="signup.tpl">

    <div class="signup container" ng-controller="RegController">
        <div class="card">
            <h1>注册</h1>
            [: User.signup_data :]
            <form ng-submit="User.signup()" name="signup_form">
                <div class="input-group">
                    <label>用户名</label>
                    <input type="text" ng-model="User.signup_data.username" name="username" ng-minlength="4" ng-maxlength="16" ng-model-options="{debounce: 500}" required>
                    <div class="input-error-set" ng-if="signup_form.username.$touched">
                        <div ng-if="signup_form.username.$error.required">用户名不能为空</div>
                        <div ng-if="signup_form.username.$error.maxlength>16 || signup_form.username.$error.minlength < 4" >用户名长度应该为4-16</div>
                        <div ng-if="User.signup_username_exists">用户名已存在</div>
                    </div>
                </div>
                <div class="input-group">
                    <label>密码</label>
                    <input type="password" ng-model="User.signup_data.password" ng-minlength="6" ng-maxlength="255"  required>
                    <div class="input-error-set" ng-if="signup_form.password.$touched">
                        <div ng-if="signup_form.password.$error.required ">密码不能为空</div>
                        <div ng-if="signup_form.password.$error.maxlength > 255 || signup_form.password.$error.minlength < 6">密码长度为6-255</div>
                    </div>
                </div>
                <button type="submit" ng-disabled="signup_form.$invalid">注册</button>
            </form>
        </div>
    </div>
</script>
<script type="text/ng-template" id="question.add.tpl">
    <div ng-controller="QuestionAddController" class="question_add container">
        <div class="card">
            <form ng-submit="Question.add()" name="question_add_form">
                <div class="input-group">
                    <label>问题标题</label>
                    <input type="text" ng-model="Question.new_question.title" name="title">
                </div>
                <div class="input-group">
                    <label>问题描述</label>
                    <textarea  ng-model="Question.new_question.desc" name="desc"></textarea>
                </div>
                <div class="input-group">
                    <button type="submit" class="primary">提交</button>
                </div>
            </form>
        </div>
    </div>
</script>
</html>