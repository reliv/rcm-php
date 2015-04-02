(function ($) {
    /**
     * Pops up an alert dialog using Boostrap
     *
     * @param {String} text what to say to user
     * @param {Function} [okCallBack] optional callback for ok button
     * @param {String} [title] optional the title bar text
     */
    $.fn.alert = function (text, okCallBack, title) {


        if (!title) {
            title = 'Alert';
        }

        var message = title;
        if(text){
            message = '<div class="modal-body"><p>' + text + '</p></div>';
        }

        var config = {
            message: message,
            title: '<h1 class="modal-title">' + title + '</h1>',
            buttons: {
                ok: {
                    label: "Ok",
                    className: "btn-primary",
                    callback: function () {
                    }
                }
            }

        };

        if (typeof okCallBack == 'function') {
            config.buttons.ok.callback = okCallBack;
        }

        bootbox.dialog(config);
    };
    /**
     * Pops up a confirm dialog using Boostrap
     *
     * @param {String} text what we are asking the user to confirm
     * @param {Function} [okCallBack] optional callback for ok button click
     * @param {Function} [cancelCallBack] optional callback for cancel button click
     * @param {String} [title] optional the title bar text
     */
    $.fn.confirm = function (text, okCallBack, cancelCallBack, title) {

        if (!title) {
            title = 'Confirm';
        }

        var message = title;
        if(text){
            message = '<div class="modal-body"><p>' + text + '</p></div>';
        }

        var config = {
            message: message,
            title: '<h1 class="modal-title">' + title + '</h1>',
            buttons: {
                cancel: {
                    label: "Cancel",
                    className: "btn-default",
                    callback: function () {
                    }
                },
                ok: {
                    label: "Ok",
                    className: "btn-primary",
                    callback: function () {
                    }
                }
            }

        };

        if (typeof cancelCallBack === 'function') {
            config.buttons.cancel.callback = cancelCallBack;
        }

        if (typeof okCallBack == 'function') {
            config.buttons.ok.callback = okCallBack;
        }

        bootbox.dialog(config);
    };
})(jQuery);