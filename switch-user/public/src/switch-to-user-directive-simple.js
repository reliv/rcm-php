/**
 * rcmSwitchUserSwitchToUserSimple
 */
angular.module('rcmSwitchUser').directive(
    'rcmSwitchUserSwitchToUserSimple',
    [
        '$sce',
        '$window',
        function (
            $sce,
            $window
        ) {
            /**
             *
             * @param $scope
             * @param element
             * @param attrs
             */
            function link($scope, element, attrs) {
                $scope.showImpersonatorDetails = false;

                $scope.toggleImpersonatorDetails = function()
                {
                    $scope.showImpersonatorDetails = !$scope.showImpersonatorDetails;
                }
            }

            return {
                link: link,
                scope: {
                    propLoading: '=loading', // Bool
                    propIsSu: '=isSu', // Bool
                    propImpersonatedUser: '=impersonatedUser', // {User}
                    propSwitchBackMethod: '=switchBackMethod', // string ('auth' or 'basic')
                    propShowSwitchToUserNameField: '=showSwitchToUserNameField', // bool
                    propSwitchToUserName: '=switchToUserName', // string
                    propSwitchToUserNamePlaceholder: '=switchToUserNamePlaceholder', // string
                    propSwitchToUserNameButtonLabel: '=switchToUserNameButtonLabel', // string
                    propSwitchBackButtonLabel: '=switchBackButtonLabel', // string
                    propSuUserPassword: '=suUserPassword', // string
                    propSwitchUserInfoContentPrefix: '=switchUserInfoContentPrefix', // string
                    propMessage: '=message', // {message},
                    propOnSwitchTo: '=onSwitchTo', // function
                    propOnSwitchBack: '=onSwitchBack' // function
                },
                template: '<%= inlineTemplate("src/switch-to-user-directive-simple.html") %>'
            }
        }
    ]
);
