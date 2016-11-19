;(function() {
    'use strict';
    angular.module('answer',[])
        .service('AnswerService',['$http',function($http) {
            var me = this;
            me.count_vote = function (answers) {
                for (var i = 0; i < answers.length;i++) {
                    var votes,item = answers[i];

                    if(!item['question_id'] || !item['users']) continue;
                    item.upvote_count = 0;
                    item.downvote_count = 0;

                    votes = item['users'];
                    for(var j = 0; j<votes.length;j++) {
                        var v = votes[j];
                        if(v['pivot'].vote === 1)
                            item.upvote_count++;
                        if(v['pivot'].vote === 2)
                            item.downvote_count++;
                    }
                }
                return answers;
            };

            me.vote = function (conf) {
                if(!conf.id || !conf.vote) {
                    console.log('需要id和投票数')
                    return;
                }

                return $http.post('/api/answer/vote',conf)
                    .then(function(r) {
                        if (r.data.status)
                            return true;
                        return false;
                    }, function() {
                        return false;
                    })

            }

            me.update_data = function(input) {
                if(angular.isNumeric(input))
                    var id = input;
                if(angular.isArray(input))
                    var id_set = input;
            }
        }])


})();