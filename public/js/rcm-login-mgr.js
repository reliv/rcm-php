var RcmLoginMgr = function(loginUrl) {

    var me = this;

    me.loginUrl = loginUrl;

    me.successCallback = null;

    me.failCallback = null;

    me.doLogin = function(username, password, successCallback, failCallback) {

        me.successCallback=successCallback;
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
            success : me.processResponse,
            error : function(){me.callFail('systemFailure');}
        });
    };

    me.processResponse = function(data) {
        if(!data.dataOk) {
            if (!data.error) {
                me.doSystemFailure(data);
                return
            }

            me.processError(data.error);
            return;
        }

        me.doSuccess(data);
    };

    me.processError = function(error) {
        switch(error) {
            case 'missingNeeded':
                me.callFail('missing');
                break;
            case 'invalid':
                me.callFail('invalid');
                break;
            case 'noAuth':
                me.callFail('invalid');
                break;
            default:
                me.callFail('systemFailure');
        }
    };

    me.doSuccess = function (data) {
        if (typeof(me.successCallback) === 'function') {
            me.successCallback(this, data);
        }
    };

    me.callFail = function(message){
        if (typeof(me.failCallback) === 'function') {
            me.failCallback(message);
        }
    };
};