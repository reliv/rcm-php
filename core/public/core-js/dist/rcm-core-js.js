/**
 * rcm is a base library to help with the issues with Angular when Angular is not
 *   in charge of all domain changes.  It is NOT a perfect solution, so use these
 *   only as required.
 *   - helps with exposing Angular methods and properties
 *   - helps with AJAX loading of Angular scripts
 *   - helps with loading Angular modules without using bootstrap
 *   - helps with console.log issues in IE 8 and lower
 *
 */
var RcmCore = function () {

    var self = this;

    self.moduleDepenencies = [];

    self.app = null;

    self.compile;
    self.scope;

    /**
     * config - Exposes a standard way of sharing config data
     * @type {{}}
     */
    self.config = {};

    /**
     * get Config Value
     * @param configKey
     * @param defaultValue
     * @returns {*}
     */
    self.getConfigValue = function (configKey, defaultValue) {

        if (self.config[configKey]) {
            return self.config[configKey]
        }

        return defaultValue;
    };

    /**
     * @param moduleName AngularJS Module name
     * @param ezloadConfig
     * EXAMPLE:
     * {
     *   name: 'e',
     *   files: ['/modules/my/script.js']
     * }
     */
    self.addAngularModule = function (moduleName) {

        if (self.hasModule(moduleName)) {
            return;
        }

        if (!self.app) {
            self.pushModuleName(moduleName);
            return;
        }

        console.error('Module: ' + moduleName + ' registered too late.');
    };

    /**
     *
     * @param moduleConfigs
     * EXAMPLE: [name]: [lazyLoadConfig]
     * {
     *  'myModuleName': {files: ['/modules/my/script.js']}
     * }
     */
    self.addAngularModules = function (moduleConfigs) {

        for (var moduleName in moduleConfigs) {
            self.addAngularModule(moduleName, moduleConfigs[moduleName]);
        }
    };

    /**
     *
     * @param moduleName
     */
    self.pushModuleName = function (moduleName) {

        if (!self.hasModule(moduleName)) {
            self.moduleDepenencies.push(moduleName);
        }
    };

    /**
     *
     * @param moduleName
     * @returns {boolean}
     */
    self.hasModule = function (moduleName) {

        return (self.moduleDepenencies.indexOf(moduleName) > -1);
    };

    /**
     *
     * @param document
     */
    self.init = function (document) {

        var angularModule = angular.module('rcm', self.moduleDepenencies);

        angular.element(document).ready(
            function () {

                angular.bootstrap(
                    document,
                    ['rcm']
                );

                self.app = angularModule;

                self.compile = angular.element(document).injector().get('$compile');

                self.scope = angular.element(document).scope();

                self.rootScope = angular.element(document).injector().get('$rootScope');

                self.rootScope.safeApply = function (fn) {
                    var phase = self.rootScope.$$phase;
                    if (phase == '$apply' || phase == '$digest') {
                        if (fn && (typeof(fn) === 'function')) {
                            fn();
                        }
                    } else {
                        self.rootScope.$apply(fn);
                    }
                };

                self.safeApply = function (scope, fn) {
                    var phase = scope.$root.$$phase;
                    if (phase == '$apply' || phase == '$digest') {
                        if (fn && (typeof(fn) === 'function')) {
                            fn();
                        }
                    } else {
                        scope.$apply(fn);
                    }
                };

                self.angularSafeApply = function (fn) {
                    self.rootScope.safeApply(fn);
                };

                self.angularCompile = function (elm, fn) {

                    console.warn('rcm.angularCompile can cause problems for other angular directives!');

                    var content = elm.contents();

                    angular.element(document).injector().invoke(
                        function ($compile) {
                            var scope = angular.element(content).scope();
                            $compile(content)(scope);
                            self.safeApply(scope, fn);
                            //self.rootScope.safeApply(fn);
                        }
                    );
                };
            }
        );
    };

    /**
     * @deprecated Use RcmPluginModel.getPluginContainerSelector()
     * Or Use RcmAdminPlugin.model.getPluginContainerSelector() AKA: pluginHandler.model.getPluginContainerSelector()
     *
     * From old scripts
     * @param instanceId
     * @returns {string}
     */
    self.getPluginContainerSelector = function (instanceId) {

        return ('[data-rcmPluginInstanceId="' + instanceId + '"] .rcmPluginContainer');
    };

    /**
     * @deprecated Use RcmPluginModel.getElm
     * Or Use RcmAdminPlugin.getElm() AKA: pluginHandler.getElm()
     * From old scripts
     * @param instanceId
     * @returns {*|jQuery|HTMLElement}
     */
    self.getPluginContainer = function (instanceId) {

        return $(self.getPluginContainerSelector(instanceId));
    };

    /**
     * Browser safe console replacement
     */
    self.console = function () {

        var self = this;

        self.log = function (msg) {
        };

        self.info = function (msg) {
        };

        self.warn = function (msg) {
        };

        self.error = function (msg) {
        };

        /* there are more methods, but this covers the basics */
    };

    /**
     * Initialize the console
     */
    self.initConsole = function () {

        if (typeof window.console !== "undefined") {
            self.console = window.console;
        }

        window.console = self.console;
    };

    // construct
    self.initConsole();

    self.init(document);
};

