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
                    // @todo console.warn('Tried to build the same RcmHtmlEditor more than once for editor id: ' + id);
                    return;
                }

                rcmHtmlEditorService.editors[id] = new RcmHtmlEditor(id, rcmHtmlEditorService);

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