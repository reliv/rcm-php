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

    me.getDataAndDefaultDataFromServer = function(callback){

        var data;
        var defaultData;

        var haveDataAndDefaultData = function(){
            return (data!=null && defaultData!=null);
        };

        var pluginBaseUrl =  '/rcm-plugin-admin-proxy/' + pluginUrlName + '/'
            + instanceId + '/';

        var returnAllData = function(){
            callback(data, defaultData);
        };

        $.getJSON(
            pluginBaseUrl + 'default-data',
            function(returnedData) {
                defaultData = returnedData;
                if (haveDataAndDefaultData()) {
                    returnAllData();
                }
            }
        );

        $.getJSON(
            pluginBaseUrl + 'data',
            function success(returnedData) {
                data = returnedData;
                if (haveDataAndDefaultData()) {
                    returnAllData();
                }
            }
        );

    }
};