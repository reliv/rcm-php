/**
 * {RcmSwitchUserAdminService}
 *
 * @param $sce
 * @param rcmSwitchUserService
 * @param rcmEventManager
 * @param rcmApiLibMessageService
 * @param $window
 * @param rcmLoading
 */
var RcmSwitchUserAdminService = function (
    $sce,
    rcmSwitchUserService,
    rcmEventManager,
    rcmApiLibMessageService,
    $window,
    rcmLoading
) {

    var self = this;

    self.link = function ($scope) {

        $scope.loading = false;
        $scope.isSu = false;
        $scope.impersonatedUser = null;
        $scope.switchBackMethod = 'auth';
        $scope.suUserPassword = null;
        $scope.message = null;

        var setLoading = function (isLoading) {
            $scope.loading = isLoading;
            var loadingInt = Number(!isLoading);
            rcmLoading.setLoading(
                'rcmSwitchUserAdmin.loading',
                loadingInt
            );
        };

        /**
         * apiInit
         */
        var apiInit = function () {
            setLoading(true);
            $scope.message = null;
        };

        /**
         *handleMessages
         * @param messages
         */
        var handleMessages = function (messages) {
            $scope.message = null;
            rcmApiLibMessageService.getPrimaryMessage(
                messages,
                function (message) {
                    if (message) {
                        $scope.message = message;
                    }
                }
            );
        };

        /**
         * onSwitchToSuccess
         * @param response
         */
        var onSwitchToSuccess = function (response) {
            $window.location.reload();
        };

        /**
         * onSwitchToError
         * @param response
         */
        var onSwitchToError = function (response) {
            handleMessages(response.messages);
            setLoading(false);
        };

        /**
         * onSwitchBackAndToSuccess
         * @param response
         */
        var onSwitchBackAndToSuccess = function (response) {
            $scope.suUserPassword = null;
            switchTo();
        };

        /**
         * onSwitchBackSuccess
         * @param response
         */
        var onSwitchBackSuccess = function (response) {
            $scope.suUserPassword = null;
            $window.location.reload();
        };

        /**
         * onSwitchBackError
         * @param response
         */
        var onSwitchBackError = function (response) {
            $scope.suUserPassword = null;
            handleMessages(response.messages);
            setLoading(false);
        };

        /**
         * switchTo
         */
        var switchTo = function () {
            apiInit();
            rcmSwitchUserService.switchUser(
                $scope.propSwitchToUserName,
                onSwitchToSuccess,
                onSwitchToError
            );
        };

        /**
         * switchTo
         */
        $scope.switchTo = function () {
            if ($scope.isSu) {
                apiInit();
                rcmSwitchUserService.switchUserBack(
                    $scope.propSwitchToUserName,
                    onSwitchBackAndToSuccess,
                    onSwitchBackError
                );
                return;
            }

            switchTo();
        };

        /**
         * switchBack
         */
        $scope.switchBack = function () {
            apiInit();
            rcmSwitchUserService.switchUserBack(
                $scope.suUserPassword,
                onSwitchBackSuccess,
                onSwitchBackError
            );
        };

        /**
         * rcmEventManager.on
         */
        rcmEventManager.on(
            'rcmSwitchUserService.suChange',
            function (data) {
                $scope.isSu = data.isSu;
                $scope.impersonatedUser = data.impersonatedUser;
                $scope.switchBackMethod = data.switchBackMethod;
                //$scope.loading = false;
            }
        );
    };

    self.scope = {
        propShowSwitchToUserNameField: '=showSwitchToUserNameField', // bool
        propSwitchToUserName: '=switchToUserName', // string
        propSwitchToUserNamePlaceholder: '=switchToUserNamePlaceholder', // string
        propSwitchToUserNameButtonLabel: '=switchToUserNameButtonLabel', // string
        propSwitchBackButtonLabel: '=switchBackButtonLabel', // string
        propSwitchUserInfoContentPrefix: '=switchUserInfoContentPrefix' // string
    }
};

/**
 * rcmSwitchUserService
 */
angular.module('rcmSwitchUser').service(
    'rcmSwitchUserAdminService',
    [
        '$sce',
        'rcmSwitchUserService',
        'rcmEventManager',
        'rcmApiLibMessageService',
        '$window',
        'rcmLoading',
        function (
            $sce,
            rcmSwitchUserService,
            rcmEventManager,
            rcmApiLibMessageService,
            $window,
            rcmLoading
        ) {
            return new RcmSwitchUserAdminService(
                $sce,
                rcmSwitchUserService,
                rcmEventManager,
                rcmApiLibMessageService,
                $window,
                rcmLoading
            );
        }
    ]
);
