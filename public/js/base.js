;(function() {
    'use strict';

    angular.module('zhihu',['ui.router'])
        .config([
            '$interpolateProvider',
            '$stateProvider',
            '$urlRouterProvider'
        ],function ($interpolateProvider,$stateProvider,$urlRouterProvider) {
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

                .service('UserService', [
                    function(){
                       var me = this;
                        me.signup = function() {
                            console.log('signup');
                        }
                }])
    })
})();