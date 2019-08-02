/**
 * rcmuserAdminUsersApp.rcmuserAdminUsersData
 */
angular.module('rcmuserAdminUsersApp').factory(
    'rcmuserAdminUsersData',
    [
        'rcmUserConfig',
        function (rcmUserConfig) {

            var self = this;

            self.url = rcmUserConfig.url;

            self.rolePropertyId = 'RcmUserUserRoles';

            self.availableStates = [
                'enabled',
                'disabled'
            ];

            return self;
        }
    ]
);
