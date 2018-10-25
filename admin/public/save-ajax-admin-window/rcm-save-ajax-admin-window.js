/**
 * @deprecated
 * Replaces rcmEdit ajax lib
 * @type {{save: Function, post: Function, success: Function, error: Function}}
 */
var RcmSaveAjaxAdminWindow = {

    /**
     * @deprecated
     * Replaces rcmEdit.saveAjaxAdminWindow
     * @param saveUrl
     * @param send
     * @param formContainer
     * @param dataOkHeadline
     * @param dataOkMessage
     * @param keepOpen
     * @param successCallback
     */
    save: function (
        saveUrl,
        send,
        formContainer,
        dataOkHeadline,
        dataOkMessage,
        keepOpen,
        successCallback,
        rcmDialog
        ) {
        $.getJSON(
            saveUrl,
            send,
            function (data) {
                RcmSaveAjaxAdminWindow.success(
                    data,
                    formContainer,
                    dataOkHeadline,
                    dataOkMessage,
                    keepOpen,
                    successCallback,
                    rcmDialog
                )
            }
        ).error(
            function () {
                RcmSaveAjaxAdminWindow.error(formContainer);
            }
        );
    },

    /**
     * @deprecated
     * Replaces saveAjaxAdminWindowUsingPost
     * @param saveUrl
     * @param send
     * @param formContainer
     * @param dataOkHeadline
     * @param dataOkMessage
     * @param keepOpen
     * @param successCallback
     */
    post: function (
        saveUrl,
        send,
        formContainer,
        dataOkHeadline,
        dataOkMessage,
        keepOpen,
        successCallback,
        rcmDialog
        ) {
        $.post(
            saveUrl,
            send,
            function (data) {
                RcmSaveAjaxAdminWindow.success(
                    data,
                    formContainer,
                    dataOkHeadline,
                    dataOkMessage,
                    keepOpen,
                    successCallback,
                    rcmDialog
                )
            },
            'json'
        )
            .error(
            function () {
                RcmSaveAjaxAdminWindow.error(formContainer);
            }
        );
    },

    /**
     * @deprecated
     * Replaces saveAjaxAdminWindowSuccess
     * @param data
     * @param formContainer
     * @param dataOkHeadline
     * @param dataOkMessage
     * @param keepOpen
     * @param successCallback
     */
    success: function (
        data,
        formContainer,
        dataOkHeadline,
        dataOkMessage,
        keepOpen,
        successCallback,
        rcmDialog
        ) {

        if (data.dataOk == 'Y' && data.redirect == undefined) {

            //Close Window unless told not to
            if (keepOpen !== true) {

                if (rcmDialog) {
                    rcmDialog.actions.close.method();
                } else {
                    $(formContainer).parent().dialog("close");
                }
            } else {

                $(formContainer).find(".ajaxFormErrorLine").html('').hide();
            }

            //Show Status Message if passed in
            if (dataOkHeadline && dataOkMessage) {

                $.growlUI(dataOkHeadline, dataOkMessage);
            }

            //Process sucessCallback if passed in
            if (typeof successCallback === 'function') {

                successCallback(data);
            }
        } else if (data.dataOk == 'Y' && data.redirect) {

            window.location = data.redirect;
        } else if (data.dataOk != 'Y' && data.error != '') {

            $(formContainer).find(".ajaxFormErrorLine").html('<br /><p style="color: #FF0000;">' + data.error + '</p><br />').show();
            $(formContainer).parent().scrollTop(0);
        } else {

            $(formContainer).find(".ajaxFormErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
            $(formContainer).parent().scrollTop(0);
        }

    },
    /**
     * @deprecated
     * Replaces saveAjaxAdminWindowSuccessError
     * @param formContainer
     */
    error: function (formContainer) {
        $(formContainer).find(".ajaxFormErrorLine").html('<br /><p style="color: #FF0000;">Communication Error!</p><br />').show();
        $(formContainer).parent().scrollTop(0);
    }
};