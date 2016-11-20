;(function() {
    'use strict';
    angular.module('common', [])
        .service('TimelineService' ,['$http' , 'AnswerService',function($http,AnswerService) {
            var me = this;
            me.data = [];
            me.current_page = 1;
            me.vote = function(conf) {
                AnswerService.vote(conf)
                    .then(function(r) {
                        if(r)
                            AnswerService.update_data(conf.id);
                    })
            };
            me.get = function(conf) {
                if (me.pending) return;
                me.pending = true;

                conf = conf || {page: me.current_page};
                $http.post('/api/timeline', conf)
                    .then(function(r) {
                        if (r.data.status) {
                            if (r.data.data.length) {
                                // me.data = r.data.data;
                                me.data = me.data.concat(r.data.data);
                                me.data = AnswerService.count_vote(me.data);
                                me.current_page++;
                            } else {
                                me.no_more_data = true;
                            }

                        } else {
                            console.error('加载失败，请检查您的网络情况');
                        }
                    },function(e) {
                        console.log('e',e);
                    })
                    //相当于jQuery中的always
                    .finally(function() {
                        me.pending = false;
                    })
            }
        }])

            .controller('HomeController',['$scope','TimelineService','AnswerService',
                function($scope,TimelineService,AnswerService) {
                    var $window;
                    $scope.Timeline = TimelineService;
                    TimelineService.get();

                    $window = $(window);

                    $window.on('scroll', function() {
                        if($window.scrollTop() - ($(document).height() - $window.height()) > -30) {
                            TimelineService.get();
                        }
                    })

                    $scope.$watch(function() {
                        return AnswerService.data;
                    },function(new_data,old_data) {
                        var timeline_data = TimelineService.data;
                        for(var k in new_data){
                            for(var i = 0; i < timeline_data.length;i++ ){
                                if( k == timeline_data[i].id) {
                                    timeline_data[i] = new_data[k];
                                }
                            }
                        }

                        TimelineService.data = AnswerService.count_vote(TimelineService.data);
                    },true)
                }])
})();
