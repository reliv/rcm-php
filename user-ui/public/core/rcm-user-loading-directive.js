/**
 * rcmuserCore.rcmAlerts
 */
angular.module('rcmuserCore').directive(
    'rcmUserLoadingDirective',
    function () {
        
        return {
            scope: {
                'loading': '=rcmUserLoadingDirective'
            },
            template: '' +
            '<div class="rcm-user loading" ng-show="loading">' +
            '   <i class="glyphicon glyphicon-refresh loading-icon"></i>' +
            '   <span class="loading-message"> loading...</span>' +
            '</div>'
        };
    }
);
