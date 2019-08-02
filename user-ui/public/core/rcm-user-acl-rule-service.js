/**
 * rcmUserAclRuleService
 */
angular.module('rcmuserCore').factory(
    'rcmUserAclRuleService',
    [
        'RcmUserHttp',
        'rcmuserAdminAclData',
        function (
            RcmUserHttp,
            rcmuserAdminAclData
        ) {
            var RcmUserAclRuleService = function () {

                var self = this;
                var rcmUserHttp = RcmUserHttp;
                var url = rcmuserAdminAclData.url;

                self.getEventManager = function () {
                    return rcmUserHttp.getEventManager();
                };

                /**
                 *
                 * @param ruleData
                 * @param onSuccess
                 * @param onFail
                 */
                self.addRule = function (ruleData, onSuccess, onFail) {

                    var config = {
                        method: 'POST',
                        url: url.rule,
                        data: ruleData
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail, 'rcmUserAclRuleService.addRule');
                };

                /**
                 * removeRule
                 * @param ruleData
                 * @param onSuccess
                 * @param onFail
                 */
                self.removeRule = function (ruleData, onSuccess, onFail) {
                    var config = {
                        method: 'DELETE',
                        url: url.rule + "/" + JSON.stringify(ruleData),
                        data: ruleData
                    };

                    rcmUserHttp.execute(config, onSuccess, onFail, 'rcmUserAclRuleService.removeRule');
                };
            };

            return new RcmUserAclRuleService();
        }
    ]
);
