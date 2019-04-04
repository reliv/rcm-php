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
