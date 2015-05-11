'use strict';

var app = angular.module('app', ['ngMaterial', 'ngRoute', 'ngCookies', 'ngResource', 'ngMessages', 'ngAnimate', 'angular-storage']);

app.constant('config', {
  backend_url: 'http://localhost:8080/v1',
  max_wrong_answers: 3,
  max_wrong_answers_per_question: 2,
  stack_answers: false
});

app.config(config, ['$httpProvider', '$routeProvider', '$locationProvider', '$mdThemingProvider', '$mdIconProvider', 'app.config']);

app.service('RestInterceptor', function($q, $rootScope, UserService, config) {
    
    var service = this;
    app.config = config;

    service.request = function(config) { 
        
        var currentUser = UserService.getCurrentUser(),
            access_token = currentUser ? currentUser.access_token : null;

        if (access_token) {
            if(angular.isObject(config.params)){
                angular.extend(config.params, {"access-token": access_token});
            }

            if(angular.isObject(config.data)){
                config.params = {"access-token": access_token};
            }
        }

        return config;
    };
    
    service.response = function(response) {
        var status = response.status,
            message = response.statusText;
        
        return response;
    }

    service.responseError = function(response) {
        var status = response.status,
            message = response.statusText;

        if (status === 401) {
            $rootScope.$broadcast('unauthorized');
        }

        return $q.reject(response);
    }
});

app.run(run, ['$rootScope', '$location', '$cookieStore', '$http']);

function config($httpProvider, $routeProvider, $locationProvider, $mdThemingProvider, $mdIconProvider) {
    $routeProvider
        .when('/', {
            controller: 'AppController',
            templateUrl: 'src/main/views/main.view.html',
            controllerAs: 'vm'
        })
        .when('/login', {
            controller: 'LoginController',
            templateUrl: 'src/users/views/login.view.html',
            controllerAs: 'vm'
        })
        .when('/test', {
            controller: 'TestController',
            templateUrl: 'src/tests/views/test.view.html',
            controllerAs: 'vm'
        })
        .otherwise({
            redirectTo: '/'
        });


    $mdIconProvider
        .defaultIconSet("./assets/svg/avatars.svg", 128)
        .icon("menu", "./assets/svg/menu.svg", 24);

    $mdThemingProvider.theme('default')
        .primaryPalette('indigo')
        .accentPalette('red');

    $httpProvider.interceptors.push('RestInterceptor');

    $httpProvider.defaults.useXDomain = true;
    delete $httpProvider.defaults.headers.common['X-Requested-With'];
}

function run($rootScope, $location, $cookieStore, $http, UserService) {
    if(UserService.isGuest()){
        $rootScope.logOutButtonShow = false;
    } else{
        $rootScope.logOutButtonShow = true;
    }
    
    $rootScope.$on('$locationChangeStart', function (event, next, current) {
        var restrictedPage = $.inArray($location.path(), ['/login']) === -1;

        if(restrictedPage && UserService.isGuest()){
            $rootScope.$broadcast('unauthorized');
        }
    });

    $rootScope.$on('unauthorized', function() {
        UserService.clearCurrentUser();
        $location.path('/login');
        $rootScope.logOutButtonShow = false;
    });

    $rootScope.$on('authorized', function() {
        $location.path('/');
        $rootScope.logOutButtonShow = true;
    });
}