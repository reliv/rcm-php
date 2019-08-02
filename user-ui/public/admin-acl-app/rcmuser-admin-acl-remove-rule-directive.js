angular.module('rcmuserAdminAclApp').directive(
    'rcmuserAdminAclRemoveRuleDirective',
    [
        'rcmUserEventManager',
        'rcmUserAclRuleService',
        'rcmUserAclRoleService',
        'rcmUserSelectedDataService',
        function (
            rcmUserEventManager,
            rcmUserAclRuleService,
            rcmUserAclRoleService,
            rcmUserSelectedDataService
        ) {
            var thisLink = function (scope, element, attrs) {
                var roleServiceEventManager = rcmUserAclRoleService.getEventManager();
                var ruleServiceEventManager = rcmUserAclRuleService.getEventManager();
                var selectedDataServiceEventManager = rcmUserSelectedDataService.getEventManager();

                scope.loading = false;

                roleServiceEventManager.on(
                    'RcmUserHttp.loading',
                    function (loading) {
                        scope.loading = loading;
                    }
                );

                var setRuleData = function (ruleData) {
                    scope.ruleData = ruleData;
                };

                var setResourceData = function (resourceData) {
                    scope.resourceData = resourceData;
                };

                selectedDataServiceEventManager.on(
                    'rcmUserSelectedDataServiceChange.removeRuleRuleData',
                    setRuleData
                );

                selectedDataServiceEventManager.on(
                    'rcmUserSelectedDataServiceChange.removeRuleResourceData',
                    setResourceData
                );

                scope.cancel = function () {
                    element.find('#removeRule').modal('hide');
                };

                scope.close = function () {
                    element.find('#removeRule').modal('hide');
                };

                var onError = function (data, status) {
                    $window.alert('An error occurred');
                    console.error(data);
                };

                var onGetRolesSuccess = function (data, status) {
                    scope.close();
                };

                var onRemoveRuleSuccess = function (data, status) {
                    rcmUserAclRoleService.getRulesByRoles(
                        onGetRolesSuccess,
                        onError
                    );
                };

                scope.removeRule = function () {
                    rcmUserAclRuleService.removeRule(
                        scope.ruleData,
                        onRemoveRuleSuccess
                    );
                };
            };
            return {
                link: thisLink,
                scope: {},
                templateUrl: '/modules/rcm-user/admin-acl-app/rcmuser-admin-acl-remove-rule-directive.html'
            };
        }
    ]
);