var rcm = new RcmCore();

/**
 * General service to wrap standard API JSON returns from RCM
 *  - Deals with failed codes (code 0 = success)
 *  - Creates standard return on error if no standard API JSON object received
 *  - Deals with loading state
 *    See: ApiParams.loading
 *  - Formats error messages (from rcm input filter) into single strings (optional)
 *    See: ApiParams.prepareErrors
 */
angular.module('rcmApi', [])
    .factory(
        'rcmApiService',
        [
        '$http',
        '$log',
        function ($http, $log) {

            var self = this;

            /**
             * cache
             * @type {{}}
             */
            self.cache = {};

            /**
             * ApiParams
             * @constructor
             */
            self.ApiParams = function () {
                /**
                 * URL of request (can contain parsable params in format {myParam})
                 * @type {string}
                 */
                this.url = '';
                /**
                 * URL Params that will replace parsable params in url
                 * @type {object}
                 */
                this.urlParams = null;
                /**
                 * POST PUT DELETE data
                 * @type {object}
                 */
                this.data = null;
                /**
                 * GET query params
                 * @type {object}
                 */
                this.params = null;
                /**
                 * Prepare errors from input filters if set to true
                 * @type {boolean}
                 */
                this.prepareErrors = false;
                /**
                 * Loading callback, used to track loading state
                 * @param {boolean} loading
                 */
                this.loading = function (loading) {
                };
                /**
                 * Success callback, called if http and API is successful (error code == 0)
                 * @param {object} data
                 */
                this.success = function (data) {
                };
                /**
                 * Error callback, called if http or API is fails (error code > 0)
                 * @param data
                 */
                this.error = function (data) {
                };
            };

            /**
             * ApiData - Format expected from server
             * @constructor
             */
            self.ApiData = function () {
                this.code = null;
                this.message = null;
                this.data = [];
                this.errors = [];
            };

            /**
             * GET
             * @param apiParams
             * @param {bool} cache - if you ask for cache it will try to get it from and set it to the cache
             * @returns {*}
             */
            self.get = function (apiParams, cache) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                if (cache) {
                    if (self.cache[apiParams.url]) {
                        self.apiSuccess(
                            self.cache[apiParams.url],
                            apiParams,
                            'CACHE',
                            null,
                            null
                        );
                        return;
                    }

                    apiParams.cacheId = apiParams.url;
                }

                apiParams.loading(true);

                $http(
                    {
                        method: 'GET',
                        url: apiParams.url,
                        params: apiParams.params // @todo Validate this works for GET query
                    }
                )
                    .success(
                        function (data, status, headers, config) {
                            self.apiSuccess(data, apiParams, status, headers, config)
                        }
                    )
                    .error(
                        function (data, status, headers, config) {
                            self.apiError(data, apiParams, status, headers, config)
                        }
                    );
            };

            /**
             * POST
             * @param apiParams
             */
            self.post = function (apiParams) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'POST',
                        url: apiParams.url,
                        data: apiParams.data
                    }
                )
                    .success(
                        function (data, status, headers, config) {
                            self.apiSuccess(data, apiParams, status, headers, config)
                        }
                    )
                    .error(
                        function (data, status, headers, config) {
                            self.apiError(data, apiParams, status, headers, config)
                        }
                    );
            };

            /**
             * PATCH
             * @param apiParams
             */
            self.patch = function (apiParams) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'PATCH',
                        url: apiParams.url,
                        data: apiParams.data // angular.toJson(data)
                    }
                )
                    .success(
                        function (data, status, headers, config) {
                            self.apiSuccess(data, apiParams, status, headers, config)
                        }
                    )
                    .error(
                        function (data, status, headers, config) {
                            self.apiError(data, apiParams, status, headers, config)
                        }
                    );
            };

            /**
             * PUT
             * @param apiParams
             */
            self.put = function (apiParams) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'PUT',
                        url: apiParams.url,
                        data: apiParams.data
                    }
                )
                    .success(
                        function (data, status, headers, config) {
                            self.apiSuccess(data, apiParams, status, headers, config)
                        }
                    )
                    .error(
                        function (data, status, headers, config) {
                            self.apiError(data, apiParams, status, headers, config)
                        }
                    );
            };

            /**
             * DELETE
             * @param apiParams
             */
            self.del = function (apiParams) {

                apiParams = angular.extend(new self.ApiParams(), apiParams);

                apiParams.url = self.formatUrl(apiParams.url, apiParams.urlParams);

                apiParams.loading(true);

                $http(
                    {
                        method: 'DELETE',
                        url: apiParams.url,
                        data: apiParams.data
                    }
                )
                    .success(
                        function (data, status, headers, config) {
                            self.apiSuccess(data, apiParams, status, headers, config)
                        }
                    )
                    .error(
                        function (data, status, headers, config) {
                            self.apiError(data, apiParams, status, headers, config)
                        }
                    );
            };

            /**
             * Parse URL string and replace {#} with param value by key
             * @param {string} str
             * @param {array} urlParams
             * @returns {string}
             */
            self.formatUrl = function (str, urlParams) {

                if (typeof urlParams !== 'object' || urlParams === null) {
                    return str;
                }

                for (var arg in urlParams) {
                    str = str.replace(
                        RegExp("\\{" + arg + "\\}", "gi"),
                        urlParams[arg]
                    );
                }

                return str;
            };

            /**
             *
             * @param data
             * @param apiParams
             */
            self.apiError = function (data, apiParams, status, headers, config) {
                $log.error(
                    'An API error occured, status: ' + status + ' returned: ',
                    data
                );

                self.prepareErrorData(
                    data,
                    apiParams,
                    function (data) {
                        apiParams.loading(false);
                        apiParams.error(data);
                    },
                    status
                );
            };

            /**
             * apiSuccess
             * @param data
             * @param apiParams
             * @param cacheId
             */
            self.apiSuccess = function (data, apiParams, status, headers, config) {
                // $log.info('An API success: ', data);

                if (data.code > 0 || typeof data !== 'object') {
                    self.prepareErrorData(
                        data,
                        apiParams,
                        function (data) {
                            apiParams.loading(false);
                            apiParams.error(data);
                        },
                        status
                    )
                } else {
                    self.prepareData(
                        data,
                        apiParams,
                        function (data) {
                            if (apiParams.cacheId) {
                                self.cache[apiParams.cacheId] = angular.copy(data);
                            }
                            apiParams.loading(false);
                            apiParams.success(data);
                        }
                    );
                }
            };

            /**
             * prepareErrorData
             * @param data
             * @param apiParams
             * @param callback
             * @param status
             * @returns {ApiData|*}
             */
            self.prepareErrorData = function (data, apiParams, callback, status) {

                if (typeof data !== 'object' || data === null) {
                    data = new self.ApiData();
                }

                if (!data.code) {
                    data.code = status;
                }

                if (!data.message) {
                    data.message = 'An unknown error occured while making request.';
                }

                return self.prepareData(data, apiParams, callback);
            };

            /**
             * prepareData
             * @param data
             * @param apiParams
             * @param callback
             */
            self.prepareData = function (data, apiParams, callback) {

                if (data.errors && apiParams.prepareErrors) {
                    self.prepareErrors(data, callback);
                    return;
                }

                callback(data);
            };

            /**
             * prepareErrors
             * @param data
             * @param callback
             */
            self.prepareErrors = function (data, callback) {

                angular.forEach(
                    data.errors,
                    function (value, key) {
                        if (typeof value === 'object' && value !== null) {
                            angular.forEach(
                                value,
                                function (evalue, ekey) {
                                    data.errors[key] = evalue + ' ';
                                }
                            );
                        }
                    }
                );
                callback(data);
            };

            return self;
        }
        ]
    );


