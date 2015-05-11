'use strict';

angular
    .module('app')
    .controller('TestController', function($scope, $rootScope, $location, $route, $window, $mdToast, UserService, AnswerService, QuestionService, config) {
        var vm = this;

        (function initController() {
            $scope.points = 0;
            $scope.wrongAnswers = 0;
            $scope.answerPerQuest = 0;
            $scope.countQuestions = 0;
            $scope.totalQuestion = 0;
            $scope.stack_wrong_answers = [];
            $scope.beginTest = true;
            $scope.check = check;
            $scope.determinateValue = 0;

            QuestionService.getNextQuestion(1)
                .then(function(response) {
                    setCredentials(response);
                }, function(responseError) {
                    $mdToast.show($mdToast.simple().content(responseError.status + ': ' + responseError.statusText).position('top right'));
                });
        })();

        $scope.runTest = function() {
            $route.reload();
        };

        function check() {
            if ($scope.form.$valid) {
                if (AnswerService.checkAnswer(vm.data.items[0], $scope.vm.radio, {
                        from: vm.from,
                        to: vm.to
                    })) {
                    $scope.vm.radio = false;

                    if ($scope.answerPerQuest === 0) {
                        $scope.points++;
                    }

                    $scope.answerPerQuest = 0;

                    $scope.totalQuestion++;

                    if ($scope.totalQuestion > $scope.countQuestions) {
                        $rootScope.$broadcast('finalTest');
                        return;
                    }

                    QuestionService.getNextQuestion($scope.totalQuestion)
                        .then(function(response) {
                            setCredentials(response);
                        }, function(responseError) {
                            $mdToast.show($mdToast.simple().content(responseError.status + ': ' + responseError.statusText).position('top right'));
                        });


                } else {
                    AnswerService.createWrongAnswer(vm.data.items[0], $scope.vm.radio, {
                            from: vm.from,
                            to: vm.to
                        }, $scope.stack_wrong_answers)
                        .then(function(response) {
                            // console.log('Create wrong answer');
                            $scope.stack_wrong_answers = response;
                            checkWrongAnswer();
                        }, function(responseError) {
                            // console.log('Create wrong answer');
                            $scope.stack_wrong_answers = responseError;
                            checkWrongAnswer();
                        });
                }
            } else {
                $mdToast.show($mdToast.simple().content('Please answer the question.').position('top right'));
            }
        }

        function checkWrongAnswer(){
            $scope.answerPerQuest++;

            if ($scope.answerPerQuest >= config.max_wrong_answers_per_question) {
                $rootScope.$broadcast('finalTest');
                return;
            }

            $scope.wrongAnswers++;

            var step = parseInt(100 / config.max_wrong_answers);
            $scope.determinateValue = step * $scope.wrongAnswers;

            if (config.max_wrong_answers == $scope.wrongAnswers) {
                $rootScope.$broadcast('finalTest');
                return;
            }

            $scope.answerIsWrong = true;
            $mdToast.show($mdToast.simple().content('Wrong answer. Try again.').position('top right'));
            $scope.form.$valid = false;
        }

        function setCredentials(response) {
            vm.data = response.data.data.response;
            var type = QuestionService.getTypeTranslate(vm.data.items[0].type);
            
            vm.from = type.from;
            vm.to = type.to;

            $scope.countQuestions = vm.data._meta.totalCount;
            $scope.totalQuestion = vm.data._meta.currentPage
            $scope.answers = vm.data.items[0].variants;
            $scope.question = vm.data.items[0][vm.from];
        }

        $scope.$on('finalTest', function() {
            var currentUser = UserService.getCurrentUser();
            $scope.determinateValue = 100;
            $scope.beginTest = false;
            $scope.username = currentUser.user_name;
            $scope.avatar = currentUser.avatar;
            $scope.email = currentUser.email;

            AnswerService.finish($scope.stack_wrong_answers, $scope.points, $scope.countQuestions)
                .then(function(responseAnswer) {
                    // console.info("It's so good!");
                }, function(responseError) {
                    $mdToast.show($mdToast.simple().content(responseError.status + ': ' + responseError.statusText).position('top right'));
                });
        });


    });
