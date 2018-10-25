/**
 * rcmBlockEditorNoopFactory
 *
 * @param {RcmAdminPlugin} pluginHandler
 * @constructor
 */
var rcmBlockEditorNoopFactory = function (pluginHandler) {

    return new RcmAdminPluginEditJs(pluginHandler);
};
