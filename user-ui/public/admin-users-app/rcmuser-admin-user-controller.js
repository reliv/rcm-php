/**
 * rcmuserAdminUsersApp.rcmuserAdminUser
 */
angular.module('rcmuserAdminUsersApp').controller(
    'rcmuserAdminUser',
    [
        '$window',
        '$scope',
        '$log',
        'RcmUserResult',
        'RcmResults',
        'rcmUserUserService',
        'getNamespaceRepeatString',
        function (
            $window,
            $scope,
            $log,
            RcmUserResult,
            RcmResults,
            rcmUserUserService,
            getNamespaceRepeatString
        ) {
            var self = this;

            $scope.rolePropertyId = rcmUserUserService.getPropertyRoleId();
            var userServiceEventManager = rcmUserUserService.getEventManager();

            $scope.loading = true;

            userServiceEventManager.on(
                'RcmUserHttp.loading',
                function (loading) {
                    $scope.loading = loading;
                }
            );

            $scope.getNamespaceRepeatString = getNamespaceRepeatString;

            $scope.showEdit = false;

            $scope.defaultRoles = [];

            $scope.orgUser = angular.copy($scope.user);

            $scope.isDefaultRole = function (roleId) {
                var index = $scope.defaultRoles.indexOf(roleId);
                return (index !== -1);
            };

            var onError = function (data) {
                $window.alert('An error occurred');
                console.error(data);
            };

            var onOpenEditUserSuccess = function (data) {
                $scope.showEdit = true;
                $scope.user = data.data;
            };

            $scope.openEditUser = function () {
                rcmUserUserService.getUser($scope.user, onOpenEditUserSuccess, onError);
            };

            $scope.openRemoveUser = function () {
                // @todo show dialog
            };

            $scope.addRole = function (roleId) {
                if (typeof($scope.user.properties[$scope.rolePropertyId]) === 'undefined') {
                    $scope.user.properties[$scope.rolePropertyId] = [];
                }

                if ($scope.user.properties[$scope.rolePropertyId].indexOf(roleId) === -1) {
                    $scope.user.properties[$scope.rolePropertyId].push(roleId);
                }
            };

            $scope.removeRole = function (roleId) {
                var index = $scope.user.properties[$scope.rolePropertyId].indexOf(
                    roleId
                );
                if (index === -1) {
                    return;
                }
                $scope.user.properties[$scope.rolePropertyId].splice(index, 1);
            };

            var onCreateUserSuccess = function (data, status) {
                $scope.user = data.data;
            };

            /* <USER> */
            $scope.createUser = function (user) {
                rcmUserUserService.createUser($scope.user, onCreateUserSuccess, onError);
            };

            var onUpdateUserSuccess = function (data, status) {
                $scope.user = data.data;
            };

            $scope.updateUser = function (user) {
                rcmUserUserService.updateUser($scope.user, onUpdateUserSuccess, onError);
            };

            var onRemoveUserSuccess = function (data, status) {
                if (typeof($scope.users.splice) === 'function') {
                    $scope.users.splice($scope.index, 1);
                } else {
                    $log.error(
                        'Expected array, user could not be properly removed'
                    );
                }

                delete $scope.user;
            };

            $scope.removeUser = function (user) {
                rcmUserUserService.removeUser($scope.user, onRemoveUserSuccess, onError);
            };

            $scope.resetUser = function () {
                $scope.user = angular.copy($scope.orgUser);
            };

            $scope.cancelCreateUser = function () {
                // @todo need pop this from users in parent scope
                $scope.user = $scope.users.splice($scope.index, 1);
            };
            /* </USER> */

            var onGetDefaultUserRolesSuccess = function (data, status) {
                $scope.defaultRoles = data.data;
            };

            rcmUserUserService.getDefaultUserRoles(
                onGetDefaultUserRolesSuccess,
                onError
            );

        }
    ]
);