/**
 * Exposes Angular service to global scope for use by other libraries
 * - This is to support jQuery and native JavaScript modules and code
 */
var rcmApi = {
    rcmApiService: null // defined in angular
};

/**
 * Angular injector to get rcmApi Module services
 */
angular.injector(['ng', 'rcmApi']).invoke(
    [
    'rcmApiService',
    function (rcmApiService) {
            rcmApi.rcmApiService = rcmApiService;
    }
    ]
);

(function ($) {
    /**
     * Pops up an alert dialog using Boostrap
     *
     * @param {String} text what to say to user
     * @param {Function} [okCallBack] optional callback for ok button
     * @param {String} [title] optional the title bar text
     */
    $.fn.alert = function (text, okCallBack, title) {


        if (!title) {
            title = 'Alert';
        }

        var message = title;
        if (text) {
            message = '<div class="modal-body"><p>' + text + '</p></div>';
        }

        var config = {
            message: message,
            title: '<h1 class="modal-title">' + title + '</h1>',
            buttons: {
                ok: {
                    label: "Ok",
                    className: "btn-primary",
                    callback: function () {
                    }
                }
            }

        };

        if (typeof okCallBack == 'function') {
            config.buttons.ok.callback = okCallBack;
        }

        bootbox.dialog(config);
    };
    /**
     * Pops up a confirm dialog using Boostrap
     *
     * @param {String} text what we are asking the user to confirm
     * @param {Function} [okCallBack] optional callback for ok button click
     * @param {Function} [cancelCallBack] optional callback for cancel button click
     * @param {String} [title] optional the title bar text
     */
    $.fn.confirm = function (text, okCallBack, cancelCallBack, title) {

        if (!title) {
            title = 'Confirm';
        }

        var message = title;
        if (text) {
            message = '<div class="modal-body"><p>' + text + '</p></div>';
        }

        var config = {
            message: message,
            title: '<h1 class="modal-title">' + title + '</h1>',
            buttons: {
                cancel: {
                    label: "Cancel",
                    className: "btn-default",
                    callback: function () {
                    }
                },
                ok: {
                    label: "Ok",
                    className: "btn-primary",
                    callback: function () {
                    }
                }
            }

        };

        if (typeof cancelCallBack === 'function') {
            config.buttons.cancel.callback = cancelCallBack;
        }

        if (typeof okCallBack == 'function') {
            config.buttons.ok.callback = okCallBack;
        }

        bootbox.dialog(config);
    };
})(jQuery);
/**
 * This ensures forms can only be submitted once and shows that it is loading via css
 */
