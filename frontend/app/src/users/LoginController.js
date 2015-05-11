'use strict';

angular
    .module('app')
    .controller('LoginController', function($scope, $rootScope, $location, $mdToast, AuthService, UserService) {
        var vm = this,
            currentUser = UserService.getCurrentUser(),
            access_token = currentUser ? currentUser.access_token : null;

        vm.login = login;
        vm.logOut = logOut;

        $scope.logout = logOut;

        function login() {
            vm.dataLoading = true;

            AuthService.login(vm.email)
                .then(function(response){
                    var user = {};
                    user.access_token = response.data.data.response.access_token;
                    user.user_name = response.data.data.response.username;
                    user.email = response.data.data.response.email;
                    user.avatar = response.data.data.response.avatar;
                    UserService.setCurrentUser(user);
                    $rootScope.$broadcast('authorized');
                    vm.dataLoading = false;
            }, function(response){
                    if(angular.isUndefined(response.data) === false && angular.isObject(response.data) === true){
                        if(angular.isUndefined(response.data.data) === false && angular.isObject(response.data) === true && angular.isArray(response.data.data.response)){
                            $mdToast.show($mdToast.simple().content(response.status+': '+response.data.data.response[0].message).position('top right'));
                        } else{
                            $mdToast.show($mdToast.simple().content(response.status+': '+response.statusText).position('top right'));
                        }
                    } else {
                        $mdToast.show($mdToast.simple().content(response.status+': '+response.statusText).position('top right'));
                    }
                    vm.dataLoading = false;
            });
        }

        function logOut() {
            $rootScope.$broadcast('unauthorized');
        }
    });