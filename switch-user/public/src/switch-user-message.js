/**
 * rcmSwitchUserMessage
 */
angular.module('rcmSwitchUser').directive(
    'rcmSwitchUserMessage', [
        '$sce',
        'rcmSwitchUserService',
        'rcmEventManager',
        function (
            $sce,
            rcmSwitchUserService,
            rcmEventManager
        ) {
            /**
             * Link function
             *
             * @param $scope
             * @param element
             * @param attrs
             */
            function link($scope, element, attrs) {

                $scope.loading = true;

                $scope.isSu = false;

                $scope.impersonatedUser = null;

                rcmEventManager.on(
                    'rcmSwitchUserService.suChange',
                    function (data) {
                        $scope.isSu = data.isSu;
                        $scope.impersonatedUser = data.impersonatedUser;
                        $scope.loading = false;
                    }
                );
            }

            return {
                link: link,
                scope: {
                    propShowSwitchToUserNameField: '=showSwitchToUserNameField', // bool
                    propSwitchToUserName: '=switchToUserName', // string
                    propSwitchToUserNamePlaceholder: '=switchToUserNamePlaceholder', // string
                    propSwitchToUserNameButtonLabel: '=switchToUserNameButtonLabel', // string
                    propSwitchBackButtonLabel: '=switchBackButtonLabel', // string,
                    propSwitchUserInfoContentPrefix: '=switchUserInfoContentPrefix' // string
                },
                template: '' +
                '<style type="text/css">' +
                '    .switch-user-message.real {' +
                '       position: fixed;' +
                '       z-index: 1001;' +
                '    }' +
                '    .switch-user-message.placeholder {' +
                '       visibility: hidden' +
                '    }' +
                '    .switch-user-message .alert {' +
                '        padding: 3px;' +
                '    }' +
                '    .switch-user-message .alert-caution {' +
                '       background-color: #FFFFAA;' +
                '       border-color: #FFFF00;' +
                '       color: #999900;' +
                '   }' +
                '</style>' +
                '<div>' +
                '<div class="switch-user-message real" ng-if="isSu">' +
                ' <div class="alert alert-caution" role="alert"> ' +
                '  <div rcm-switch-user-admin-horizontal ' +
                '       show-switch-to-user-name-field="propShowSwitchToUserNameField"' +
                '       switch-to-user-name="propSwitchToUserName"' +
                '       switch-to-user-name-placeholder="propSwitchToUserNamePlaceholder"' +
                '       switch-to-user-name-button-label="propSwitchToUserNameButtonLabel"' +
                '       switch-back-button-label="propSwitchBackButtonLabel"' +
                '       switch-user-info-content-prefix="propSwitchUserInfoContentPrefix"' +
                '  ></div> ' +
                ' </div> ' +
                '</div>' +
                '<div class="switch-user-message placeholder" ng-if="isSu">' +
                ' <div class="alert alert-caution" role="alert"> ' +
                '  <div rcm-switch-user-admin-horizontal ' +
                '       show-switch-to-user-name-field="propShowSwitchToUserNameField"' +
                '       switch-to-user-name="propSwitchToUserName"' +
                '       switch-to-user-name-placeholder="propSwitchToUserNamePlaceholder"' +
                '       switch-to-user-name-button-label="propSwitchToUserNameButtonLabel"' +
                '       switch-back-button-label="propSwitchBackButtonLabel"' +
                '       switch-user-info-content-prefix="propSwitchUserInfoContentPrefix"' +
                '  ></div> ' +
                ' </div> ' +
                '</div>' +
                '</div>'
            }
        }
    ]
);
