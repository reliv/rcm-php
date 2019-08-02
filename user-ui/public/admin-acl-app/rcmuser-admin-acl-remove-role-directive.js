angular.module('rcmuserAdminAclApp').directive(
    'rcmuserAdminAclRemoveRoleDirective',
    [
        'rcmUserEventManager',
        'rcmUserAclRoleService',
        'rcmUserSelectedDataService',
        function (
            rcmUserEventManager,
            rcmUserAclRoleService,
            rcmUserSelectedDataService
        ) {
            var thisLink = function (scope, element, attrs) {
                scope.rootPlaceHolder = '*root*';
                var roleServiceEventManager = rcmUserAclRoleService.getEventManager();
                var selectedDataServiceEventManager = rcmUserSelectedDataService.getEventManager();
                scope.loading = false;

                roleServiceEventManager.on(
                    'RcmUserHttp.loading',
                    function (loading) {
                        scope.loading = loading;
                    }
                );

                var setRoleData = function(roleData) {
                    scope.roleData = roleData;
                };

                selectedDataServiceEventManager.on(
                    'rcmUserSelectedDataServiceChange.removeRoleRoleData',
                    setRoleData
                );

                scope.cancel = function () {
                    element.find('#removeRole').modal('hide');
                };

                scope.close = function () {
                    element.find('#removeRole').modal('hide');
                };

                var onError = function (data, status) {
                    $window.alert('An error occurred');
                    console.error(data);
                };

                var onGetRolesSuccess = function (data, status) {
                    scope.close();
                };

                var onRemoveRoleSuccess = function (data, status) {
                    rcmUserAclRoleService.getRulesByRoles(
                        onGetRolesSuccess,
                        onError
                    );
                };

                scope.removeRole = function () {
                    rcmUserAclRoleService.removeRole(
                        scope.roleData.role,
                        onRemoveRoleSuccess,
                        onError
                    );
                };
            };
            return {
                link: thisLink,
                scope: {},
                templateUrl: '/modules/rcm-user/admin-acl-app/rcmuser-admin-acl-remove-role-directive.html'
            };
        }
    ]
);
