'use strict';

angular
    .module('app')
    .service('QuestionService', function($q, $http, AnswerService, config) {
        var service = this;
        
        service.getTypeTranslate = function(type) {
           var type_arr = type.split('.');
           return {from: type_arr[0], to: type_arr[1]}
        }

        service.getNextQuestion = function(question) {
             return $http.get(config.backend_url+'/questions', { params : { page : question } });
        }
    });