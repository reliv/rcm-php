var rcm = new Rcm();
/**
 * Content Viewing Code
 *
 * @type {Object}
 */
function Rcm() {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {Rcm}
     */
    var me = this;

    me.getPluginContainer = function(instanceId){
        return $(me.getPluginContainerSelector(instanceId));
    }

    me.getPluginContainerSelector = function(instanceId){

        /* Check for actual container.  Helpful for duplicates on page */
        var container = $('#RcmRealPage [data-rcmPluginInstanceId="'+instanceId+'"]');

        if (container.length < 1) {
            return('[data-rcmPluginInstanceId="'+instanceId+'"]');
        } else {
            return('#RcmRealPage [data-rcmPluginInstanceId="'+instanceId+'"]');
        }
    }


    /**
     * Gets all params from the url query string
     * To get your params
     * <code>
     *     var params = object.getUrlParams();
     *     params.myparm
     * </code>
     *
     * @return {Object}
     */
    me.getUrlParams = function(){
        var params = {};

        if (location.search) {
            var parts = location.search.substring(1).split('&');

            for (var i = 0; i < parts.length; i++) {
                var nv = parts[i].split('=');
                if (!nv[0]) continue;
                params[nv[0]] = nv[1] || true;
            }
        }
        return params;
    }
}