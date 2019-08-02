/**
 * rcmuserCore.rcmAlerts
 */
angular.module('rcmuserCore').directive(
    'rcmAlerts', 
    function () {
        /*
         * Example:
         * <div rcm-alerts rcm-results="alerts" alert-title="'An error occured:'"></div>
         */
        var thislink = function (scope, element, attrs) {

            var self = this;

            scope.closeAlert = function (index) {
                scope.rcmResults.clear();
            };

            scope.type = {
                0: 'warning',
                1: 'info'
            };

            scope.title = {
                0: scope.alertTitleError,
                1: scope.alertTitleSuccess
            }

        };

        return {
            link: thislink,
            restrict: 'A',
            replace: true,
            scope: {
                'rcmResults': '=',
                'alertTitleError': '=',
                'alertTitleSuccess': '='
            },
            template: '' +
            '<alert class="alert alert-{{type[alert.code]}}" ng-repeat="alert in rcmResults.results track by $index" type="type[alert.code]" close="closeAlert($index)">' +
            '    <div class="alert-header">' +
            '        <i class="glyphicon glyphicon-{{type[alert.code]}}-sign"></i>' +
            '        <span class="alert-title"><strong>{{title[alert.code]}}</strong></span>' +
            '    </div>' +
            '    <div class="alert-messages">' +
            '        <ul>' +
            '            <li ng-repeat="msg in alert.messages">{{msg}}</li>' +
            '        </ul>' +
            '    </div>' +
            '</alert>'
        };
    }
);
