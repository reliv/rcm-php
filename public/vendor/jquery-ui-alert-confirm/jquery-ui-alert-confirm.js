(function( $ ){
    /**
     * Pops up an alert dialog using jQuery UI
     *
     * @param {String} text what to say to user
     * @param {Function} [okCallBack] optional callback for ok button
     * @param {String} [title] optional the title bar text
     */
    $.fn.alert = function(text, okCallBack, title){

        if(typeof(title)=='undefined'){
            title = 'Alert';
        }

        $('<p>' + text + '</p>').dialog({
            title: title,
            modal: true,
            buttons: {
                "Ok": function() {
                    if(typeof(okCallBack)=='function'){
                        okCallBack();
                    }
                    $( this ).dialog( "close" );
                }
            }
        });
    };

    /**
     * Pops up a confirm dialog using jQuery UI
     *
     * @param {String} text what we are asking the user to confirm
     * @param {Function} [okCallBack] optional callback for ok button click
     * @param {Function} [cancelCallBack] optional callback for cancel button click
     * @param {String} [title] optional the title bar text
     */
    $.fn.confirm = function(text, okCallBack, cancelCallBack, title){

        if(typeof(title)=='undefined'){
            title = 'Confirm';
        }

        var p = $('<p></p>');
        p.append(text);
        p.dialog({
            title: title,
            modal: true,
            buttons: {
                "Ok": function() {
                    if(typeof(okCallBack)=='function'){
                        okCallBack();
                    }
                    $( this ).dialog( "close" );
                },
                Cancel: function() {
                    if(typeof(cancelCallBack)=='function'){
                        cancelCallBack();
                    }
                    $( this ).dialog( "close" );
                }
            }
        });

    };
})( jQuery );
