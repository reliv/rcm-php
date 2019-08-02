/**
 * rcmuserCore.rcmUserSafeApply
 */
angular.module('rcmuserCore').factory(
    'rcmUserSafeApply', function () {
        return function(scope, fn) {
            var phase = scope.$root.$$phase;
            if(phase == '$apply' || phase == '$digest') {
                if(fn && (typeof(fn) === 'function')) {
                    fn();
                }
            } else {
                scope.$apply(fn);
            }
        };
    }
);
