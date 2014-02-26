var ZendFormHelper = function (container) {
    /**
     * Always refers to this object unlike the 'this' JS variable;
     * @type {ZendFormHelper}
     */
    var me = this;

    /**
     * @TODO move this to php
     */
    this.highlightBadFields = function (badFields) {
        $.each(badFields, function () {
            container.find('[name="' + this + '"]')
                .addClass('invalid');
            container.find('[name="' + this + 'Confirm"]')
                .addClass('invalid');
            container.find('label[data-textEdit="' + this + '"]')
                .addClass('invalid');
            container.find('label[data-textEdit="' + this + 'Confirm"]')
                .addClass('invalid');
        });
    };

    /**
     * @TODO move this to php
     */
    this.fillPreviousPostedData = function (posted) {
        $.each(posted, function (key, value) {
            var input = container.find('[name=' + key + ']');
            if (input.is('input') && input.attr('type') == 'radio') {
                var correctValInput = container.find(
                    'input[value=' + value + '][name=' + key + ']'
                );
                correctValInput.attr('checked', true);
            } else {
                input.val(value);
            }
        });
    };

    this.showProcessing = function (buttonProcessingMessage, popMessage) {
        $.each(container.find('button, input[type="submit"]'), function () {
            var button = $(this);
            button.addClass('disabled');
            button.attr('data-originalHtml', button.html());
            button.val(buttonProcessingMessage);
            button.html(buttonProcessingMessage
//                + ' <img src=' +
//                '"/modules/rcm/vendor/jquery-block-ui/busy-spinner-16x16.gif' +
//                '">'
            );
        });
        if (typeof(popMessage) != 'undefined') {
            me.blockUiWithMessage(popMessage);
        } else {
            me.blockUiInvisible();
        }
    };

    this.hideProcessing = function () {
        $.each(container.find('button'), function () {
            var button = $(this);
            button.html(button.attr('data-originalHtml'));
            button.removeClass('disabled');
        });
        $.unblockUI();
    };

    /**
     * Creates a container that is show/hidden depending on a radio value
     * @param radioName
     * @param contSelector
     * @param showVal
     */
    this.createRadioHiddenCont = function (radioName, contSelector, showVal) {
        var checker = function () {

            if (typeof(notEqual) == 'undefined') {
                notEqual = false;
            }

            var hiddenCont = container.find(contSelector);

            var val = container.find('input[name="' + radioName + '"]:checked').val();

            if (!notEqual && val == showVal) {
                hiddenCont.show();
            } else {
                hiddenCont.hide();
            }
        };
        container.find('input[name="' + radioName + '"]').change(checker);
        checker();
    };

    this.createHiddenCheckBoxCont = function (checkSelector, contSelector) {
        var hiddenCont = container.find(contSelector);
        var checker = function(){
            if (container.find(checkSelector).is(':checked')) {
                hiddenCont.hide();
            } else {
                hiddenCont.show();
            }
        };
        container.find(checkSelector).change(checker);
        checker();
    };

    this.blockUiWithMessage = function (message) {
        $.blockUI({
            message: '<br>' + message + '<br>',
            css: { borderRadius: '20px', 'borderWidth': '0px', 'padding': '20px'}
        });
    };

    /**
     * Wrapper for blockUi
     */
    this.blockUiInvisible = function () {
        $.blockUI({
            message: '',
            css: { backgroundColor: 'transparent', borderColor: 'transparent'},
            overlayCSS: { backgroundColor: 'transparent'}
        });
    };
};
