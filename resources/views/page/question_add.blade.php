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