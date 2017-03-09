/**
 * RcmAdminPluginEditJs AKA RcmPluginEditJs - Default Edit JS - does nothing - interface
 * @param {RcmAdminPlugin} pluginHandler
 * @constructor
 */
var RcmAdminPluginEditJs = function (pluginHandler) {
    var self = this;
    self.id = pluginHandler.getId();
    //self.pluginContainer = pluginContainer;

    self.initEdit = function () {
        //console.warn('initEdit: no edit js object found for '+pluginHandler.getName()+' - using default for: ' + self.id);
    };

    self.getSaveData = function () {
        //console.warn('getSaveData: no edit js object found '+pluginHandler.getName()+' - using default for: ' + self.id);
        return {};
    };
};
