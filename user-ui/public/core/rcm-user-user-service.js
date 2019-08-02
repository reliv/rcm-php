/**
 * rcmUserAclRuleService
 */
angular.module('rcmuserCore').factory(
    'rcmUserUserService',
    [
        'RcmUserHttp',
        'rcmuserAdminUsersData',
        function (
            RcmUserHttp,
            rcmuserAdminUsersData
        ) {
            var RcmUserUserService = function () {

                var self = this;
                var rcmUserHttp = RcmUserHttp;
                var url = rcmuserAdminUsersData.url;

                self.getEventManager = function () {
                    return rcmUserHttp.getEventManager();
                };
                
                self.getPropertyRoleId = function () {
                    return rcmuserAdminUsersData.rolePropertyId;
                };

                self.getUsers = function (onSuccess, onFail) {

                    var config = {
                        method: 'GET',
                        url: url.users
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail);
                };

                self.getRoles = function (onSuccess, onFail) {

                    var config = {
                        method: 'GET',
                        url: url.role
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail);
                };

                self.getDefaultUserRoles = function (onSuccess, onFail) {

                    var config = {
                        method: 'GET',
                        url: url.defaultUserRoles
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail);
                };

                self.getValidUserStates = function (onSuccess, onFail) {

                    var config = {
                        method: 'GET',
                        url: url.validUserStates
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail);
                };

                // API CALLS
                self.createUser = function (user, onSuccess, onFail) {

                    var config = {
                        method: 'POST',
                        url: url.users,
                        data: user
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail);
                };

                self.updateUser = function (user, onSuccess, onFail) {

                    var config = {
                        method: 'PUT',
                        url: url.users + '/' + user.id,
                        data: user
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail);
                };

                self.removeUser = function (user, onSuccess, onFail) {

                    var config = {
                        method: 'DELETE',
                        url: url.users + '/' + user.id,
                        data: user
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail);
                };

                self.getUser = function (user, onSuccess, onFail) {

                    var config = {
                        method: 'GET',
                        url: url.users + '/' + user.id
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail);
                };
            };

            return new RcmUserUserService();
        }
    ]
);
