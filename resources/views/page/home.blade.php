 <div class="home card container" ng-controller="HomeController">
        <h1>最近动态</h1>
        <div class="hr"></div>
        <div class="item-set">
            <div ng-repeat="item in Timeline.data" class="feed item clearfix">
                <div ng-if="item.question_id" class="vote">
                    <div class="up">[: item.upvote_count :]</div>
                    <div class="down">踩</div>
                </div>
                <div class="feed-item-content">
                    <div ng-if="item.question_id" class="content-act">[: item.user.username :]添加了回答</div>
                    <div ng-if="!item.question_id" class="content-act">[: item.user.username :]回答了问题</div>
                    <div class="title">[: item.title :]</div>
                    <div class="content-owner">[: item.user.username :]
                        <span class="desc">this is a great author</span>
                    </div>
                    <div class="content-main">[: item.desc :]</div>
                    <div class="action-set">
                        <div class="comment">评论</div>
                    </div>
                    <div class="comment-block">
                        <div class="comment-item-set clearfix">
                            <div class="rect"></div>
                            <div class="comment-item">
                                <div class="user">狗蛋</div>
                                <div class="comment-content">哈哈哈哈</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hr"></div>
            </div>
            <div ng-if="Timeline.pending" class="tac">loading...</div>
            <div class="tac" ng-if="Timeline.no_more_data">没有更多数据了</div>
        </div>

    </div>