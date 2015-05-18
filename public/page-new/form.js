function RcmNewPageForm() {

    var me = this;

    me.pageCheckUrl = '/rcm/page/check/n/';

    me.errorClass = 'invalid-field';
    me.formErrorLine = '#rcmNewPageErrorLine';
    me.pageTempateSelect = '#pageTemplate select';
    me.pageLayoutDiv = '#pageLayout';
    me.pageUrlTextField = "#pageUrl input";
    me.errorLabels = 'label.' + me.errorClass;

    me.xhrCount = 0;

    me.init = function () {
        me.addBindHandlers();
        me.showHidePageLayout();
        me.checkErrorLine();
    };

    me.checkErrorLine = function () {
        var errorLine = jQuery(me.formErrorLine);

        var currentHtml = errorLine.html();

        if (currentHtml.length > 0) {
            errorLine.show();
        } else {
            errorLine.hide();
        }
    };

    me.addBindHandlers = function () {
        jQuery(me.pageTempateSelect).change(me.showHidePageLayout);
        jQuery(me.pageUrlTextField).keyup(me.checkUrlValid);
        jQuery(me.errorLabels).change(function () {
            jQuery(this).removeClass();
        });
    };

    me.showHidePageLayout = function () {
        var currentValue = jQuery(me.pageTempateSelect).val();

        if (currentValue == 'blank') {
            jQuery(me.pageLayoutDiv).show();
        } else {
            jQuery(me.pageLayoutDiv).hide();
        }
    };

    me.checkUrlValid = function () {
        var pageUrlField = jQuery(me.pageUrlTextField);
        var currentValue = pageUrlField.val();

        pageUrlField.parent().removeClass();
        currentValue = currentValue.replace(' ', '-');
        pageUrlField.val(currentValue);

        if (currentValue.length < 3) {
            return;
        }

        var seqNumber = ++me.xhrCount;

        jQuery.getJSON(me.pageCheckUrl + currentValue,function () {

            if (seqNumber !== me.xhrCount) {
                return;
            }

            pageUrlField.parent().addClass('fieldOk');

            jQuery(me.formErrorLine + ' p.urlErrorMessage').remove();
            me.checkErrorLine();

        }).fail(function (data) {

            if (seqNumber !== me.xhrCount) {
                return;
            }

            pageUrlField.parent().addClass(me.errorClass);

            if (data.responseJSON.error != undefined) {
                var messages = '';

                jQuery.each(data.responseJSON.error, function ($key, $value) {
                    messages = messages + '<p class="urlErrorMessage">*' + $value + '</p>';
                });

                jQuery(me.formErrorLine).html(messages);
            }

            me.checkErrorLine();
        })
    }


}

jQuery(function () {
    var pageForm = new RcmNewPageForm;
    pageForm.init();
});