$().ready(function () {
    $('body').delegate(
        'form',
        'submit',
        function () {
            var form = $(this);

            // Ignore form without action, like angular forms
            if (!form.attr('action')) {
                return true;
            }

            if (form.hasClass('processing')) {
                return false;
            }

            form.addClass('processing');
            return true;
        }
    );
});
/**
 * @deprecated
 * rcmEdit.adminPopoutWindow replacement
 * @param pagePath
 * @param height
 * @param width
 * @param title
 * @param windowName
 * @param data
 * @param successCallback
 * @param language
 * @returns {*|jQuery|HTMLElement}
 * @constructor
 */
RcmPopoutWindow = function (
    pagePath,
    height,
    width,
    title,
    windowName,
    data,
    successCallback,
    language
) {

    if (windowName == undefined || windowName == null || windowName == '') {
        windowName = 'rcmAdminPagePopoutWindow'
    }

    if (!language) {
        language = '';
    } else {
        language = '/' + language;
    }

    $('body').find("#" + windowName).remove();
    $('body').append('<div id="' + windowName + '"></div>');

    var popoutWidowDiv = $("#" + windowName);

    $(popoutWidowDiv).load(pagePath + language, data, function (response, status, xhr) {

        if (status == "error") {
            var msg = "Sorry but there was an error: ";
            $(popoutWidowDiv).html(msg + xhr.status + " " + xhr.statusText);
        }

        if (successCallback && typeof successCallback === 'function') {
            successCallback.call(popoutWidowDiv);
        }
    });

    $(popoutWidowDiv).dialog(
        {
            title: title,
            height: height,
            width: width,
            open: function (event, ui) {
                $('.ui-dialog').css('z-index',3000);
                $('.ui-widget-overlay').css('z-index',3000);
            }
        }
    );

    return popoutWidowDiv;
};