/**
 * rcmuserAdminAclApp.rcmuserAdminAclData
 */
angular.module('rcmuserAdminAclApp').factory(
    'rcmuserAdminAclData',
    [
        'rcmUserConfig',
        function (rcmUserConfig) {

            var self = this;

            self.url = rcmUserConfig.url;

            self.get = function(success, error) {
                // @todo API call here
            };

            return self;
        }
    ]
);
