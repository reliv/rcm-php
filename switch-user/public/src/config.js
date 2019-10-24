/**
 * {RcmSwitchUserConfig}
 * @constructor
 */
var RcmSwitchUserConfig = function () {
    var self = this;

    self.config = {};

    self.defaults = {
        showSwitchToUserNameField: true,
        switchToUserName: '',
        switchToUserNameButtonLabel: 'Switch to User',
        switchBackButtonLabel: 'End Impersonation',
        switchUserInfoContentPrefix: 'Impersonating:',
        switchToUserNamePlaceholder: 'Username',
    };

    self.get = function (key) {

        if (self.config[key]) {
            return self.config[key];
        }

        if (self.defaults[key]) {
            return self.defaults[key];
        }

        return null;
    }
};

/**
 * rcmSwitchUserConfig
 */
angular.module('rcmSwitchUser').service(
    'rcmSwitchUserConfig',
    function () {
        return new RcmSwitchUserConfig();
    }
);

