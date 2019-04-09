/**
 * rcmHtmlEditorGuid
 * @type {{generate: Function}}
 */
var rcmHtmlEditorGuid = {

    generate: function () {

        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }

        var guid = function () {
            return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                s4() + '-' + s4() + s4() + s4();
        };

        return guid();
    }
};


/**
 * RcmHtmlEditorEventManager - Can be replaced with RCM event manager
 * @type {{on: Function, trigger: Function}}
 */
var RcmHtmlEditorEventManager = function () {

    var self = this;

    var events = {};

    self.on = function (event, method) {

        if (!events[event]) {
            events[event] = [];
        }

        events[event].push(method);
    };

    self.trigger = function (event, args) {

        if (events[event]) {
            jQuery.each(
                events[event],
                function (index, value) {
                    value(args);
                }
            );
        }
    };
};

/**
 * RcmHtmlEditorService class
 * @constructor
 */
var RcmHtmlEditorService = function (eventManager) {

    var self = this;

    self.eventManager = eventManager;

    self.isEditing = false;
    self.toolbarLoading = false;
    self.editorsLoading = [];
    self.editors = {};
    self.hasEditors = false;
    // options
    self.showFixedToolbar = false;
    self.fixedToolbarToggle = false;

    /**
     * updateState
     * @param onUpdateComplete
     */
    self.updateState = function (onUpdateComplete) {

        var hasEditors = false;

        for (var id in self.editors) {

            if (self.editors[id].hasEditorInstance()) {

                hasEditors = true;
            }
        }

        self.hasEditors = hasEditors;

        if (typeof onUpdateComplete === 'function') {

            onUpdateComplete(self);
        }
    };

    /**
     * deleteEditor
     * @param id
     */
    self.deleteEditor = function (id) {

        delete self.editors[id];

        // In case delete did not work
        if (self.editors[id]) {

            self.editors[id] = undefined;
        }
    };

    /**
     * getEditor
     * @param id
     * @returns {*}
     */
    self.getEditor = function (id) {

        if (self.editors[id]) {

            return self.editors[id];
        }

        return null;
    };

    /**
     * hasEditorInstance
     * @param id
     * @returns {*}
     */
    self.hasEditorInstance = function (id) {

        if (self.editors[id]) {

            return self.editors[id].hasEditorInstance();
        }

        return false;
    };

    /**
     * hasEditorInstance
     * @param editorId
     * @param loading
     * @param msg
     */
    self.loading = function (editorId, loading, msg) {

        if (loading) {

            var firstLoading = false;

            if (self.editorsLoading.length == 0) {

                firstLoading = true;
            }

            if (self.editorsLoading.indexOf(editorId) < 0) {
                self.editorsLoading.push(editorId);
                self.toolbarLoading = (self.editorsLoading.length > 0);

                if (firstLoading) {

                    self.eventManager.trigger(
                        'rcmHtmlEditorService.loading.start',
                        {
                            editorId: editorId,
                            loading: self.editorsLoading
                        }
                    );
                }

                self.eventManager.trigger(
                    'rcmHtmlEditorService.loading.change',
                    {
                        editorId: editorId,
                        loading: self.editorsLoading,
                        amount: (1 / (self.editorsLoading.length + 1)) // is not the correct calc, but will work
                    }
                );
            }

        } else {

            if (self.editorsLoading.indexOf(editorId) > -1) {
                self.editorsLoading.splice(
                    self.editorsLoading.indexOf(editorId),
                    1
                );
                self.toolbarLoading = (self.editorsLoading.length > 0);

                self.eventManager.trigger(
                    'rcmHtmlEditorService.loading.change',
                    {
                        editorId: editorId,
                        loading: self.editorsLoading,
                        amount: (1 / (self.editorsLoading.length + 1)) // is not the correct calc, but will work
                    }
                );

                if (!self.toolbarLoading) {

                    self.eventManager.trigger(
                        'rcmHtmlEditorService.loading.end',
                        {
                            editorId: editorId,
                            loading: self.editorsLoading
                        }
                    );
                }
            }
        }
    };

    /**
     * eventManager.on RcmHtmlEditor.onInit
     */
    self.eventManager.on(
        'RcmHtmlEditor.onInit',
        function (args) {

            self.loading(
                args.rcmHtmlEditor.id,
                false,
                'rcmHtmlEditor: '
            );

            self.updateState(
                function () {
                    // will show default toolbar on init
                    if (args.rcmHtmlEditor.settings.fixed_toolbar) {

                        self.showFixedToolbar = true;
                    }
                }
            );
        }
    );

    /**
     * eventManager.on RcmHtmlEditor.onDestroy
     */
    self.eventManager.on(
        'RcmHtmlEditor.onDestroy',
        function (rcmHtmlEditor) {

            self.loading(
                rcmHtmlEditor.id,
                false,
                'rcmHtmlEditor.onDestroy: '
            );

            self.updateState(
                function () {
                    self.deleteEditor(rcmHtmlEditor.id);
                }
            );
        }
    );
};

