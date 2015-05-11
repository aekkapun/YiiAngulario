'use strict';

angular
    .module('app')
    .service('AnswerService', function($q, $http, $mdToast, config) {
        var service = this;

        service.checkAnswer = function(question, answer, type) {
            if(question[type.to] === answer){
                return true;
            }
            return false;
        }

        service.postWrongAnswer = function(answers) {
            return $http.post(config.backend_url+'/answer/wrong', { "AnswersWrong" : { answers: answers } });
        }
        
        service.finish = function(answersWrong, points, max_points) {
            if(answersWrong.length > 0){
                service.postWrongAnswer(answersWrong).then(function(response) {
                }, function(responseError){
                    $mdToast.show($mdToast.simple().content(responseError.status+': '+responseError.statusText).position('top right'));
                });
            }
            return $http.post(config.backend_url+'/results', { "Result" : { points: points, max_points: max_points } });
        }

        service.createWrongAnswer = function(question, answer, type, stack) {
            var deferred;
            deferred = $q.defer();

            var stack_answers = stack.concat([{id: question.id, answer: answer, type: type.from+'.'+type.to}]);
            
            if(angular.isNumber(config.stack_answers) && stack_answers.length >= config.stack_answers){
                service.postWrongAnswer(stack_answers)
                    .then(function(response) {
                        deferred.resolve([]); 
                    }, function(responseError) {
                        deferred.reject([stack_answers]);
                    });
            } else{
                deferred.resolve(stack_answers); 
            }

            return deferred.promise;
        }
    });