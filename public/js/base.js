;(function() {
    'use strict';
    angular.module('zhihu',['ui.router','common','user','question'])
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
                        templateUrl : 'tpl/page/home'
                        // templateUrl: 'path/to/path' 从服务器上动态获取内容
                    })
                    .state('login',{
                        url : '/login',
                        // template : '登录页面'
                        templateUrl : 'tpl/page/login'
                    })
                    .state('signup',{
                        url : '/signup',
                        templateUrl : 'tpl/page/signup'
                    })
                    .state('question',{
                        abstract : true,
                        url : '/question',
                        template : '<div ui-view></div>'
                    })
                    .state('question.add',{
                        url : '/add',
                        templateUrl : 'tpl/page/question_add'
                    })
                }])
})();