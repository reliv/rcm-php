(function( $ ){
    $.fn.inputImage = function (description, src) {

        //Give it a random name so labels and multi-dialogs work
        var name = $.fn.generateUUID();

        var p = $('<p class="imageInput" style="overflow-y:hidden"></p>');
        p.append('<label for="' + name + '">' + description + '</label><br>' +
            '<img style="max-width:120px;float:left;margin-right:10px" src="' + src + '">');
        var urlBox=$('<input style="width:370px;margin-right:10px" name="' + name + '" value="' + src + '">');
        p.append(urlBox);
        p.append('<button type="button" class="image-button ui-button ui-widget ' +
            'ui-state-default ui-corner-all ui-button-text-only" role="button" ' +
            'aria-disabled="false">' +
            '<span class="ui-button-text">Browse</span>' +
            '</button>');

        if(!this.inputImageEventsDelegated){

            this.inputImageEventsDelegated = true;

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
        return p;
    };

    /**
     * Build html for a text input
     *
     * @param {String} description title to show user
     * @param {String} value the current value
     *
     * @return String
     */
    $.fn.inputText = function (description, value) {

        //Give it a random name so labels and multi-dialogs work
        var name = $.fn.generateUUID();

        return $(
            '<p><label for="' + name + '">' + description + '</label><br>' +
            '<input name="' + name + '" value="' + value + '"></p>'
        );
    };

    /**
     * Build html for a text input
     *
     * @param {String} description title to show user
     * @param {String} value the current value
     *
     * @return String
     */
    $.fn.inputDate = function (description, value) {

        //Give it a random name so labels and multi-dialogs work
        var name = $.fn.generateUUID();

        var p = $('<p><label for="' + name + '">' + description + '</label>' +
            '<br></p>');
        var input = $('<input name="' + name + '" value="' + value + '">');
        p.append(input);
        input.datepicker();
        return p;
    };

    /**
     * Build html for a select drop down box
     *
     * @param {String} description title to show user
     * @param {Array} choices options [html key => display value]
     * @param {String} [value] current choice key
     * @param {Boolean} [allowCustomValues] allow user to enter custom values that
     *                  are no in the select
     *
     * @return {String}
     */
    $.fn.inputSelect = function (description, choices, value, allowCustomValues) {

        //Give it a random name so labels and multi-dialogs work
        var name = $.fn.generateUUID();

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
     * Build a check box
     *
     * @param {String} description name to show user
     * @param {Boolean} checked is it checked?
     *
     * @return {String}
     */
    $.fn.inputCheckBox = function (description, checked) {

        //Give it a random name so labels and multi-dialogs work
        var name = $.fn.generateUUID();

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
     * Build html for a text input
     *
     * Due to ckEditor limitations, this must be called AFTER .dialog is called
     *
     * @param {String} description title to show user
     * @param {String} value the current value
     * @param {Object} [toolBarConfig] tool bar config for ckEditor
     *
     * @return String
     */
    $.fn.inputRichEdit = function (description, value, toolBarConfig) {

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
        var div = $('<div id="' + id + '" contenteditable="true">' + value +'</div>');
        var p = $(
            '<p>' +
                '<label>' + description + '</label><br>' +
                '</p>'
        );
        p.append(div);
        // This terrible timeout hack is needed because the new version of
        // ckEditor only works on elements that are in the DOM
        setTimeout(
            function(){
                CKEDITOR.replace(id,toolBarConfig);
            },
            100
        );
        return p;
    };

    /**
     * Generates RFC4122 v4 compliant random ids
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
})( jQuery );
