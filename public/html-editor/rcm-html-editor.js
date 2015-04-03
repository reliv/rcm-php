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

        var self = this;

        self.language = 'en';
        self.baseUrl = "/"; //"<?php echo $baseUrl; ?>";
        self.fixed_toolbar_container = '#externalToolbarWrapper';
        self.toolbar_container_prefix = '#htmlEditorToolbar-';

        self.htmlEditorOptions = {
            defaults: {
                link_list: "/rcm-page-search/title?format=tinyMceLinkList",
                relative_urls: false,
                optionsName: 'defaults',
                force_br_newlines: false,
                force_p_newlines: true,
                forced_root_block: '',
                paste_as_text: true,

                inline: true,
                encoding: "raw",
                fixed_toolbar_container: self.fixed_toolbar_container,
                language: self.language,

                menubar: false,
                plugins: "anchor, charmap, code, hr, image, linkwithjqueryautocomplete, paste, table, textcolor, colorpicker, elfinder",
                document_base_url: self.baseUrl,
                statusbar: false,

                style_formats_merge: true,
                style_formats: [
                    {
                        title: "Image",
                        items: [
                            {
                                title: 'Align Left',
                                selector: 'img',
                                styles: {
                                    'float': 'left',
                                    'margin': '0 1em .5em 0'
                                }
                            },
                            {
                                title: 'Align Right',
                                selector: 'img',
                                styles: {
                                    'float': 'right',
                                    'margin': '0 0 .5em 1em'
                                }
                            }
                        ]
                    }
                ],

                image_advtab: true,

                toolbar: [
                    "code | undo redo | styleselect | forecolor | " +
                    "bold italic underline strikethrough subscript superscript removeformat | " +
                    "alignleft aligncenter alignright alignjustify | " +
                    "bullist numlist outdent indent | cut copy pastetext | " +
                    "image table hr charmap | link unlink anchor | removeformat"
                ]
            },
            text: {
                link_list: "/rcm-page-search/title?format=tinyMceLinkList",
                relative_urls: false,
                optionsName: 'text',
                force_br_newlines: false,
                force_p_newlines: true,
                forced_root_block: '',
                paste_as_text: true,

                inline: true,
                encoding: "raw",
                fixed_toolbar_container: self.fixed_toolbar_container,
                language: self.language,

                menubar: false,
                plugins: "anchor, charmap, code, hr, image, linkwithjqueryautocomplete, paste, table, textcolor, colorpicker, elfinder",
                document_base_url: self.baseUrl,
                statusbar: false,

                image_advtab: true,

                toolbar: [
                    "code | undo redo | forecolor | " +
                    "bold italic underline strikethrough subscript superscript removeformat | " +
                    "outdent indent | cut copy pastetext | " +
                    "image charmap | link unlink anchor | removeformat"
                ]
            },
            simpleText: {
                link_list: "/rcm-page-search/title?format=tinyMceLinkList",
                relative_urls: false,
                optionsName: 'simpleText',
                force_br_newlines: false,
                force_p_newlines: true,
                forced_root_block: '',
                paste_as_text: true,

                inline: true,
                encoding: "raw",
                fixed_toolbar_container: self.fixed_toolbar_container,
                language: self.language,

                menubar: false,
                plugins: "anchor, charmap, code, hr, image, linkwithjqueryautocomplete, paste, table, elfinder",
                document_base_url: self.baseUrl,
                statusbar: false,

                toolbar: [
                    "code | " +
                    "bold italic underline strikethrough subscript superscript removeformat | " +
                    "link unlink anchor | removeformat"
                ]
            }
        };

        return self;
    }
)
    .factory(
    'rcmHtmlEditorService',
    [
        function () {

            var RcmHtmlEditorService = function () {

                var self = this;
                self.events = {};
                self.eventManager = {

                    on: function (event, method) {

                        if (!self.events[event]) {
                            self.events[event] = [];
                        }

                        self.events[event].push(method);
                    },

                    trigger: function (event, args) {

                        if (self.events[event]) {
                            jQuery.each(
                                self.events[event],
                                function (index, value) {
                                    value(args);
                                }
                            );
                        }
                    }
                };

                self.isEditing = false;
                self.toolbarLoading = false;
                self.editorsLoading = [];
                self.editors = {};
                self.hasEditors = false;
                // options
                self.showFixedToolbar = false;
                self.fixedToolbarToggle = false;
                // TinyMce CSS path
                self.fixedToolbarCss = null;

                self.updateState = function (onUpdateComplete) {

                    var hasEditors = false;

                    for (var id in self.editors) {

                        if (self.editors[id].hasTinyMce()) {

                            hasEditors = true;
                        }
                    }

                    self.hasEditors = hasEditors;

                    if (typeof onUpdateComplete === 'function') {

                        onUpdateComplete(self);
                    }
                };

                self.deleteEditor = function (id) {

                    delete self.editors[id];
                };

                self.hasTinyMce = function (id) {

                    if (self.editors[id]) {

                        return self.editors[id].hasTinyMce();
                    }

                    return false;
                };

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
                        }

                    } else {

                        if (self.editorsLoading.indexOf(editorId) > -1) {
                            self.editorsLoading.splice(
                                self.editorsLoading.indexOf(editorId),
                                1
                            );
                            self.toolbarLoading = (self.editorsLoading.length > 0);

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

            var rcmHtmlEditorService = new RcmHtmlEditorService();

            return rcmHtmlEditorService;
        }
    ]
)
    .factory(
    'htmlEditorOptions',
    [
        'rcmHtmlEditorConfig',
        function (rcmHtmlEditorConfig) {

            var self = this;

            // get options based on the config settings
            self.getHtmlOptions = function (type) {

                if (!type) {

                    return rcmHtmlEditorConfig.htmlEditorOptions.defaults;
                }

                if (rcmHtmlEditorConfig.htmlEditorOptions[type]) {

                    return rcmHtmlEditorConfig.htmlEditorOptions[type]
                }

                return rcmHtmlEditorConfig.htmlEditorOptions.defaults;
            };


            // build settings based on the attrs and config
            self.buildHtmlOptions = function (id, scope, attrs, config) {

                var options = {};
                var settings = {};

                if (typeof config !== 'object') {

                    config = {};
                }

                if (attrs.htmlEditorOptions) {
                    try {
                        var attrConfig = scope.$eval(attrs.htmlEditorOptions);
                    } catch (e) {

                    }

                    if (typeof attrConfig === 'object') {

                        config = angular.extend(attrConfig, config);
                    }
                }

                options = angular.copy(self.getHtmlOptions(attrs.htmlEditorType));

                settings = angular.extend(options, config); // copy(options);

                settings.mode = 'exact';
                settings.elements = id;
                settings.fixed_toolbar = true;

                // set some overrides based on attr html-editor-attached-toolbar
                if (typeof attrs.htmlEditorAttachedToolbar !== 'undefined') {

                    settings.inline = true;
                    settings.fixed_toolbar_container = rcmHtmlEditorConfig.toolbar_container_prefix + id;
                    settings.fixed_toolbar = false;

                    // @todo NOT SUPPORTED: attr html-editor-show-hide-toolbar
                    //if (typeof attrs.htmlEditorShowHideToolbar !== 'undefined') {
                    //    settings.show_hide_toolbar = true;
                    //}
                }

                // set some overrides based on attr html-editor-base-url
                if (attrs.htmlEditorBaseUrl) {
                    settings.baseUrl = attrs.htmlEditorBaseUrl;
                }

                if (attrs.htmlEditorSize) {
                    settings.toolbar_items_size = attrs.htmlEditorSize; // 'small'
                }

                return settings
            };

            return self;
        }
    ]
)
    .factory(
    'guid',
    [
        function () {

            var guid = (function () {
                function s4() {
                    return Math.floor((1 + Math.random()) * 0x10000)
                        .toString(16)
                        .substring(1);
                }

                return function () {
                    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
                        s4() + '-' + s4() + s4() + s4();
                };
            })();

            return guid;
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

                rcmHtmlEditorService.editors[id] = new RcmHtmlEditor(id);

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

            var RcmHtmlEditor = function (id) {
                var self = this;
                self.id = id;
                self.scope;
                self.elm;
                self.attrs;
                self.ngModel;

                self.settings = {};
                self.tinyInstance;
                self.tagName = "";
                self.initTimeout;

                self.init = function (scope, elm, attrs, ngModel, settings) {

                    self.scope = scope;
                    self.elm = elm;
                    self.ngModel = ngModel;
                    self.settings = settings;
                    self.attrs = attrs;

                    // is dom has changed, init may not complete
                    self.initTimeout = setTimeout(
                        function () {
                            self.onInitTimout();
                        },
                        2000
                    );

                    self.buildEditor();
                };

                /**
                 * onInit
                 */
                self.onInit = function (ed) {

                    rcmHtmlEditorService.eventManager.trigger(
                        'RcmHtmlEditor.onInit',
                        {
                            rcmHtmlEditor: self,
                            tinyMceEditorInstance: ed
                        }
                    );
                    clearTimeout(self.initTimeout);
                };

                /**
                 * onInitTimout
                 */
                self.onInitTimout = function () {

                    rcmHtmlEditorService.eventManager.trigger(
                        'RcmHtmlEditor.onInitTimeout',
                        self
                    );
                    //console.warn('RcmHtmlEditor: ' + id + ' failed to init.');
                    self.destroy();
                };

                /**
                 * onDestroy
                 */
                self.onDestroy = function () {

                    rcmHtmlEditorService.eventManager.trigger(
                        'RcmHtmlEditor.onDestroy',
                        self
                    );
                    clearTimeout(self.initTimeout);
                };

                /**
                 * onApply
                 */
                self.onApply = function () {

                    rcmHtmlEditorService.eventManager.trigger(
                        'RcmHtmlEditor.onApply',
                        self
                    );
                };

                /**
                 * getTagName
                 * @returns {string}
                 */
                self.getTagName = function () {

                    if ((self.elm && self.elm[0]) && !self.tagName) {
                        self.tagName = self.elm[0].tagName;
                    }

                    return self.tagName;
                };

                /**
                 * getElmValue
                 * @returns {*}
                 */
                self.getElmValue = function () {

                    if (self.isFormControl()) {

                        return self.elm.val();
                    }

                    return self.elm.html();
                };

                /**
                 * isFormControl
                 * @returns {boolean}
                 */
                self.isFormControl = function () {

                    if (self.getTagName() == "TEXTAREA") {

                        return true;
                    }

                    return false;
                };

                /**
                 * updateView
                 */
                self.updateView = function () {

                    if (self.ngModel) {
                        self.ngModel.$setViewValue(self.tinyInstance.getContent());
                    }

                    self.apply();
                };

                /**
                 * apply
                 */
                self.apply = function () {

                    if (!self.scope.$root.$$phase) {
                        self.scope.$apply();
                    }

                    self.onApply();
                };

                /**
                 * buildEditor
                 */
                self.buildEditor = function () {

                    self.settings.setup = function (ed) {
                        var args;
                        //
                        //ed.on('click', function (args) {
                        //
                        //    if (self.elm.click) {
                        //        self.elm.click();
                        //    }
                        //});
                        ed.on(
                            'init', function (args) {

                                if (self.ngModel) {
                                    self.ngModel.$render();
                                    self.ngModel.$setPristine();
                                }

                                self.onInit(ed);
                                self.apply();
                            }
                        );
                        //
                        ed.on(
                            'postrender', function (args) {
                            }
                        );
                        // Update model on button click
                        ed.on(
                            'ExecCommand', function (e) {

                                ed.save();
                                self.updateView();
                            }
                        );
                        // Update model on keypress
                        ed.on(
                            'KeyUp', function (e) {

                                ed.save();
                                self.updateView();
                            }
                        );
                        // Update model on change, i.e. copy/pasted text, plugins altering content
                        ed.on(
                            'SetContent', function (e) {

                                if (!e.initial) {

                                    if (self.ngModel) {

                                        if (self.ngModel.$viewValue !== e.content) {
                                            ed.save();
                                            self.updateView();
                                        }
                                    } else {

                                        ed.save();
                                        self.updateView();
                                    }
                                }
                            }
                        );
                        //
                        ed.on(
                            'blur', function (e) {

                                rcmHtmlEditorService.isEditing = false;

                                if (self.elm.blur) {
                                    //causing some issues //
                                    //self.elm.blur();
                                }
                                self.updateView();
                            }
                        );
                        //
                        ed.on(
                            'focus', function (e) {
                                rcmHtmlEditorService.isEditing = true;

                                if (self.elm.focus) {
                                    //causing some issues //
                                    //self.elm.focus();
                                }
                                self.updateView();
                            }
                        );
                        // Update model when an object has been resized (table, image)
                        ed.on(
                            'ObjectResized', function (e) {

                                ed.save();
                                self.updateView();
                            }
                        );
                        // This might be needed if setup can be passed in
                        //if (settings) {
                        //    settings(ed);
                        //}
                    };

                    setTimeout(
                        function () {

                            tinymce.init(self.settings);
                        }
                    );

                    if (self.ngModel) {

                        self.ngModel.$render = function () {

                            if (!self.tinyInstance) {
                                self.tinyInstance = tinymce.get(self.id);
                            }
                            if (self.tinyInstance) {
                                self.tinyInstance.setContent(self.ngModel.$viewValue || self.getElmValue());
                            } else {
                                // self.destroy(null, 'tinyInstance not found')
                            }
                        };
                    }

                    self.elm.on(
                        '$destroy', function () {

                            self.destroy();
                        }
                    );

                    self.scope.$on(
                        '$destroy', function () {

                            // this can cause issues with editors that are on the page dynamically
                            // might be caused by element being destroyed and scope is part on elm.
                            // self.destroy();
                        }
                    );
                };

                /**
                 * destroy
                 */
                self.destroy = function () {

                    if (!self.tinyInstance) {
                        self.tinyInstance = tinymce.get(self.id);
                    }
                    if (self.tinyInstance) {
                        self.tinyInstance.remove();
                    }

                    self.onDestroy();

                    self.apply();
                };

                /**
                 * hasTinyMce
                 * @returns {boolean}
                 */
                self.hasTinyMce = function () {

                    var tinyInstance = tinymce.get(self.id);

                    if (tinyInstance) {
                        return true;
                    }

                    return false;
                };
            };

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
     *  html-editor-attachedToolbar
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
                priority: 10,
                require: '?ngModel',
                compile: self.compile
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
     *  html-editor-attachedToolbar
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
                priority: 10,
                require: '?ngModel',
                compile: self.compile
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

            var self = this;

            var loadSkin = function (skin, loadedCallback, errorCallback) {
                var skinUrl = tinymce.baseURL + '/skins/' + skin;

                var skinUiCss = '';
                // Load special skin for IE7
                // TODO: Remove this when we drop IE7 support
                if (tinymce.Env.documentMode <= 7) {
                    skinUiCss = skinUrl + '/skin.ie7.min.css';
                } else {
                    skinUiCss = skinUrl + '/skin.min.css';
                }

                // Load content.min.css or content.inline.min.css + (editor.inline ? '.inline' : '')
                //editor.contentCSS.push(skinUrl + '/content' + '.min.css');
                tinymce.DOM.styleSheetLoader.load(
                    skinUiCss,
                    loadedCallback,
                    errorCallback
                );
            };

            var link = '';

            self.compile = function (tElm, tAttr) {

                rcmHtmlEditorService.fixedToolbarToggle = (tAttr.htmlEditorToolbarToggle == 'true');

                // fixedToolbarToggle requires TinyMCE CSS to be loaded on the page or it will not be displayed correctly
                if (!rcmHtmlEditorService.fixedToolbarToggle) {

                    var skin = (tAttr.htmlEditorToolbarDefaultSkin) ? tAttr.htmlEditorToolbarDefaultSkin : 'lightgray';

                    rcmHtmlEditorService.fixedToolbarDefaultSkin = skin;

                    var originalStyle = tElm.attr('style');

                    if (typeof originalStyle === 'undefined') {
                        originalStyle = '';
                    }

                    tElm.attr('style', 'display: none;');

                    loadSkin(
                        rcmHtmlEditorService.fixedToolbarDefaultSkin,
                        function () {
                            tElm.attr('style', originalStyle);
                        },
                        function () {
                            tElm.attr('style', originalStyle);
                        }
                    );
                }

                return function (scope, element, attrs, htmlEditorState) {

                    scope.rcmHtmlEditorService = rcmHtmlEditorService;
                }
            };

            self.restrict = 'A';
            //self.templateUrl = '/modules/rcm/html-editor/rcm-html-editor-debug.html';
            self.template = '<div class=htmlEditorToolbar ng-cloak><div class=loading ng-show=rcmHtmlEditorService.toolbarLoading>Loading...</div><div ng-hide=rcmHtmlEditorService.toolbarLoading><div class=mce-fake ng-show="(rcmHtmlEditorService.showFixedToolbar || !rcmHtmlEditorService.fixedToolbarToggle) && !rcmHtmlEditorService.isEditing"><div class="mce-tinymce mce-tinymce-inline mce-container mce-panel" role=presentation><div class="mce-container-body mce-abs-layout"><div class="mce-toolbar-grp mce-container mce-panel mce-first mce-last"><div class="mce-container-body mce-stack-layout"><div class="mce-container mce-toolbar mce-first mce-last mce-stack-layout-item"><div class="mce-container-body mce-flow-layout"><div id=mcefake_33 class="mce-container mce-first mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_33-body><div id=mcefake_0 class="mce-widget mce-btn mce-disabled mce-first mce-last" tabindex=-1 aria-labelledby=mcefake_0 role=button aria-label="Source code"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-code"></i></button></div></div></div><div id=mcefake_34 class="mce-container mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_34-body><div id=mcefake_1 class="mce-widget mce-btn mce-disabled mce-first" tabindex=-1 aria-labelledby=mcefake_1 role=button aria-label=Undo aria-disabled=true><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-undo"></i></button></div><div id=mcefake_2 class="mce-widget mce-btn mce-disabled mce-last" tabindex=-1 aria-labelledby=mcefake_2 role=button aria-label=Redo aria-disabled=true><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-redo"></i></button></div></div></div><div id=mcefake_35 class="mce-container mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_35-body><div id=mcefake_3 class="mce-widget mce-btn mce-disabled mce-menubtn mce-first mce-last" tabindex=-1 aria-labelledby=mcefake_3 role=button aria-haspopup=true><button id=mcefake_3-open role=presentation type=button tabindex=-1><span>Formats</span> <i class=mce-caret></i></button></div></div></div><div id=mcefake_36 class="mce-container mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_36-body><div id=mcefake_4 class="mce-widget mce-btn mce-disabled mce-colorbutton mce-first mce-last" role=button tabindex=-1 aria-haspopup=true aria-label="Text color"><button role=presentation hidefocus=1 type=button tabindex=-1><i class="mce-ico mce-i-forecolor"></i> <span id=mcefake_4-preview class=mce-preview></span></button> <button type=button class=mce-open hidefocus=1 tabindex=-1><i class=mce-caret></i></button></div></div></div><div id=mcefake_37 class="mce-container mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_37-body><div id=mcefake_5 class="mce-widget mce-btn mce-disabled mce-first" tabindex=-1 aria-labelledby=mcefake_5 role=button aria-label=Bold aria-pressed=true><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-bold"></i></button></div><div id=mcefake_6 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_6 role=button aria-label=Italic><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-italic"></i></button></div><div id=mcefake_7 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_7 role=button aria-label=Underline><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-underline"></i></button></div><div id=mcefake_8 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_8 role=button aria-label=Strikethrough><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-strikethrough"></i></button></div><div id=mcefake_9 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_9 role=button aria-label=Subscript><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-subscript"></i></button></div><div id=mcefake_10 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_10 role=button aria-label=Superscript><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-superscript"></i></button></div><div id=mcefake_11 class="mce-widget mce-btn mce-disabled mce-last" tabindex=-1 aria-labelledby=mcefake_11 role=button aria-label="Clear formatting"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-removeformat"></i></button></div></div></div><div id=mcefake_38 class="mce-container mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_38-body><div id=mcefake_12 class="mce-widget mce-btn mce-disabled mce-first" tabindex=-1 aria-labelledby=mcefake_12 role=button aria-label="Align left"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-alignleft"></i></button></div><div id=mcefake_13 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_13 role=button aria-label="Align center"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-aligncenter"></i></button></div><div id=mcefake_14 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_14 role=button aria-label="Align right"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-alignright"></i></button></div><div id=mcefake_15 class="mce-widget mce-btn mce-disabled mce-last" tabindex=-1 aria-labelledby=mcefake_15 role=button aria-label=Justify><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-alignjustify"></i></button></div></div></div><div id=mcefake_39 class="mce-container mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_39-body><div id=mcefake_16 class="mce-widget mce-btn mce-disabled mce-first" tabindex=-1 aria-labelledby=mcefake_16 role=button aria-label="Bullet list"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-bullist"></i></button></div><div id=mcefake_17 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_17 role=button aria-label="Numbered list"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-numlist"></i></button></div><div id=mcefake_18 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_18 role=button aria-label="Decrease indent"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-outdent"></i></button></div><div id=mcefake_19 class="mce-widget mce-btn mce-disabled mce-last" tabindex=-1 aria-labelledby=mcefake_19 role=button aria-label="Increase indent"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-indent"></i></button></div></div></div><div id=mcefake_40 class="mce-container mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_40-body><div id=mcefake_20 class="mce-widget mce-btn mce-disabled mce-first" tabindex=-1 aria-labelledby=mcefake_20 role=button aria-label=Cut><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-cut"></i></button></div><div id=mcefake_21 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_21 role=button aria-label=Copy><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-copy"></i></button></div><div id=mcefake_22 class="mce-widget mce-btn mce-disabled mce-last" tabindex=-1 aria-labelledby=mcefake_22 role=button aria-pressed=false aria-label="Paste as text"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-pastetext"></i></button></div></div></div><div id=mcefake_41 class="mce-container mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_41-body><div id=mcefake_23 class="mce-widget mce-btn mce-disabled mce-first" tabindex=-1 aria-labelledby=mcefake_23 role=button aria-label="Insert/edit image"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-image"></i></button></div><div id=mcefake_24 class="mce-widget mce-btn mce-disabled mce-menubtn" tabindex=-1 aria-labelledby=mcefake_24 role=button aria-label=Table aria-haspopup=true><button id=mcefake_24-open role=presentation type=button tabindex=-1><i class="mce-ico mce-i-table"></i> <span></span> <i class=mce-caret></i></button></div><div id=mcefake_25 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_25 role=button aria-label="Horizontal line"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-hr"></i></button></div><div id=mcefake_26 class="mce-widget mce-btn mce-disabled mce-last" tabindex=-1 aria-labelledby=mcefake_26 role=button aria-label="Special character"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-charmap"></i></button></div></div></div><div id=mcefake_42 class="mce-container mce-last mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_42-body><div id=mcefake_27 class="mce-widget mce-btn mce-disabled mce-first" tabindex=-1 aria-labelledby=mcefake_27 role=button aria-label="Insert/edit link"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-link"></i></button></div><div id=mcefake_28 class="mce-widget mce-btn mce-disabled" tabindex=-1 aria-labelledby=mcefake_28 role=button aria-label="Remove link"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-unlink"></i></button></div><div id=mcefake_29 class="mce-widget mce-btn mce-disabled mce-last" tabindex=-1 aria-labelledby=mcefake_29 role=button aria-label=Anchor><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-anchor"></i></button></div></div></div><div id=mcefake_42 class="mce-container mce-last mce-flow-layout-item mce-btn-group" role=group><div id=mcefake_42-body><div id=mcefake_27 class="mce-widget mce-btn mce-disabled mce-first mce-last" tabindex=-1 aria-labelledby=mcefake_27 role=button aria-label="Insert/edit link"><button role=presentation type=button tabindex=-1><i class="mce-ico mce-i-removeformat"></i></button></div></div></div></div></div></div></div></div></div></div><div id=externalToolbarWrapper></div></div></div>';
            return self;
        }
    ]
);