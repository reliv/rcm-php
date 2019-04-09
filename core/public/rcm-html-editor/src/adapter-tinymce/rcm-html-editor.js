/**
 * RcmHtmlEditor - Main adapter to an actual tinymce
 * @param id
 * @param rcmHtmlEditorService
 * @constructor
 */
window.RcmHtmlEditor = function (id, rcmHtmlEditorService) {

    var self = this;
    self.id = id;
    self.scope;
    self.elm;
    self.attrs;
    self.ngModel;

    self.settings = {};

    self.tagName = "";

    /**
     * init
     * @param scope
     * @param elm
     * @param attrs
     * @param ngModel
     * @param settings
     */
    self.init = function (scope, elm, attrs, ngModel, settings) {
        self.scope = scope;
        self.elm = elm;
        self.ngModel = ngModel;
        self.settings = settings;
        self.attrs = attrs;
        self.buildEditor();
        self.buildRenderer(self.ngModel);
    };

    /**
     * onInit
     */
    self.onInit = function (editor) {
        rcmHtmlEditorService.eventManager.trigger(
            'RcmHtmlEditor.onInit',
            {
                rcmHtmlEditor: self,
                editorInstance: editor
            }
        );
    };

    /**
     * onDestroy
     */
    self.onDestroy = function () {
        rcmHtmlEditorService.eventManager.trigger(
            'RcmHtmlEditor.onDestroy',
            self
        );
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
        return self.getValue();
    };

    /**
     * getValue
     * @returns {*}
     */
    self.getValue = function () {

        if (self.ngModel.$viewValue) {
            return self.ngModel.$viewValue;
        }

        if (self.isFormControl()) {
            return self.elm.val();
        }

        return self.elm.html();
    };

    /**
     * getEditorInstance
     * @returns {*}
     */
    self.getEditorInstance = function () {

        return tinymce.get(self.id);
    };

    /**
     * isFormControl
     * @returns {boolean}
     */
    self.isFormControl = function () {

        return (self.getTagName() == "TEXTAREA");
    };

    /**
     * updateView
     */
    self.updateView = function () {

        if (!self.ngModel || typeof self.ngModel.$viewValue !== 'string') {
            return;
        }

        var editorInstance = self.getEditorInstance();
        var content = editorInstance.getContent({format: 'raw'});
        var currentValue = self.getValue();

        if (content !== currentValue) {
            self.ngModel.$setViewValue(content);
            self.apply();
        }
    };

    /**
     * apply
     */
    self.apply = function () {

        if (!self.scope.$root.$$phase) {

            self.scope.$apply(
                function () {
                    self.onApply();
                }
            );
        } else {

            self.onApply();
        }
    };

    /**
     * buildEditor
     */
    self.buildEditor = function () {

        self.settings.setup = function (editor) {

            var args;
            // This did not work, might be a way to sync click events
            //editor.on('click', function (args) {
            //
            //    if (self.elm.click) {
            //        self.elm.click();
            //    }
            //});
            editor.on(
                'init',
                function (args) {

                    if (self.ngModel) {
                        editor.setContent(self.getValue(), {format: 'raw'});
                        self.ngModel.$render();
                        self.ngModel.$setPristine();
                    }

                    self.onInit(editor);
                    self.apply();
                }
            );
            //
            editor.on(
                'postrender',
                function (args) {
                }
            );
            // Update model on button click
            editor.on(
                'ExecCommand',
                function (e) {
                    editor.save();
                    self.updateView();
                }
            );
            // Update model on keypress
            editor.on(
                'KeyUp',
                function (e) {
                    editor.save();
                    self.updateView();
                }
            );
            //editor.on(
            //    'BeforeSetContent',
            //    function (e) {
            //    }
            //);
            // Update model on change, i.e. copy/pasted text, plugins altering content
            editor.on(
                'SetContent',
                function (e) {

                    /**
                     * IMPORTANT
                     * We do NOT sync content on initial and when it is selection
                     * Selection adds some special markup which causes issues when we sync it
                     */
                    if (!e.initial && !e.selection) {

                        if (self.ngModel) {
                            if (self.ngModel.$viewValue !== e.content) {
                                editor.save();
                                self.updateView();
                            }
                        }
                        // else {
                        //     editor.save();
                        //     self.updateView();
                        // }
                    }
                }
            );
            // blur
            editor.on(
                'blur',
                function (e) {

                    rcmHtmlEditorService.isEditing = false;

                    self.updateView();
                }
            );
            // focus
            editor.on(
                'focus',
                function (e) {

                    rcmHtmlEditorService.isEditing = true;

                    self.updateView();
                }
            );
            // Update model when an object has been resized (table, image)
            editor.on(
                'ObjectResized',
                function (e) {

                    editor.save();
                    self.updateView();
                }
            );
            // change
            editor.on(
                'change',
                function (e) {

                    self.updateView();
                }
            );

            // change selection
            //editor.on(
            //    'SelectionChange',
            //    function () {
            //
            //        self.updateView();
            //    }
            //);
            // This might be needed if setup can be passed in
            //if (settings) {
            //    settings(editor);
            //}
        };

        self.elm.on(
            '$destroy', function () {
                self.destroy('self.elm.on $destroy');
            }
        );

        self.scope.$on(
            '$destroy', function () {
                // this can cause issues with editors that are on the page dynamically
                // might be caused by element being destroyed and scope is part on elm.
                // self.destroy();
            }
        );

        setTimeout(
            function () {
                tinymce.init(self.settings);
            }
        );
    };

    /**
     * ngModelRender
     */
    self.getNgModelRender = function (originalRender) {
        return function () {
            setTimeout(
                function () {
                    var editorInstance = self.getEditorInstance();
                    // NOTE: this is low level tinymce stuff and might break if tinymce changes
                    if (editorInstance && editorInstance.getBody()) {
                        var value = self.getValue();
                        editorInstance.setContent(value, {format: 'raw'});
                    } else {
                        // Should not be required, was extra garbage cleanup
                        // self.destroy('editorInstance not found')
                    }
                    originalRender();
                },
                0
            );
        };
    };

    /**
     * buildRenderer
     */
    self.buildRenderer = function (ngModel) {
        if (!ngModel) {
            return;
        }
        var originalRender = ngModel.$render;
        ngModel.$render = self.getNgModelRender(originalRender);
    };

    /**
     * destroy
     * @param msg For tracking only - not required
     */
    self.destroy = function (msg) {

        var editorInstance = self.getEditorInstance();
        // @todo editorInstance.dom is a hack to fix issues where dom is gone, not sure what the impact is
        if (editorInstance && editorInstance.dom) {
            editorInstance.remove();
        }

        self.onDestroy();

        self.apply();
    };

    /**
     * hasEditorInstance
     * @returns {boolean}
     */
    self.hasEditorInstance = function () {

        var tinyInstance = tinymce.get(self.id);

        return (tinyInstance);
    };
};
