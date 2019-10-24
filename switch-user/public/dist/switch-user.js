/** RcmSwitch User Module **/
if (typeof rcm != 'undefined') {
    // RCM is undefined in unit tests
    rcm.addAngularModule('rcmSwitchUser');
}
angular.module('rcmSwitchUser', ['RcmLoading', 'RcmJsLib', 'rcmApiLib']);

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


/**
 * rcmSwitchUserSwitchToUser
 */
angular.module('rcmSwitchUser').directive(
    'rcmSwitchUserSwitchToUser',
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
                template: '<style type="text/css">    .switch-user.default label,    .switch-user.default p {        margin: 5px 0px 5px 0px;    }</style><div class="switch-user default container-fluid" ng-hide="propLoading">    <div class="row form-inline">        <div class="col-md-12" ng-show="propMessage">            <div class="alert alert-warning" role="alert">                {{propMessage.value}}            </div>        </div>        <div class="col-md-3" ng-show="propIsSu">            <label class="switch-user-info-content">                {{propSwitchUserInfoContentPrefix}} {{propImpersonatedUser.id}} {{propImpersonatedUser.username}}            </label>        </div>        <div class="col-md-4" ng-show="propShowSwitchToUserNameField">            <form ng-submit="propOnSwitchTo()">                <input class="form-control input-sm switchToUserName"                       placeholder="{{propSwitchToUserNamePlaceholder}}"                       ng-model="propSwitchToUserName"                       type="text"/>                <button class="btn btn-default btn-sm"                        type="submit">{{propSwitchToUserNameButtonLabel}}                </button>            </form>        </div>        <div class="col-md-4" ng-show="propIsSu">            <form ng-submit="propOnSwitchBack()">                <input class="form-control input-sm suUserPassword"                       placeholder="password"                       ng-model="propSuUserPassword"                       ng-show="propSwitchBackMethod == \'auth\'"                       type="password"/>                <button class="btn btn-primary btn-sm"                        type="submit">{{propSwitchBackButtonLabel}}                </button>            </form>        </div>    </div></div>'
            }
        }
    ]
);

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
                template: '<style type="text/css">    /* show full borders */    .switch-user.simple .input-group-btn button {        margin-right: 1px;    }</style><div class="switch-user simple">    <div class="row" ng-show="propMessage">        <div class="col-sm-12">            <div class="alert alert-warning" role="alert">                {{propMessage.value}}            </div>        </div>    </div>    <div class="row">        <div class="col-sm-12">            <form ng-submit="propOnSwitchTo()">                <div class="input-group input-group-sm">                    <input class="form-control switchToUserName"                           placeholder="{{propSwitchToUserNamePlaceholder}}"                           ng-model="propSwitchToUserName"                           type="text"                    >                    <span class="input-group-btn">                        <button class="btn btn-primary"                                type="submit">                            {{propSwitchToUserNameButtonLabel}}                        </button>                        <button class="btn btn-warning"                                ng-click="propOnSwitchBack()"                                ng-if="propSwitchBackMethod != \'auth\' && propIsSu"                                type="button">                            {{propSwitchBackButtonLabel}}                        </button>                        <button class="btn btn-default"                                ng-click="toggleImpersonatorDetails()"                                ng-if="propSwitchBackMethod != \'auth\' && propIsSu"                                type="button">                            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>                        </button>                    </span>                </div>            </form>            <form ng-show="propSwitchBackMethod == \'auth\' && propIsSu"                  ng-submit="propOnSwitchBack()">                <div class="input-group  input-group-sm">                    <input class="form-control suUserPassword"                           placeholder="password"                           ng-model="propSuUserPassword"                           type="password"                    >                    <span class="input-group-btn">                        <button class="btn btn-warning"                                type="submit">                            {{propSwitchBackButtonLabel}}                        </button>                        <button class="btn btn-default"                                ng-click="showImpersonatorDetails = !showImpersonatorDetails"                                type="button">                            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>                        </button>                    </span>                </div>            </form>            <rcm-switch-user-tooltip                    content="propSwitchUserInfoContentPrefix + \' \' + propImpersonatedUser.id + \' \' + propImpersonatedUser.username"                    show="showImpersonatorDetails && propIsSu"            >                tooltip            </rcm-switch-user-tooltip>        </div>    </div></div>'
            }
        }
    ]
);

/**
 * rcmSwitchUserSwitchToUserSimple
 */
