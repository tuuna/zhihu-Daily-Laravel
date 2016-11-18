;(function() {
    'use strict';

    angular.module('zhihu',['ui.router'])
        .config([
            '$interpolateProvider',
            '$stateProvider',
            '$urlRouterProvider',
            function ($interpolateProvider,$stateProvider,$urlRouterProvider) {
                $interpolateProvider.startSymbol('[:');
                $interpolateProvider.endSymbol(':]');

                $urlRouterProvider.otherwise('/home');
                $stateProvider
                    .state('home',{
                        url : '/home',
                        // template : '首页'
                        templateUrl : 'home.tpl'
                        // templateUrl: 'path/to/path' 从服务器上动态获取内容
                    })
                    .state('login',{
                        url : '/login',
                        // template : '登录页面'
                        templateUrl : 'login.tpl'
                    })
                    .state('signup',{
                        url : '/signup',
                        templateUrl : 'signup.tpl'
                    })
                    .state('question',{
                        abstract : true,
                        url : '/question',
                        template : '<div ui-view></div>'
                    })
                    .state('question.add',{
                        url : '/add',
                        templateUrl : 'question.add.tpl'
                    })


                }])

            .service('UserService', [
                '$state',
                '$http',
                function($state,$http){
                    var me = this;
                    me.signup_data = {};
                    me.login_data = {};
                    me.signup = function() {
                        $http.post('/api/user/signup',me.signup_data)
                            .then(function (r) {
                                if (r.data.status) {
                                    me.signup_data = {};
                                    $state.go('login'); //路由名称
                                }
                            },function(e) {
                                console.log('e',e);
                            })
                    };
                    me.username_exists = function () {
                        $http.post('/api/user/exist',{
                            'username' : me.signup_data.username
                        })
                            .then(function (r) {
                                if(r.data.status && r.data.data.count)
                                    me.signup_username_exists = true;
                                else
                                    me.signup_username_exists = false;
                                console.log('r',r);
                            }, function(e) {
                                console.log('e',e);
                            })
                    };
                    me.login = function() {
                        $http.post('/api/user/login',me.login_data)
                            .then(function (r) {
                                // console.log('r',r);
                                if(r.data.status) {
                                    // me.login_data = {};
                                    $state.go('home');
                                    //location.href = '/';
                                } else {
                                    me.login_failed = true;
                                }
                            },function(e) {
                                console.log('e',e);
                            })
                    };

            }])

            .controller('RegController',[ '$scope','UserService',
                function ($scope,UserService) {
                    $scope.User = UserService;
                    /**
                     * 这里的监控一般传入三个参数，第一个参数是要监控的数据，第二个参数是如果变化则发生什么
                     * 第三个参数让监控递归的去检查每一层,$watch对于debug也很有帮助
                     */
                    $scope.$watch(function () {
                        return UserService.signup_data;
                    }, function (n,o) {
                        if(n.username != o.username)
                            UserService.username_exists();
                    },true)
            }])

        .controller('LoginController',['$scope','UserService',
            function ($scope,UserService) {
                $scope.User = UserService;
        }])

        .service('QuestionService',['$http','$state', function($http,$state) {
            var me = this;
            me.new_question = {};
            me.go_add_question = function() {
                $state.go('question.add');
            };
            me.add = function() {
                if(!me.new_question.title)
                    return;

                $http.post('/api/question/add',me.new_question)
                    .then(function(r) {
                        if(r.data.status) {
                            me.new_question = {};
                            $state.go('home');
                        }
                    },function(e) {
                        console.log('e',e);
                    })
            }
        }])

        .controller('QuestionAddController',['$scope','QuestionService',
            function ($scope,QuestionService) {
                $scope.Question = QuestionService;
        }])
})();