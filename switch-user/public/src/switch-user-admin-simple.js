/**
 * rcmSwitchUserAdmin
 */
angular.module('rcmSwitchUser').directive(
    'rcmSwitchUserAdminSimple',
    [
        'rcmSwitchUserAdminService',
        function (
            rcmSwitchUserAdminService
        ) {
            return {
                link: rcmSwitchUserAdminService.link,
                scope: rcmSwitchUserAdminService.scope,
                template: '' +
                '<rcm-switch-user-switch-to-user-simple' +
                ' loading="loading"' +
                ' is-su="isSu"' +
                ' impersonated-user="impersonatedUser"' +
                ' switch-back-method="switchBackMethod"' +
                ' show-switch-to-user-name-field="propShowSwitchToUserNameField"' +
                ' switch-to-user-name="propSwitchToUserName"' +
                ' switch-to-user-name-placeholder="propSwitchToUserNamePlaceholder"' +
                ' switch-to-user-name-button-label="propSwitchToUserNameButtonLabel"' +
                ' switch-back-button-label="propSwitchBackButtonLabel"' +
                ' su-user-password="suUserPassword"' +
                ' switch-user-info-content-prefix="propSwitchUserInfoContentPrefix"' +
                ' message="message"' +
                ' on-switch-to="switchTo"' +
                ' on-switch-back="switchBack"' +
                '>' +
                '</rcm-switch-user-switch-to-user-simple>'
            }
        }
    ]
);
