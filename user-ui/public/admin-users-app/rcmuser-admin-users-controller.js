/**
 * rcmuserAdminUsersApp.rcmuserAdminUsers
 */
angular.module('rcmuserAdminUsersApp').controller(
    'rcmuserAdminUsers',
    [
        '$window',
        '$scope',
        '$log',
        '$uibModal',
        'RcmUserResult',
        'RcmResults',
        'rcmUserUserService',
        function (
            $window,
            $scope,
            $log,
            $uibModal,
            RcmUserResult,
            RcmResults,
            rcmUserUserService
        ) {
            var self = this;

            var userServiceEventManager = rcmUserUserService.getEventManager();

            $scope.loading = true;

            userServiceEventManager.on(
                'RcmUserHttp.loading',
                function (loading) {
                    $scope.loading = loading;
                }
            );

            $scope.userQuery = '';

            $scope.availableStates = [
                'enabled',
                'disabled'
            ];

            // User
            $scope.showMessages = false;

            var onError = function (data) {
                $window.alert('An error occurred');
                console.error(data);
            };

            var onGetUsersSuccess = function (data) {
                $scope.users = data.data;
                $scope.messages = data.messages;
            };

            rcmUserUserService.getUsers(
                onGetUsersSuccess,
                onError
            );

            var onGetRolesSuccess = function (data) {
                $scope.roles = data.data;
                $scope.messages = data.messages;
            };

            // User Roles
            rcmUserUserService.getRoles(
                onGetRolesSuccess,
                onError
            );

            var onValidUserStatesSuccess = function (data, status) {

                $scope.availableStates = data.data;
            };
            // valid user states
            rcmUserUserService.getValidUserStates(
                onValidUserStatesSuccess
            );

            $scope.rolePropertyId = rcmUserUserService.getPropertyRoleId();

            $scope.oneAtATime = false;

            $scope.addUser = function () {

                var user = {
                    username: '',
                    password: null,
                    state: 'disabled',
                    email: '',
                    name: '',
                    properties: {},
                    isNew: true
                };

                user.properties[$scope.rolePropertyId] = [];

                $scope.users.unshift(user);

                // clear filter
                $scope.userQuery = '';
            }
        }
    ]
);
