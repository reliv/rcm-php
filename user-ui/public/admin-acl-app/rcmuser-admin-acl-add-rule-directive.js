angular.module('rcmuserAdminAclApp').directive(
    'rcmuserAdminAclAddRuleDirective',
    [
        '$window',
        'rcmUserAclRuleService',
        'rcmUserAclRoleService',
        'rcmUserSelectedDataService',
        function (
            $window,
            rcmUserAclRuleService,
            rcmUserAclRoleService,
            rcmUserSelectedDataService
        ) {
            var thisLink = function (scope, element, attrs) {

                var ruleServiceEventManager = rcmUserAclRuleService.getEventManager();
                var roleServiceEventManager = rcmUserAclRoleService.getEventManager();
                var selectedDataServiceEventManager = rcmUserSelectedDataService.getEventManager();

                scope.loading = false;

                roleServiceEventManager.on(
                    'RcmUserHttp.loading',
                    function (loading) {
                        scope.loading = loading;
                    }
                );

                scope.status = {
                    isopen: false
                };

                scope.toggleDropdown = function ($event, isopen) {
                    $event.preventDefault();
                    $event.stopPropagation();
                    scope.status.isopen = isopen;
                };

                var setRuleData = function (roledata) {
                    scope.roleData = roledata;

                    var roleId = null;
                    if (scope.roleData) {
                        roleId = scope.roleData.role.roleId;
                    }

                    scope.ruleData = {
                        rule: 'allow',
                        roleId: roleId,
                        resourceId: '',
                        privileges: []
                    };
                };

                var setResources = function (resources) {
                    scope.resources = resources;
                };

                selectedDataServiceEventManager.on(
                    'rcmUserSelectedDataServiceChange.addRuleRoleData',
                    setRuleData
                );

                selectedDataServiceEventManager.on(
                    'rcmUserSelectedDataServiceChange.resources',
                    setResources
                );

                scope.cancel = function () {
                    element.find('#addRule').modal('hide');
                };

                scope.close = function () {
                    element.find('#addRule').modal('hide');
                };

                var onGetRolesSuccess = function (data, status) {
                    scope.close();
                };

                var isValid = function () {

                    if (!scope.resources[scope.ruleData.resourceId]) {
                        $window.alert('Resource is not valid: ' + scope.ruleData.resourceId);
                        return false;
                    }

                    return true;
                };

                var onError = function (data) {
                    $window.alert('An error occurred');
                    console.error(data);
                };

                var onAddRuleSuccess = function (data) {
                    rcmUserAclRoleService.getRulesByRoles(
                        onGetRolesSuccess,
                        onError
                    );
                };

                /**
                 * addRule
                 */
                scope.addRule = function () {
                    
                    if (!isValid()) {
                        return;
                    }

                    rcmUserAclRuleService.addRule(
                        scope.ruleData,
                        onAddRuleSuccess,
                        onError
                    );
                };

                scope.addRulePrivilege = function (privilege) {
                    if (scope.ruleData.privileges.indexOf(privilege) < 0) {
                        scope.ruleData.privileges.push(privilege);
                    }
                };

                scope.removeRulePrivilege = function (privilege) {
                    var index = scope.ruleData.privileges.indexOf(privilege);
                    if (index > -1) {
                        scope.ruleData.privileges.splice(index, 1);
                    }
                };

                scope.selected = {
                    privileges: {},
                    allPrivileges: true
                };

                scope.allRulePrivileges = function (hasAllPrivileges) {

                    if (!hasAllPrivileges) {
                        return;
                    }
                    for (var property in scope.selected.privileges) {
                        scope.selected.privileges[property] = false;
                    }

                    scope.ruleData.privileges = [];
                };

                scope.toggleRulePrivilege = function (privilege, isChecked) {

                    if (isChecked) {
                        scope.addRulePrivilege(privilege);
                    } else {
                        scope.removeRulePrivilege(privilege);
                    }

                    scope.selected.allPrivileges = (scope.ruleData.privileges.length == 0);
                }
            };
            return {
                link: thisLink,
                scope: {},
                templateUrl: '/modules/rcm-user/admin-acl-app/rcmuser-admin-acl-add-rule-directive.html'
            };
        }
    ]
);
