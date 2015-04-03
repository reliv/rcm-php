/**
 * @deprecated
 * rcmEdit.adminPopoutWindow replacement
 * @param pagePath
 * @param height
 * @param width
 * @param title
 * @param windowName
 * @param data
 * @param successCallback
 * @param language
 * @returns {*|jQuery|HTMLElement}
 * @constructor
 */
RcmPopoutWindow = function (
    pagePath,
    height,
    width,
    title,
    windowName,
    data,
    successCallback,
    language
    ) {

    if (windowName == undefined || windowName == null || windowName == '') {
        windowName = 'rcmAdminPagePopoutWindow'
    }

    if (!language) {
        language = '';
    } else {
        language = '/' + language;
    }

    $('body').find("#" + windowName).remove();
    $('body').append('<div id="' + windowName + '"></div>');

    var popoutWidowDiv = $("#" + windowName);

    $(popoutWidowDiv).load(pagePath + language, data, function (response, status, xhr) {

        if (status == "error") {
            var msg = "Sorry but there was an error: ";
            $(popoutWidowDiv).html(msg + xhr.status + " " + xhr.statusText);
        }

        if (successCallback && typeof successCallback === 'function') {
            successCallback.call(popoutWidowDiv);
        }
    });

    $(popoutWidowDiv).dialog(
        {
            title: title,
            height: height,
            width: width,
            open: function (event, ui) {
                $('.ui-dialog').css('z-index',3000);
                $('.ui-widget-overlay').css('z-index',3000);
            }
        }
    );

    return popoutWidowDiv;
};