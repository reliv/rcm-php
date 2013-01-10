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

    me.getDataAndDefaultDataFromServer = function(callback){

        $.getJSON(
            pluginBaseUrl + 'data-and-default-data',
            function(result) {
                callback(result.data,result.defaultData);
            }
        );


    }
};