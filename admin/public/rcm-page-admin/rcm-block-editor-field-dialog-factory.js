/**
 * RcmAdminPluginEditJs AKA RcmPluginEditJs - Default Edit JS - does nothing - interface
 * @param {RcmAdminPlugin} pluginHandler
 * @constructor
 */
var rcmBlockEditorFieldDialogFactory = function (pluginHandler) {

    var config = window.rcmBlockConfigs[pluginHandler.getName()];

    if (!config) {
        return new RcmBlockEditorFieldDialog(pluginHandler, []);
    }

    return new RcmBlockEditorFieldDialog(pluginHandler, config['fields']);
};