angular.module('rcmSwitchUser').directive(
    'rcmSwitchUserSwitchToUserHorizontal',
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
                template: '<style type="text/css">    .switch-user.horizontal {        min-height: 20px;    }    .switch-user.horizontal label {        margin: .3em;    }    /* show full borders */    .switch-user.horizontal .input-group-btn button {        margin-right: 1px;    }</style><div class="switch-user horizontal">    <div class="row" ng-show="propMessage">        <div class="col-sm-12">            <div class="alert alert-warning" role="alert">                {{propMessage.value}}            </div>        </div>    </div>    <div class="row">        <div class="col-sm-4" ng-show="propIsSu">            <label>                <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>                <span class="switch-user-info-content">{{propSwitchUserInfoContentPrefix}} {{propImpersonatedUser.id}} {{propImpersonatedUser.username}}</span>            </label>        </div>        <div ng-class="{\'col-sm-4\': propSwitchBackMethod == \'auth\', \'col-sm-4 col-md-offset-4\': propSwitchBackMethod != \'auth\'}">            <form ng-submit="propOnSwitchTo()">                <div class="input-group input-group-sm">                    <input class="form-control switchToUserName"                           placeholder="{{propSwitchToUserNamePlaceholder}}"                           ng-model="propSwitchToUserName"                           type="text"                    >                    <span class="input-group-btn">                        <button class="btn btn-primary"                                type="submit">                            {{propSwitchToUserNameButtonLabel}}                        </button>                        <button class="btn btn-warning"                                ng-click="propOnSwitchBack()"                                ng-show="propSwitchBackMethod != \'auth\' && propIsSu"                                type="button">                            {{propSwitchBackButtonLabel}}                        </button>                    </span>                </div>            </form>        </div>        <div class="col-sm-4" ng-show="propSwitchBackMethod == \'auth\' && propIsSu">            <form ng-submit="propOnSwitchBack()">                <div class="input-group input-group-sm">                    <input class="form-control suUserPassword"                           placeholder="password"                           ng-model="propSuUserPassword"                           type="password"                    >                    <span class="input-group-btn">                        <button class="btn btn-warning"                                ng-show="propIsSu"                                type="submit">                            {{propSwitchBackButtonLabel}}                        </button>                    </span>                </div>            </form>        </div>    </div></div>'
            }
        }
    ]
);

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

/**
 * rcmSwitchUserAdmin
 */
angular.module('rcmSwitchUser').directive(
    'rcmSwitchUserAdmin',
    [
        'rcmSwitchUserAdminService',
        function (
            rcmSwitchUserAdminService
        ) {
            return {
                link: rcmSwitchUserAdminService.link,
                scope: rcmSwitchUserAdminService.scope,
                template: '' +
                '<rcm-switch-user-switch-to-user' +
                ' loading="loading"' +
                ' is-su="isSu"' +
                ' impersonated-user="impersonatedUser"' +
                ' switch-back-method="switchBackMethod"' +
                ' show-switch-to-user-name-field="propShowSwitchToUserNameField"' +
                ' switch-to-user-name="propSwitchToUserName"' +
                ' switch-to-user-name-button-label="propSwitchToUserNameButtonLabel"' +
                ' switch-back-button-label="propSwitchBackButtonLabel"' +
                ' su-user-password="suUserPassword"' +
                ' switch-user-info-content-prefix="propSwitchUserInfoContentPrefix"' +
                ' message="message"' +
                ' on-switch-to="switchTo"' +
                ' on-switch-back="switchBack"' +
                '>' +
                '</rcm-switch-user-switch-to-user>'
            }
        }
    ]
);

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

/**
 * rcmSwitchUserAdmin
 */
angular.module('rcmSwitchUser').directive(
    'rcmSwitchUserAdminHorizontal',
    [
        'rcmSwitchUserAdminService',
        function (
            rcmSwitchUserAdminService
        ) {
            return {
                link: rcmSwitchUserAdminService.link,
                scope: rcmSwitchUserAdminService.scope,
                template: '' +
                '<rcm-switch-user-switch-to-user-horizontal' +
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
                '</rcm-switch-user-switch-to-user-horizontal>'
            }
        }
    ]
);

/**
 * rcmSwitchUserSwitchToUser
 */
angular.module('rcmSwitchUser').directive(
    'rcmSwitchUserTooltip',
    function () {
        return {
            scope: {
                propInfoContent: '=content',
                propShow: '=show',
            },
            template: '<div class="switch-user-tooltip alert alert-info" ng-show="propShow" role="alert">    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>    <span class="switch-user-info-content">{{propInfoContent}}</span></div>'
        }
    }
);
