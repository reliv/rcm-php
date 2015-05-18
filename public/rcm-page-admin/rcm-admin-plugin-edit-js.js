/**
 * RcmAdminPluginEditJs AKA RcmPluginEditJs - Default Edit JS - does nothing - interface
 * @param id
 * @param pluginContainer
 * @param pluginHandler
 * @constructor
 */
var RcmAdminPluginEditJs = function (id, pluginContainer, pluginHandler) {

    var self = this;
    self.id = id;
    //self.pluginContainer = pluginContainer;

    self.initEdit = function () {
        //console.warn('initEdit: no edit js object found for '+pluginHandler.getName()+' - using default for: ' + self.id);
    };

    self.getSaveData = function () {
        //console.warn('getSaveData: no edit js object found '+pluginHandler.getName()+' - using default for: ' + self.id);
        return {};
    };
};
