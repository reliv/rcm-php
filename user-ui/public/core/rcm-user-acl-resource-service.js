/**
 * rcmUserAclResourceService
 */
angular.module('rcmuserCore').factory(
    'rcmUserAclResourceService',
    [
        'RcmUserHttp',
        'rcmuserAdminAclData',
        function (
            RcmUserHttp,
            rcmuserAdminAclData
        ) {
            var RcmUserAclResourceService = function () {

                var self = this;
                var rcmUserHttp = RcmUserHttp;
                var url = rcmuserAdminAclData.url;

                self.getEventManager = function () {
                    return rcmUserHttp.getEventManager();
                };

                self.getResources = function (onSuccess, onError) {

                    var config = {
                        method: 'GET',
                        url: url.resources
                    };

                    rcmUserHttp.execute(config, onSuccess, onError, 'rcmUserAclResourceService.getResources');
                };
            };

            return new RcmUserAclResourceService();
        }
    ]
);
