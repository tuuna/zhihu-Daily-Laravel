;(function() {
    'use strict';
    angular.module('answer',[])
        .service('AnswerService',['$http',function($http) {
            var me = this;
            me.data = {};
            me.count_vote = function (answers) {
                for (var i = 0; i < answers.length;i++) {
                    var votes,item = answers[i];

                    if(!item['question_id']) continue;
                    me.data[item.id] = item;
                    if(!item['users']) continue;
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
                    console.log('需要id和投票数');
                    return;
                }

                var answer = me.data[conf.id],
                    users  = answer.users;
                /*判断当前用户是否已经投过相同的票*/
                for (var i = 0; i < users.length; i++) {
                    if(users[i].id == his.id && conf.vote == users[i].pivot.vote)
                        conf.vote = 3;
                }
                return $http.post('/api/answer/vote',conf)
                    .then(function(r) {
                        if (r.data.status)
                            return true;
                        return false;
                    }, function() {
                        return false;
                    })

            };

            me.update_data = function(id) {
                return $http.post('/api/answer/read', {id:id})
                    .then(function(r) {
                        // console.log('r',r);
                        me.data[id] = r.data.data;
                    }, function(e) {
                        console.log('e',e);
                    })
                /*if(angular.isNumeric(input))
                    var id = input;
                if(angular.isArray(input))
                    var id_set = input;*/


            }

            me.read = function(params) {
                return $http.post('/api/answer/read',params)
                    .then(function(r) {
                        if(r.data.status)
                            me.data = angular.merge({},me.data,r.data.data);
                        return r.data.data;
                    })
            }
        }])


})();