/**
 * rcmuserCore.RcmUserResult
 */
angular.module('rcmuserCore').factory(
    'RcmUserResult', function () {

        var RcmUserResult = function (code, messages, data) {

            var self = this;

            self.code = code;
            self.messages = messages;
            self.data = data;
        };

        return RcmUserResult;
    }
);
