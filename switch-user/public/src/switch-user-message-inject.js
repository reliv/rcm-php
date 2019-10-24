/**
 * RcmSwitchUserMessageInject dom loader
 *
 * @param $compile
 * @param JSON
 * @param rcmSwitchUserConfig
 * @constructor
 */
var RcmSwitchUserMessageInject = function (
    $compile,
    JSON,
    rcmSwitchUserConfig
) {
    var self = this;

    /**
     *
     * @param value
     * @param defaultKey
     * @param fallback
     * @returns {*}
     */
    self.getDefault = function (value, defaultKey, fallback) {

        if (typeof value === 'undefined') {
            value = rcmSwitchUserConfig.defaults[defaultKey];
        }

        if (typeof value === 'undefined') {
            value = fallback;
        }

        return value;
    };
    /**
     *
     * @param {boolean} showSwitchToUserNameField
     * @param {string} switchToUserName
     * @param {string} switchToUserNamePlaceholder
     * @param {string} switchToUserNameButtonLabel
     * @param {string} switchBackButtonLabel
     */
    self.injectHeader = function (
        showSwitchToUserNameField,
        switchToUserName,
        switchToUserNamePlaceholder,
        switchToUserNameButtonLabel,
        switchBackButtonLabel,
        switchUserInfoContentPrefix
    ) {
        showSwitchToUserNameField = self.getDefault(
            showSwitchToUserNameField,
            'showSwitchToUserNameField',
            true
        );

        switchToUserName = self.getDefault(
            switchToUserName,
            'switchToUserName',
            ''
        );

        switchToUserNamePlaceholder = self.getDefault(
            switchToUserNamePlaceholder,
            'switchToUserNamePlaceholder',
            'Username'
        );

        switchToUserNameButtonLabel = self.getDefault(
            switchToUserNameButtonLabel,
            'switchToUserNameButtonLabel',
            'Switch to User'
        );

        switchBackButtonLabel = self.getDefault(
            switchBackButtonLabel,
            'switchBackButtonLabel',
            'Switch Back'
        );

        switchUserInfoContentPrefix = self.getDefault(
            switchUserInfoContentPrefix,
            'switchUserInfoContentPrefix',
            'Impersonating:'
        );

        showSwitchToUserNameField = Boolean(showSwitchToUserNameField);
        showSwitchToUserNameField = JSON.stringify(showSwitchToUserNameField);
        switchToUserName = String(switchToUserName);
        switchToUserNamePlaceholder = String(switchToUserNamePlaceholder);
        switchToUserNameButtonLabel = String(switchToUserNameButtonLabel);
        switchBackButtonLabel = String(switchBackButtonLabel);
        switchUserInfoContentPrefix = String(switchUserInfoContentPrefix);

        var content = '' +
            '<div rcm-switch-user-message' +
            ' show-switch-to-user-name-field="' + showSwitchToUserNameField + '"' +
            ' switch-to-user-name="\'' + switchToUserName + '\'"' +
            ' switch-to-user-name-placeholder="\'' + switchToUserNamePlaceholder + '\'"' +
            ' switch-to-user-name-button-label="\'' + switchToUserNameButtonLabel + '\'"' +
            ' switch-back-button-label="\'' + switchBackButtonLabel + '\'"' +
            ' switch-user-info-content-prefix="\'' + switchUserInfoContentPrefix + '\'""' +
            '></div>';

        var element = jQuery(content);
        element.prependTo('body');

        var contents = element.contents();
        var aemlement = angular.element(element);
        var scope = aemlement.scope;

        $compile(contents)(scope);
    }
};

/**
 * rcmSwitchUserService
 */
angular.module('rcmSwitchUser').service(
    'rcmSwitchUserMessageInject',
    [
        '$compile',
        'rcmSwitchUserConfig',
        function (
            $compile,
            rcmSwitchUserConfig
        ) {
            return new RcmSwitchUserMessageInject(
                $compile,
                JSON,
                rcmSwitchUserConfig
            );
        }
    ]
);

/**
 * Example usage - To inject the switch user header bar, add this code to your application
 */
angular.module('rcmSwitchUser').run(
    [
        'rcmSwitchUserMessageInject',
        'rcmSwitchUserConfig',
        function (
            rcmSwitchUserMessageInject,
            rcmSwitchUserConfig
        ) {
            rcmSwitchUserMessageInject.injectHeader(
                rcmSwitchUserConfig.defaults.showSwitchToUserNameField,
                rcmSwitchUserConfig.defaults.switchToUserName,
                rcmSwitchUserConfig.defaults.switchToUserNamePlaceholder,
                rcmSwitchUserConfig.defaults.switchToUserNameButtonLabel,
                rcmSwitchUserConfig.defaults.switchBackButtonLabel,
                rcmSwitchUserConfig.defaults.switchUserInfoContentPrefix
            );
        }
    ]
);
