<div ng-controller="UserController">
    <div class="card">
        <h1>用户详情</h1>
        <div class="hr" ></div>
        <div class="basic">
            <div>用户名</div>
            <div>[: User.self_data.username :]</div>
        </div>
        <h2>用户提问</h2>
        <div ng-repeat="item in User.his_questions">
            [: item.title :]
        </div>
        <h1>用户回答</h1>
        <div ng-repeat="(key,value) in User.his_answers">
            [: value.content :]
        </div>
    </div>

</div>