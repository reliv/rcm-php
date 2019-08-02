/**
 * rcmUserAclRoleService
 */
angular.module('rcmuserCore').factory(
    'rcmUserAclRoleService',
    [
        'RcmUserHttp',
        'rcmuserAdminAclData',
        function (
            RcmUserHttp,
            rcmuserAdminAclData
        ) {
            var RcmUserAclRoleService = function () {

                var self = this;
                var rcmUserHttp = RcmUserHttp;
                var url = rcmuserAdminAclData.url;

                self.getEventManager = function () {
                    return rcmUserHttp.getEventManager();
                };

                /**
                 * create/addRole
                 * @param roleData
                 * @param onSuccess
                 * @param onFail
                 */
                self.addRole = function (roleData, onSuccess, onFail) {
                    var config = {
                        method: 'POST',
                        url: url.role,
                        data: roleData
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail, 'rcmUserAclRoleService.addRole');
                };

                /**
                 * getRulesByRoles
                 * @param onSuccess
                 * @param onFail
                 */
                self.getRulesByRoles = function (onSuccess, onFail) {

                    var config = {
                        method: 'GET',
                        url: url.rulesByroles
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail, 'rcmUserAclRoleService.getRulesByRoles');
                };

                /**
                 * removeRole
                 * @param roleData
                 * @param onSuccess
                 * @param onFail
                 */
                self.removeRole = function (roleData, onSuccess, onFail) {
                    var config = {
                        method: 'DELETE',
                        url: url.role + "/" + roleData.roleId
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail, 'rcmUserAclRoleService.removeRole');
                };
            };

            return new RcmUserAclRoleService();
        }
    ]
);
