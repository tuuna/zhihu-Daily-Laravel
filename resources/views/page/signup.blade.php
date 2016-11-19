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