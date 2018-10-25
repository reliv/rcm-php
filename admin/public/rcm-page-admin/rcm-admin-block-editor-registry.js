/**
 * RcmAdminBlockEditorRegistry
 * @param editorConfig
 * @constructor
 */
var RcmAdminBlockEditorRegistry = function (editorConfig) {
    var self = this;

    var editorFactories = {};

    /**
     *
     * @param {string} editorName
     * @param {function} editorFactory
     */
    self.add = function (editorName, editorFactory) {
        editorFactories[editorName] = editorFactory;
    };

    /**
     * getEditors
     * @returns {*}
     */
    self.getEditorFactories = function () {
        return editorFactories;
    };

    /**
     * buildEditor
     * @param editorName
     * @param {RcmAdminPlugin} pluginHandler
     * @returns {*}
     */
    self.buildEditor = function (editorName, pluginHandler) {
        if (!editorFactories[editorName]) {
            return null;
        }

        if (typeof editorFactories[editorName] !== 'function') {
            return null;
        }

        return editorFactories[editorName](pluginHandler);
    };

    /**
     *
     */
    var init = function (editorConfig) {
        if (typeof editorConfig !== 'object') {
            return;
        }

        editorFactories = editorConfig;
    };

    init(editorConfig);
};

var rcmAdminBlockEditorRegistry = new RcmAdminBlockEditorRegistry(
    {
        'noop': rcmBlockEditorNoopFactory,
        'rcm-plugin-bc': rcmBlockEditorLegacyFactory,
        'field-dialog': rcmBlockEditorFieldDialogFactory,
    }
);
