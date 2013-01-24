(function( $ ){

    /**
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     * THIS FILE IS DEPRECATED.
     * PLEASE USE vendor/reliv/Rcm/public/vendor/prompt-helper instead
     * The new version uses js vars instead of names and ids to make val
     * retrieval easy
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     *
     * @param name
     * @param description
     * @param src
     * @return {*}
     */


    /**
     * DEPRECATED - DO NOT USE
     * @param name
     * @param description
     * @param src
     * @return {*}
     */
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
    };

    /**
     * DEPRECATED - DO NOT USE
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
    };

    /**
     * DEPRECATED - DO NOT USE
     *
     * @param {String} name html name
     * @param {String} description title to show user
     * @param {String} value the current value
     * @param {String} urlToValidator URL to Ajax Validator
     * @param {Boolean} [disallowSpaces] URL to Ajax Validator
     *
     * @return String
     */
    $.fn.addInputWithAjaxValidator = function (name, description, value, urlToValidator, disallowSpaces) {
        var validatorId = $.fn.generateUUID();
        this.append('' +
            '<p><label for="' + name + '">' + description + '</label><br>' +
            '<div id="' + validatorId +'" style="float: right;"></div> ' +
            '<input id="' + name + '" name="' + name + '" value="' + value + '"></p>'
        );

        this.find('#'+name).keyup(function(){
            var validationContainer = $("#"+validatorId);
            $.fn.validateInput(this, validationContainer, urlToValidator, disallowSpaces);
        });

        return this;
    };

    /**
     * Build html for a text input
     *
     * @param {String} name html name
     * @param {String} description title to show user
     * @param {String} value the current value
     *
     * @return String
     */
    $.fn.addDate = function (name, description, value) {
        var id = $.fn.generateUUID()

        var p = $('<p><label for="' + name + '">' + description + '</label>' +
            '<br></p>');
        var input = $('<input name="' + name + '" value="' + value + '">');
        p.append(input);
        this.append(p);
        input.datepicker();
        return this;
    };

    /**
    * DEPRECATED - DO NOT USE
    *
    * Due to ckEditor limitations, this must be called AFTER .dialog is called
    *
    * @param {String} name html name
    * @param {String} description title to show user
    * @param {String} value the current value
    * @param {Object} [toolBarConfig] tool bar config for ckEditor
    *
    * @return String
    */
    $.fn.addRichEdit = function (name, description, value, toolBarConfig) {

        if(typeof(toolBarConfig)=='undefined'){
            toolBarConfig = {
                toolbar: [
                    { name: 'document', items : [ 'Source' ] },
                    { name: 'undoRedo', items : ['Undo','Redo'] },
                    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
                    { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
                        '-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
                    { name: 'clipboard', items : ['Cut','Copy','Paste','PasteText','PasteFromWord'] },
                    { name: 'insert', items : [ 'Image', 'Table','HorizontalRule','SpecialChar','Templates'] },
                    { name: 'links', items : [ 'Link','Unlink','Anchor' ] }
                ]
            };
        }

        var id = $.fn.generateUUID();
        var div = $('<div id="' + id + '" class="'+name+'" contenteditable="true">' + value +'</div>');
        var p = $(
            '<p>' +
                '<label>' + description + '</label><br>' +
            '</p>'
        );
        this.append(p);
        p.append(div);
        // This terrible timeout hack is needed because the new version of
        // ckEditor only works on elements that are in the DOM
        setTimeout(
            function(){
                CKEDITOR.replace(id,toolBarConfig);
            },
            100
        );
        return this;
    };

    /**
     * DEPRECATED - DO NOT USE
     *
     * @param {String} name html name
     * @param {String} description title to show user
     * @param {Array} choices options [html key => display value]
     * @param {String} [value] current choice key
     * @param {Boolean} [allowCustomValues] allow user to enter custom values that
     *                  are no in the select
     *
     * @return {String}
     */
    $.fn.addSelect = function (name, description, choices, value, allowCustomValues) {
        var p = $('<p></p>');
        var selected;
        p.append('<label for="' + name + '">' + description + '</label><br>');
        var customClass = '';
        if(allowCustomValues){
            customClass = ' class="selectAllowCustomValues"';
        }
        var select=$('<select' + customClass + ' name="' + name + '"><select>');

        for (var key in choices) {
            selected = '';
            if (key == value) {
                selected=' selected="selected"';
            }
            select.append('<option value="' + key + '"' + selected + '>' + choices[key] + '</option>')
        }
        var inputBox = '';
        if (allowCustomValues) {

            selected = '';
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
    };


    /**
     * DEPRECATED - DO NOT USE
     *
     * @param {String} name name
     * @param {String} description name to show user
     * @param {Boolean} checked is it checked?
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
    };

    /**
     * DEPRECATED - DO NOT USE
     * @return {String}
     */
    $.fn.generateUUID = function(){
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(
            /[xy]/g,
            function(c) {
                var r = Math.random()*16|0, v = c == 'x' ? r : (r&0x3|0x8);
                return v.toString(16);
            }
        );
    };

    $.fn.validateInput = function(inputField, resultContainer, ajaxPath, disallowSpaces) {

        if(typeof(disallowSpaces)=='undefined'){
            disallowSpaces = false;
        }

        if (disallowSpaces) {
            /* Get the value of the input field and filter */
            var inputValue = $(inputField).val().toLowerCase().replace(/\s/g, '-').replace(/[^A-Za-z0-9\-\_]/g, "");
            $(inputField).val(inputValue);
        } else {
            var inputValue = $(inputField).val();
        }

        /* make sure that the page name is greater then 1 char */
        if(inputValue.length < 1) {
            $.fn.inputFieldError(inputField, resultContainer);
            $(resultContainer).html('');
            return false;
        }

        /* Check name via rest service */
        var pageOk = false;

        var dataToSend = {};
        var fieldId = $(inputField).attr('id');

        dataToSend[fieldId] = inputValue;

        $.getJSON(ajaxPath, dataToSend, function(data) {
            if (data.pageOk == 'Y') {
                $.fn.inputFieldOk(inputField, resultContainer);
            } else if(data.pageOk != 'Y') {
                $.fn.inputFieldError(inputField, resultContainer);
            } else {
                $.fn.inputFieldFatalError(inputField, resultContainer);
            }
        }).error(function(){
                $.fn.inputFieldFatalError(inputField, resultContainer);
            });

        return pageOk;
    };

    $.fn.inputFieldError = function(inputField, resultContainer) {
        $(resultContainer).removeClass('ui-icon-check');
        $(resultContainer).addClass('ui-icon-alert').addClass('ui-icon');
        $(inputField).addClass('RcmErrorInputHightlight');
        $(inputField).removeClass('RcmOkInputHightlight');

    };

    $.fn.inputFieldFatalError = function(inputField, resultContainer) {
        $(resultContainer).html('<p style="color: #FF0000;">Error!</p>');
        $(inputField).addClass('RcmErrorInputHightlight');
        $(inputField).removeClass('RcmOkInputHightlight');
    };

    $.fn.inputFieldOk = function(inputField, resultContainer) {
        $(resultContainer).removeClass('ui-icon-alert');
        $(resultContainer).addClass('ui-icon-check').addClass('ui-icon');
        $(inputField).removeClass('RcmErrorInputHightlight');
        $(inputField).addClass('RcmOkInputHightlight');
    };
})( jQuery );