/**
 * Angular JS module used to shoe HTML editor and toolbar on a page
 * @require:
 *  AngularJS
 *  TinyMce
 */
angular.module('RcmHtmlEditor', [])

    .factory(
        'rcmHtmlEditorConfig',
        function () {

            return rcmHtmlEditorConfig;
        }
    )
    .factory(
        'rcmHtmlEditorEventManager',
        [
            function () {
                return new RcmHtmlEditorEventManager();
            }
        ]
    )
    .factory(
        'rcmHtmlEditorService',
        [
            'rcmHtmlEditorEventManager',
            function (rcmHtmlEditorEventManager) {

                return new RcmHtmlEditorService(rcmHtmlEditorEventManager);
            }
        ]
    )
    .factory(
        'htmlEditorOptions',
        [
            'rcmHtmlEditorConfig',
            function (rcmHtmlEditorConfig) {

                return new RcmHtmlEditorOptions(rcmHtmlEditorConfig);
            }
        ]
    )
    .factory(
        'guid',
        [
            function () {

                return rcmHtmlEditorGuid.generate;
            }
        ]
    )
    .factory(
        'rcmHtmlEditorFactory',
        [
            'RcmHtmlEditor',
            'rcmHtmlEditorService',
            function (RcmHtmlEditor, rcmHtmlEditorService) {

                var self = this;

                self.build = function (id, scope, elm, attrs, ngModel, settings) {

                    if (typeof rcmHtmlEditorService.editors[id] == 'object') {
                        // console.warn(
                        //     'Tried to build the same RcmHtmlEditor more than once for editor id '
                        //     + id +
                        //     ' This subsequent build request will be ignored'
                        // );
                        return;
                    }

                    rcmHtmlEditorService.editors[id] = new RcmHtmlEditor(
                        id,
                        rcmHtmlEditorService
                    );

                    // this is to hide the default toolbar before init
                    rcmHtmlEditorService.loading(id, true, 'rcmHtmlEditorInit');

                    rcmHtmlEditorService.editors[id].init(
                        scope,
                        elm,
                        attrs,
                        ngModel,
                        settings
                    );

                };

                self.destroy = function (id) {
                    console.log('destroy',id);

                    if (typeof rcmHtmlEditorService.editors[id] == 'object') {

                        rcmHtmlEditorService.editors[id].destroy();
                    }
                };

                return self;
            }
        ]
    )
    .factory(
        'RcmHtmlEditor',
        [
            'rcmHtmlEditorService',
            function (rcmHtmlEditorService) {

                return RcmHtmlEditor;
            }
        ]
    )
    .factory(
        'rcmHtmlEditorInit',
        [
            'guid',
            'htmlEditorOptions',
            'rcmHtmlEditorService',
            'rcmHtmlEditorFactory',
            function (guid, htmlEditorOptions, rcmHtmlEditorService, rcmHtmlEditorFactory) {

                return function (scope, elm, attrs, ngModel, config) {

                    // generate an ID if not present
                    if (!attrs.id) {
                        attrs.$set('id', guid());
                    }

                    var id = attrs.id;

                    // get settings from attr or config
                    var settings = htmlEditorOptions.buildHtmlOptions(
                        id,
                        scope,
                        attrs,
                        config
                    );

                    rcmHtmlEditorFactory.build(id, scope, elm, attrs, ngModel, settings);
                }
            }
        ]
    )
    .factory(
        'rcmHtmlEditorDestroy',
        [
            'rcmHtmlEditorService',
            'rcmHtmlEditorFactory',
            function (rcmHtmlEditorService, rcmHtmlEditorFactory) {

                return function (id) {

                    if (id) {
                        rcmHtmlEditorFactory.destroy(id);
                    }
                }
            }
        ]
    )
    /*
     * rcmHtmlEdit - rcm-html-edit
     *
     * Attributes options:
     *  html-editor-options
     *  html-editor-type
     *  html-editor-attached-toolbar
     *  html-editor-base-url
     *  html-editor-size
     *  id
     */
    .directive(
        'rcmHtmlEdit',
        [
            'rcmHtmlEditorInit',
            function (rcmHtmlEditorInit) {

                var self = this;

                self.compile = function (tElm, tAttr) {
                    return function (scope, elm, attrs, ngModel, config) {
                        rcmHtmlEditorInit(scope, elm, attrs, ngModel, config);
                    }
                };
                return {
                    compile: self.compile,
                    priority: 10,
                    require: '?ngModel',
                    restrict: 'AE'
                }
            }
        ]
    )
    /*
     * rcmHtmlEditOnClick - rcm-html-edit-on-click
     *
     * Extends rcmHtmlEdit, looks for and element id (if passed)
     * Then searches the parent node for a target element to add a click too
     * By default is uses the element that the directive is attached to
     *
     * Attributes options:
     *  html-editor-options
     *  html-editor-type
     *  html-editor-attached-toolbar
     *  html-editor-base-url
     *  html-editor-size
     *  id
     */
    .directive(
        'rcmHtmlEditOnClick',
        [
            '$parse', 'rcmHtmlEditorInit',
            function ($parse, rcmHtmlEditorInit) {

                var self = this;

                self.compile = function (tElm, tAttr) {
                    return function (scope, elm, attrs, ngModel, config) {

                        var clickElm = elm;

                        if (attrs.rcmHtmlEditOnClick) {

                            var selector = $parse(attrs.rcmHtmlEditOnClick)(scope);

                            var parentElm = elm.parent();

                            var newElm = parentElm.find(selector).first();

                            if (newElm) {
                                clickElm = newElm;
                            }
                        }

                        clickElm.click(
                            function () {

                                rcmHtmlEditorInit(
                                    scope,
                                    elm,
                                    attrs,
                                    ngModel,
                                    config
                                );
                            }
                        );
                    }
                };
                return {
                    compile: self.compile,
                    priority: 10,
                    require: '?ngModel',
                    restrict: 'AE'
                }
            }
        ]
    )
    /*
     * htmlEditorToolbar - html-editor-toolbar
     * Example:
     * <div html-editor-toolbar></div>
     */
    .directive(
        'htmlEditorToolbar',
        [
            'rcmHtmlEditorService',
            function (rcmHtmlEditorService) {

                var toolbar = new RcmHtmlEditorToolbar(rcmHtmlEditorService);

                var directive = toolbar.getDirective();

                directive.restrict = 'AE';

                return directive;
            }
        ]
    );

if (typeof rcm !== 'undefined') {
    rcm.addAngularModule(
        'RcmHtmlEditor'
    );
}
