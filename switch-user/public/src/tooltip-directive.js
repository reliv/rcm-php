/**
 * rcmSwitchUserSwitchToUser
 */
angular.module('rcmSwitchUser').directive(
    'rcmSwitchUserTooltip',
    function () {
        return {
            scope: {
                propInfoContent: '=content',
                propShow: '=show',
            },
            template: '<%= inlineTemplate("src/tooltip-directive.html") %>'
        }
    }
);
