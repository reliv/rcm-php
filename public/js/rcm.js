window.rcm = new Rcm();
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
    };

    me.getPluginContainerSelector = function(instanceId){

        /* Check for actual container.  Helpful for duplicates on page */
        var container = $('#RcmRealPage [data-rcmPluginInstanceId="'+instanceId+'"]');

        if (container.length < 1) {
            return('[data-rcmPluginInstanceId="'+instanceId+'"] .rcmPluginContainer');
        } else {
            return('#RcmRealPage [data-rcmPluginInstanceId="'+instanceId+'"] .rcmPluginContainer');
        }
    };


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
    };

    me.updateURLParameter = function(url, param, paramVal)
    {
        var TheAnchor = null;
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";

        if (additionalURL)
        {
            var tmpAnchor = additionalURL.split("#");
            var TheParams = tmpAnchor[0];
            TheAnchor = tmpAnchor[1];
            if(TheAnchor)
                additionalURL = TheParams;

            tempArray = additionalURL.split("&");

            for (i=0; i<tempArray.length; i++)
            {
                if(tempArray[i].split('=')[0] != param)
                {
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }
        else
        {
            var tmpAnchor = baseURL.split("#");
            var TheParams = tmpAnchor[0];
            TheAnchor  = tmpAnchor[1];

            if(TheParams)
                baseURL = TheParams;
        }

        if(TheAnchor)
            paramVal += "#" + TheAnchor;

        var rows_txt = temp + "" + param + "=" + paramVal;
        return baseURL + "?" + newAdditionalURL + rows_txt;
    };
}