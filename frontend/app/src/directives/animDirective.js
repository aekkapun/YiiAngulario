'use strict';

angular
    .module('app')
    .directive('anim', ['$animate', '$timeout', function($animate, $timeout) {
        return {
            require: '^form',
            scope: {
                submit: '&',
                submitted: '='
            },
            link: function(scope, element, attrs, form) {
                element.on('submit', function() {
                    scope.$apply(function() {
                        if (form.$valid) return scope.submit();
                        $timeout(function(){
                            $animate.removeClass(element, 'shake');
                        }, 1000);
                            $animate.addClass(element, 'shake');
                    });
                });
            }
        };
    }]);
