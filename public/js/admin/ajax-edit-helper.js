/**
 * Provides shared ajax editing functionality
 *
 * @constructor
 */
var AjaxEditHelper=function(instanceId, pluginUrlName){

    /**
     * Always refers to this object unlike the 'this' JS variable;
     * @type {RcmDistributorApp}
     */
    var me = this;

    var pluginBaseUrl =  '/rcm-plugin-admin-proxy/' + pluginUrlName + '/'
        + instanceId + '/';

    me.getInstanceConfigAndNewInstanceConfigFromServer = function(callback){

        $.getJSON(
            pluginBaseUrl + 'instance-config-and-new-instance-config',
            function(result) {
                callback(result.instanceConfig,result.newInstanceConfig);
            }
        );


    }
};