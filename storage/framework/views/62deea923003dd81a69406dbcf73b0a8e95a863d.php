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