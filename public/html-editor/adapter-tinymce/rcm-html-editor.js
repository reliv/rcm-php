/**
 * RcmHtmlEditor - Main adapter to an actual tinymce
 * @param id
 * @param rcmHtmlEditorService
 * @constructor
 */
var RcmHtmlEditor = function (id, rcmHtmlEditorService) {

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
                editorInstance: ed
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

        return (self.getTagName() == "TEXTAREA");
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
     * hasEditorInstance
     * @returns {boolean}
     */
    self.hasEditorInstance = function () {

        var tinyInstance = tinymce.get(self.id);

        return (tinyInstance);
    };
};