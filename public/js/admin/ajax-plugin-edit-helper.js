/**
 * Provides shared ajax editing functionality
 *
 * @constructor
 */
var AjaxPluginEditHelper = function (instanceId, pluginUrlName) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     * @type {RcmDistributorApp}
     */
    var me = this;

    var pluginBaseUrl = '/rcm-plugin-admin-proxy/' + pluginUrlName + '/'
        + instanceId + '/';

    me.getInstanceConfigAndNewInstanceConfigFromServer = function (callback) {

        $.getJSON(
            pluginBaseUrl + 'instance-config-and-new-instance-config',
            function (result) {
                callback(result.instanceConfig, result.newInstanceConfig);
            }
        );
    };

    me.buildTranslateInputs = function (defaultTrans, currentTrans) {
        var inputs = [];
        $.each(defaultTrans, function (key, value) {
            $.dialogIn('text', key, value, currentTrans[key]);
        });
        return inputs
    }
};