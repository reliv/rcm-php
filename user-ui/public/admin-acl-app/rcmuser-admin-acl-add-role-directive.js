angular.module('rcmuserAdminAclApp').directive(
    'rcmuserAdminAclAddRoleDirective',
    [
        '$window',
        'rcmUserAclRoleService',
        'rcmUserSelectedDataService',
        'getNamespaceRepeatString',
        function (
            $window,
            rcmUserAclRoleService,
            rcmUserSelectedDataService,
            getNamespaceRepeatString
        ) {
            var thisLink = function (scope, element, attrs) {
                var rootPlaceHolder = '*root*';
                var roleServiceEventManager = rcmUserAclRoleService.getEventManager();
                var selectedDataServiceEventManager = rcmUserSelectedDataService.getEventManager();

                scope.loading = false;

                roleServiceEventManager.on(
                    'RcmUserHttp.loading',
                    function (loading) {
                        scope.loading = loading;
                    }
                );

                var setRoles = function (roles) {

                    roles = angular.copy(roles);

                    var roleNs;
                    var roleObj;
                    for (roleNs in roles) {
                        roleObj = roles[roleNs];
                        roleObj.repeatString = getNamespaceRepeatString(roleObj.roleNs, '..');
                    }
                    // root role
                    roles[''] = {
                        role: {
                            roleId: rootPlaceHolder
                        },
                        repeatString: ''
                    };
                    scope.roles = roles;
                    scope.roleData = {
                        roleId: '',
                        parentRoleId: rootPlaceHolder,
                        description: ''
                    };
                };

                selectedDataServiceEventManager.on(
                    'rcmUserSelectedDataServiceChange.addRoleRoles',
                    setRoles
                );

                scope.cancel = function () {
                    element.find('#addRole').modal('hide');
                };

                scope.close = function () {
                    element.find('#addRole').modal('hide');
                };

                var onError = function (data) {
                    $window.alert('An error occurred');
                    console.error(data);
                    scope.loading = false;
                };

                var onGetRolesSuccess = function (data, status) {
                    scope.close();
                };

                var isValid = function () {
                    return true;
                };

                var onAddRoleSuccess = function (data, status) {
                    rcmUserAclRoleService.getRulesByRoles(
                        onGetRolesSuccess,
                        onError
                    );
                };

                scope.addRole = function () {

                    if (!isValid()) {
                        return;
                    }

                    if (scope.roleData.parentRoleId === rootPlaceHolder) {
                        scope.roleData.parentRoleId = null;
                    }

                    rcmUserAclRoleService.addRole(
                        scope.roleData,
                        onAddRoleSuccess,
                        onError
                    );
                };
            };
            return {
                link: thisLink,
                scope: {},
                templateUrl: '/modules/rcm-user/admin-acl-app/rcmuser-admin-acl-add-role-directive.html'
            };
        }
    ]
);
