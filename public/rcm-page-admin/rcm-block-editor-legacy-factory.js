/**
 * RcmBlockEditJsLegacy
 *
 * @param {RcmAdminPlugin} pluginHandler
 * @constructor
 */
var rcmBlockEditorLegacyFactory = function (pluginHandler) {

    var className = pluginHandler.getName() + 'Edit';
    var editClass = window[className];

    if (editClass) {
        return new editClass(pluginHandler.getId(), pluginHandler.getPluginContainer(), pluginHandler);
    }

    return rcmBlockEditorNoopFactory(
        pluginHandler
    );
};
