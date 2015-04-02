/**
 * rcm is a base library to help with the issues with Angular when Angular is not
 *   in charge of all domain changes.  It is NOT a perfect solution, so use these
 *   only as required.
 *   - helps with exposing Angular methods and properties
 *   - helps with AJAX loading of Angular scripts
 *   - helps with loading Angular modules without using bootstrap
 *   - helps with console.log issues in IE 8 and lower
 *
 * Requires oc-lazy-load due to late loading of resources
 * - in modules that are loaded using AJAX, include the tag per the doc
 *   at https://github.com/ocombe/ocLazyLoad
 * - Including modules with dependencies VIA AJAX using ocLazyLoad is NOT supported
 */
var RcmCore = function () {

    var self = this;

    self.moduleDepenencies = ['oc.lazyLoad'];

    self.app = null;

    self.ocLazyLoad;
    self.compile;
    self.scope;

    /**
     * @param moduleName AngularJS Module name
     * @param ezloadConfig
     * EXAMPLE:
     * {
     *   name: 'e',
     *   files: ['/modules/my/script.js']
     * }
     */
    self.addAngularModule = function (moduleName, lazyloadConfig) {

        if (self.hasModule(moduleName)) {

            return;
        }

        if (self.ocLazyLoad) {

            if (!lazyloadConfig) {
                lazyloadConfig = {};
            }

            lazyloadConfig.name = moduleName;

            self.ocLazyLoad.load(lazyloadConfig)
                .then(
                function () {
                    self.pushModuleName(moduleName);

                    self.rootScope.safeApply();
                }
            );

            return;
        }

        if (!self.app) {

            self.pushModuleName(moduleName);
        }
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

        // @todo check oc-lazy-loader too
        if (self.moduleDepenencies.indexOf(moduleName) < 0) {
            return false;
        }

        return true;
    };

    /**
     *
     * @param document
     */
    self.init = function (document) {

        var angularModule = angular.module('rcm', self.moduleDepenencies)
            .config(
            [
                '$ocLazyLoadProvider',
                function ($ocLazyLoadProvider) {
                    $ocLazyLoadProvider.config(
                        {
                            //asyncLoader: requirejs,
                            debug: true,
                            events: true,
                            loadedModules: ['rcm']
                        }
                    );
                }
            ]
        );

        angular.element(document).ready(
            function () {

                angular.bootstrap(
                    document,
                    ['rcm']
                );

                self.app = angularModule;

                self.ocLazyLoad = angular.element(document).injector().get('$ocLazyLoad');

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
                /*
                 self.scope.$on('ocLazyLoad.moduleLoaded', function (e, module) {
                 console.log('module loaded', module);
                 });

                 self.scope.$on('ocLazyLoad.componentLoaded', function (e, module) {
                 console.log('componentLoaded loaded', module);
                 });

                 self.scope.$on('ocLazyLoad.fileLoaded', function (e, module) {
                 console.log('fileLoaded loaded', module);
                 });
                 */
            }
        );
    };

    /**
     * @deprecated
     * From old scripts
     * @param instanceId
     * @returns {string}
     */
    self.getPluginContainerSelector = function (instanceId) {

        return ('[data-rcmPluginInstanceId="' + instanceId + '"] .rcmPluginContainer');
    };

    /**
     * @deprecated
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
    };

    /**
     * Initialize the console
     */
    self.initConsole = function () {
        if (window.console) {

            self.console = window.console;
        } else {

            /* keep older browsers from blowing up */
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

            window.console = self.console;
        }
    };

    // construct
    self.initConsole();
    self.init(document);
};

var rcm = new RcmCore();