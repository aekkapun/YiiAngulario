'use strict';

angular
    .module('app')
    .controller('AppController', function($scope, $rootScope, $location, UserService) {
        var currentUser = UserService.getCurrentUser();

        $scope.username = currentUser.user_name;
        $scope.avatar = currentUser.avatar;
        $scope.email = currentUser.email;
        $scope.beginTest = beginTest;
        
        function beginTest() {
            $location.path('/test');
        }

    });