/**
 * RcmSwitchUserService
 * @param rcmLoading
 * @param rcmApiLibService
 * @param rcmEventManager
 * @constructor
 */
var RcmSwitchUserService = function (rcmLoading, rcmApiLibService, rcmEventManager) {

    /**
     * self
     */
    var self = this;

    /**
     * config
     * @type {{suMessage: string}}
     */
    self.config = {
        suMessage: 'User is currently impersonating.'
    };

    /**
     * suData
     * @type {boolean}
     */
    self.suData = {
        isSu: false,
        impersonatedUser: null,
        switchBackMethod: 'auth'
    };

    /**
     * apiPaths
     * @type {{switchUser: string, switchUserBack: string}}
     */
    var apiPaths = {
        switchUser: '/api/rpc/switch-user',
        switchUserBack: '/api/rpc/switch-user-back'
    };

    /**
     * changeSu
     * @param data
     */
    var changeSu = function (data) {
        if (!data) {
            self.suData = {
                isSu: false,
                impersonatedUser: null,
                switchBackMethod: self.suData.switchBackMethod
            };
            return;
        }
        self.suData = data
    };

    /**
     * buildValidData
     * @param data
     * @returns {*}
     */
    var buildValidData = function (data) {
        if (!data) {
            data = {
                isSu: false,
                impersonatedUser: null,
                switchBackMethod: self.suData.switchBackMethod
            };
        }

        return data;
    };

    /**
     * onSuChange
     * @param data
     */
    var onSuChange = function (data) {

        data = buildValidData(data);

        changeSu(data);

        rcmEventManager.trigger(
            'rcmSwitchUserService.suChange',
            data
        );
    };

    /**
     * The suMayBeActive flag causes the SU system to only ask the server about SU info
     * if an SU has happened in this browser session.
     * Gets the cached data from the browser's "session" local storage.
     * This storage clears if the browser is closed.
     *
     * @returns {*}
     */
    function getSuMayBeActive() {
        var mayBeActive = false;
        if (typeof(sessionStorage) !== "undefined" && sessionStorage.rcmSwitchUser_suMayBeActive) {
            mayBeActive = JSON.parse(sessionStorage.rcmSwitchUser_suMayBeActive);
        }
        return mayBeActive;
    }

    /**
     * The suMayBeActive flag causes the SU system to only ask the server about SU info
     * if an SU has happened in this browser session.
     * Sets the cached data in the browser's "session" local storage
     * This storage clears if the browser is closed.
     *
     * @param data
     */
    function setSuMayBeActive(data) {
        if (typeof(sessionStorage) !== "undefined") {
            sessionStorage.rcmSwitchUser_suMayBeActive = JSON.stringify(data);
        }
    }

    /**
     * getSu
     * @param onSuccess
     * @param onError
     */
    self.getSu = function (onSuccess, onError) {

        rcmApiLibService.get(
            {
                url: apiPaths.switchUser,
                loading: function (loading) {
                    var loadingInt = Number(!loading);
                    rcmLoading.setLoading(
                        'rcmSwitchUserService.loading',
                        loadingInt
                    );
                },
                success: function (response) {
                    onSuChange(response.data);
                    onSuccess(response);
                },
                error: function (response) {
                    onSuChange(response.data);
                    onError(response);
                }
            }
        );
    };

    /**
     * switchUser
     * @param switchToUsername
     * @param onSuccess
     * @param onError
     */
    self.switchUser = function (switchToUsername, onSuccess, onError) {

        setSuMayBeActive(true);

        var data = {
            switchToUsername: switchToUsername
        };

        rcmApiLibService.post(
            {
                url: apiPaths.switchUser,
                data: data,
                loading: function (loading) {
                    var loadingInt = Number(!loading);
                    rcmLoading.setLoading(
                        'rcmSwitchUserService.loading',
                        loadingInt
                    );
                },
                success: function (response, status) {
                    onSuChange(
                        response.data
                    );
                    onSuccess(response, status);
                },
                error: function (response, status) {
                    onSuChange(response.data);
                    onError(response, status);
                }
            }
        );
    };

    /**
     * switchUserBack
     * @param suUserPassword
     * @param onSuccess
     * @param onError
     */
    self.switchUserBack = function (suUserPassword, onSuccess, onError) {

        var data = {
            suUserPassword: suUserPassword
        };

        rcmApiLibService.post(
            {
                url: apiPaths.switchUserBack,
                data: data,
                loading: function (loading) {
                    var loadingInt = Number(!loading);
                    rcmLoading.setLoading(
                        'rcmSwitchUserService.loading',
                        loadingInt
                    );
                },
                success: function (response, status) {
                    onSuChange();
                    onSuccess(response, status);
                },
                error: function (response, status) {
                    onSuChange();
                    onError(response, status);
                }
            }
        );
    };

    /**
     * init
     */
    var init = function () {
        if (!getSuMayBeActive()) {
            return;
        }

        self.getSu(
            function () {
            },
            function () {
            }
        )
    };

    init();
};

/**
 * rcmSwitchUserService
 */
angular.module('rcmSwitchUser').service(
    'rcmSwitchUserService',
    [
        'rcmLoading',
        'rcmApiLibService',
        'rcmEventManager',
        function (
            rcmLoading,
            rcmApiLibService,
            rcmEventManager
        ) {
            return new RcmSwitchUserService(
                rcmLoading,
                rcmApiLibService,
                rcmEventManager
            );
        }
    ]
);
