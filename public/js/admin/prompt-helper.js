(function( $ ){
    /**
     * Pops up an alert dialog using jQuery UI
     *
     * @param {String} text what to say to user
     * @param {Function} okCallBack [optional] called for ok button
     *
     * @return {Null}
     */
    $.fn.alert = function(text, okCallBack){
        $('<p>' + text + '</p>').dialog({
            title: 'Alert',
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
    }

    /**
     * Pops up a confirm dialog using jQuery UI
     *
     * @param {String} text what we are asking the user to confirm
     * @param {Function} okCallBack [optional] called for ok button click
     * @param {Function} cancelCallBack [optional] called for cancel button click
     *
     * @return {Null}
     */
    $.fn.confirm = function(text, okCallBack, cancelCallBack){
        $('<p>' + text + '</p>').dialog({
            title: 'Confirm',
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
    }

    $.fn.addImage = function (name, description, src) {
        var p = $('<p class="imageInput" style="overflow-y:hidden"></p>');
        this.append(p);
        p.append('<label for="' + name + '">' + description + '</label><br>' +
            '<img style="max-width:120px;float:left;margin-right:10px" src="' + src + '">');
        var urlBox=$('<input style="width:370px;margin-right:10px" name="' + name + '" value="' + src + '">');
        p.append(urlBox);
        p.append('<button type="button" class="image-button ui-button ui-widget ' +
            'ui-state-default ui-corner-all ui-button-text-only" role="button" ' +
            'aria-disabled="false">' +
            '<span class="ui-button-text">Browse</span>' +
            '</button>');

        if(!this.addImageEventsDelegated){

            this.addImageEventsDelegated = true;

            this.delegate('.imageInput button, .imageInput img','click',
                function(){
                    rcmEdit.showFileBrowserForInputBox(
                        $(this).parent().children('input')
                        ,'images'
                    );
                }
            );
            this.delegate('.imageInput input','change', function(){
                $(this).parent().children('img').attr('src', $(this).val());
            });
        }
        return this;
    }

    /**
     * Build html for a text input
     *
     * @param {String} name html name
     * @param {String} description title to show user
     * @param {String} value the current value
     *
     * @return String
     */
    $.fn.addInput = function (name, description, value) {
        this.append('' +
            '<p><label for="' + name + '">' + description + '</label><br>' +
            '<input name="' + name + '" value="' + value + '"></p>'
        );
        return this;
    }

    /**
     * Build html for a select drop down box
     *
     * @param {String} name html name
     * @param {String} description title to show user
     * @param {Array} choices options [html key => display value]
     * @param {String} value current choice key
     * @param {Boolean} allowCustomValues allow user to enter custom values that
     *                  are no in the select
     *
     * @return {String}
     */
    $.fn.addSelect = function (name, description, choices, value, allowCustomValues) {
        var p = $('<p></p>');
        p.append('<label for="' + name + '">' + description + '</label><br>')
        var customClass = '';
        if(allowCustomValues){
            customClass = ' class="selectAllowCustomValues"';
        }
        var select=$('<select' + customClass + ' name="' + name + '"><select>');

        for (var key in choices) {
            var selected = '';
            if (key == value) {
                selected=' selected="selected"';
            }
            select.append('<option value="' + key + '"' + selected + '>' + choices[key] + '</option>')
        }
        var inputBox = '';
        if (allowCustomValues) {

            var selected = '';
            var displayNone = ' style="display:none"';
            var customValue = '';
            if (!(value in choices)) {
                selected=' selected="selected"';
                displayNone = '';
                customValue=value;
            }
            select.append('<option class="custom" value="' + customValue + '"' + selected + '>Custom Value</option>');
            inputBox = $('<input' + customClass + displayNone + ' size="80" value="' + customValue + '">');
        }

        p.append(select);
        p.append(inputBox);

        this.append(p);

        //Ensure events are attached for the custom input box
        if(allowCustomValues&&!this.selectAllowCustomValuesDelegated){

            this.selectAllowCustomValuesDelegated=true;

            //Hide/show the custom text box if the 'Custom Value' is modded
            this.delegate('select.selectAllowCustomValues','change',function(event){
                var select = $(event.target);
                var textBox = select.parent().children('input');
                if(select.children('option.custom').attr('selected')
                    == 'selected'
                    ) {
                    textBox.show();
                }else{
                    textBox.hide();
                }
            });

            //Move any input box input to the select key value
            this.delegate('input.selectAllowCustomValues','change',function(event){
                var textBox = $(event.target);
                textBox.parent().children('select')
                    .children('option.custom').val(textBox.val());
            });
        }
        return this;
    }


    /**
     * Build a check box
     *
     * @param string name name
     * @param string description name to show user
     * @param boolean checked is it checked?
     *
     * @return {String}
     */
    $.fn.addCheckBox = function (name, description, checked) {
        var checkedHtml = '';
        if (checked) {
            checkedHtml = ' checked="checked"';
        }
        this.append(
            '<p><input type="checkbox"' + checkedHtml + ' name="' + name +
                '" value="true" />' + description + '</p>'
        );
        return this;
    }
})( jQuery );
