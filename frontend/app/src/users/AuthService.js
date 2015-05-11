'use strict';

angular
    .module('app')
    .service('AuthService', function($http, UserService, config) {
        var service = this;
            service.config = config;

            service.login = function(email) {
                return $http.post(config.backend_url+'/user/login', {"LoginForm": {"email": email}});
            };

    });