var RcmLoginMgr = function(loginUrl) {

    var me = this;

    me.loginUrl = loginUrl;

    me.successCallback = null;

    me.doLogin = function(username, password, successCallBack, failCallback) {

        me.failCallback=failCallback;

        var data = {
            username : username,
            password :  password
        };

        $.ajax({
            type: 'POST',
            url : me.loginUrl,
            cache : false,
            data : data,
            dataType: "json",
            success : function(data){
                me.processResponse(data,successCallBack,failCallback)
            },
            error : function(){failCallback('systemFailure');}
        });

        return false;
    };

    me.processResponse = function(data,successCallBack,failCallback) {
        if(!data['dataOk']) {
            me.processError(data['error'],failCallback);
            return ;
        }
        successCallBack();
        //window.location=data['redirectUrl'];
    };

    me.processError = function(error, failCallback) {
        if(error!='missing'&&error!='invalid'){
            error='systemFailure';
        }
        failCallback(error);
    };
};