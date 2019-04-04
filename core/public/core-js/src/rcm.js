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
