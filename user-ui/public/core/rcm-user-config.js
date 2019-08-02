/**
 * rcmuserCore.rcmUserConfig
 */
angular.module('rcmuserCore').factory(
    'rcmUserConfig', function () {

        var self = this;

        self.url = {
            defaultUserRoles: "/api/admin/rcmuser-acl-default-user-roles",
            resources: "/api/admin/rcmuser-acl-resources",
            role: "/api/admin/rcmuser-acl-role",
            rulesByroles: "/api/admin/rcmuser-acl-rulesbyroles",
            rule: "/api/admin/rcmuser-acl-rule",
            user: "/api/admin/rcmuser-user",
            users: "/api/admin/rcmuser-user",
            validUserStates: "/api/admin/rcmuser-user-validuserstates"
        };

        return self;
    }
);
