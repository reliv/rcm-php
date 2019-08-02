/**
 * rcmuserCore.RcmUser
 */
angular.module('rcmuserCore').factory(
    'RcmUser', function () {

        var RcmUser = function () {

            var self = this;

            self.username = '';
            self.password = null;
            self.state = 'disabled';
            self.email = null;
            self.name = null;
            self.properties = {};
        };

        return RcmUser;
    }
);
