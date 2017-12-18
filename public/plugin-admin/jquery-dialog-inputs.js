var inputImageEventsDelegated = false;
/**
 * <jquery-dialog-inputs>
 */
(function ($) {
    var richEditToolbars = {

        'basic': [
            {name: 'document', items: ['Source']},
            {name: 'undoRedo', items: ['Undo', 'Redo']},
            {
                name: 'basicstyles',
                items: [
                    'Bold',
                    'Italic',
                    'Underline',
                    'Strike',
                    'Subscript',
                    'Superscript',
                    '-',
                    'RemoveFormat'
                ]
            },
            {name: 'insert', items: ['SpecialChar']},
            {name: 'links', items: ['Link', 'Unlink', 'Anchor']}
        ],

        'defaults': [
            {
                name: 'document',
                items: ['Source']
            },
            {
                name: 'undoRedo',
                items: ['Undo', 'Redo']
            },
            {
                name: 'basicstyles',
                items: [
                    'Bold',
                    'Italic',
                    'Underline',
                    'Strike',
                    'Subscript',
                    'Superscript',
                    '-',
                    'RemoveFormat'
                ]
            },
            {
                name: 'paragraph',
                items: [
                    'NumberedList',
                    'BulletedList',
                    '-',
                    'Outdent',
                    'Indent',
                    '-',
                    'Blockquote',
                    'CreateDiv',
                    '-',
                    'JustifyLeft',
                    'JustifyCenter',
                    'JustifyRight',
                    'JustifyBlock'
                ]
            },
            {
                name: 'clipboard',
                items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord']
            },
            {
                name: 'insert',
                items: [
                    'Image',
                    'Table',
                    'HorizontalRule',
                    'SpecialChar',
                    'Templates'
                ]
            },
            {
                name: 'links',
                items: ['Link', 'Unlink', 'Anchor']
            }
        ]
    };

    var attachPageListAutoComplete = function (input) {
        $.getJSON(
            '/rcm-page-search/title', function (data) {
                var pageUrls = [];
                $.each(
                    data, function (pageUrl) {
                        pageUrls.push(pageUrl);
                    }
                );
                input.autocomplete(
                    {
                        source: pageUrls,
                        minLength: 0
                    }
                );
            }
        );
    };


    /**
     * Displays a file picker window that is connected to an input box.
     *
     * @param {Object} urlInputBox jQuery input box to attach to file URL
     * @param {String} fileType optional file type to allow
     *
     * @return {Null}
     */
    var showFileBrowserForInputBox = function (urlInputBox, fileType) {
        showFileBrowser(
            function (path) {
                urlInputBox.attr('value', path);
                urlInputBox.trigger('change');
            },
            fileType,
            urlInputBox.val()
        )
    };

    /**
     * Displays a file picker window
     *
     * @param {Function} callBack this is called when the user picks a file
     * @param {String} fileType optional file type to allow
     * @param oldPath the old path of the file
     */
    var showFileBrowser = function (callBack, fileType, oldPath) {
        /**
         * This is the new way all file choosers should work in RCM
         */
        if (window.rcmFileChooser) {
            rcmFileChooser.chooseFile(callBack, oldPath);
            return;
        }
        /**
         * This is the deprecated way file choosers should work.
         * This will be removed once we have an rcmFileChooser
         * js factory for elfinder.
         * @param url
         */
        //Declare a function for the file picker to call when user picks a file
        window['elFinderFileSelected'] = function (url) {
            callBack(url);
        };
        //Open the file picker window
        var url = '/elfinder';
        if (fileType) {
            url += '/' + fileType;
        }
        popup(url, 1024, 768);
    };
    /**
     * Opens Browser in a popup. The "width" and "height" parameters accept
     * numbers (pixels) or percent (of screen size) values.
     *
     * This is pulled from ckEditor code
     *
     * @param {String} url The url of the external file browser.
     * @param {String} width Popup window width.
     * @param {String} height Popup window height.
     * @param {String} options Popup window features.
     */
    var popup = function (url, width, height, options) {
        width = width || '80%';
        height = height || '70%';

        if (typeof width == 'string' && width.length > 1 && width.substr(
                width.length - 1,
                1
            ) == '%')
            width = parseInt(window.screen.width * parseInt(width, 10) / 100, 10);

        if (typeof height == 'string' && height.length > 1 && height.substr(
                height.length - 1,
                1
            ) == '%')
            height = parseInt(window.screen.height * parseInt(height, 10) / 100, 10);

        if (width < 640)
            width = 640;

        if (height < 420)
            height = 420;

        var top = parseInt((window.screen.height - height) / 2, 10),
            left = parseInt((window.screen.width - width) / 2, 10);

        options = (options || 'location=no,menubar=no,toolbar=no,dependent=yes,minimizable=no,modal=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes') +
            ',width=' + width +
            ',height=' + height +
            ',top=' + top +
            ',left=' + left;

        var popupWindow = window.open('', null, options, true);

        // Blocked by a popup blocker.
        if (!popupWindow)
            return false;

        try {
            // Chrome 18 is problematic, but it's not really needed here (#8855).
            var ua = navigator.userAgent.toLowerCase();
            if (ua.indexOf(' chrome/18') == -1) {
                popupWindow.moveTo(left, top);
                popupWindow.resizeTo(width, height);
            }
            popupWindow.focus();
            popupWindow.location.href = url;
        }
        catch (e) {
            popupWindow = window.open(url, null, options, true);
        }

        return true;
    };

    var selectFieldFunction = function (description, choices, value, allowCustomValues) {

        //Give it a random name so labels and multi-dialogs work
        var name = $.fn.generateUUID();

        var p = $('<p class="dialogElement" data-dialogElementName="' + name + '"></p>');
        var selected;

        if (description) {
            p.append('<label for="' + name + '">' + description + '</label><br>');
        }

        var select = $('<select name="' + name + '"><select>');

        if (allowCustomValues) {
            select.append('<option></option>');
        }


        for (var key in choices) {
            selected = '';
            if (key == value) {
                selected = ' selected="selected"';
            }
            select.append('<option value="' + key + '"' + selected + '>' + choices[key] + '</option>')
        }
        var inputBox = '';

        if (allowCustomValues) {

            selected = '';
            var displayNone = ' style="display:none"';
            var customValue = '';

            if (!(value in choices)) {
                selected = ' selected="selected"';
                displayNone = '';
                customValue = value;
            }

            select.append('<option class="custom" value="' + customValue + '"' + selected + '>Custom Value</option>');
            inputBox = $('<input' + displayNone + ' size="80" value="' + customValue + '">');
        }

        p.append(select);
        p.append(inputBox);

        //Ensure events are attached for the custom input box
        if (allowCustomValues) {

            //Hide/show the custom text box if the 'Custom Value' is modded
            select.change(
                function (event) {
                    var select = $(event.target);
                    var textBox = select.parent().children('input');
                    if (select.find(':selected').hasClass('custom')) {
                        textBox.show();
                    } else {
                        textBox.hide();
                    }
                }
            );

            //Move any input box input to the select key value
            inputBox.change(
                function (event) {
                    var textBox = $(event.target);
                    textBox.parent().children('select')
                        .children('option.custom').val(textBox.val());
                }
            );
        }

        return p;
    };

    var methods = {
        image: function (description, src) {

            //Give it a random name so labels and multi-dialogs work
            var name = $.fn.generateUUID();

            if (src == undefined) {
                src = '';
            }

            var p = $('<p class="dialogElement imageInput" data-dialogElementName="' + name + '" style="overflow-y:hidden"></p>');
            p.append(
                '<label for="' + name + '">' + description + '</label><br>' +
                '<img style="max-width:120px !important;max-height:170px !important;float:left;margin-right:10px" src="' + src + '" onerror="this.src=\'/modules/rcm/images/no-image.png\';">'
            );
            var urlBox = $('<input style="width:370px;margin-right:10px" name="' + name + '" value="' + src + '">');
            p.append(urlBox);
            p.append(
                '<button type="button" class="image-button ui-button ui-widget ' +
                'ui-state-default ui-corner-all ui-button-text-only" role="button" ' +
                'aria-disabled="false">' +
                '<span class="ui-button-text">Browse</span>' +
                '</button>'
            );

            if (!inputImageEventsDelegated) {

                inputImageEventsDelegated = true;

                $('body').on(
                    'click', '.imageInput button, .imageInput img',
                    function () {
                        showFileBrowserForInputBox(
                            $(this).parent().children('input')
                            , 'images'
                        );
                    }
                );

                $('body').on(
                    'change', '.imageInput input', function () {
                        $(this).parent().children('img').attr('src', $(this).val());
                    }
                );
            }

            return p;
        },


        /**
         * Build html for a text input
         *
         * @param {String} description title to show user
         * @param {String} value the current value
         *
         * @return String
         */
        text: function (description, value) {

            if (value == undefined) {
                value = '';
            }

            //Give it a random name so labels and multi-dialogs work
            var name = $.fn.generateUUID();

            var p = $(
                '<p class="dialogElement" data-dialogElementName="' + name + '"><label for="' + name + '">' + description + '</label><br>' +
                '<input type="text" name="' + name + '" value="' + value + '"></p>'
            );

            return p;
        },

        /**
         * Build html for a text input
         *
         * @param {String} description title to show user
         * @param {String} value the current value
         *
         * @return String
         */
        textarea: function (description, value) {

            if (value == undefined) {
                value = '';
            }

            //Give it a random name so labels and multi-dialogs work
            var name = $.fn.generateUUID();

            var p = $(
                '<p class="dialogElement dialogElementTextArea" data-dialogElementName="' + name + '"><label for="' + name + '">' + description + '</label><br>' +
                '<textarea style="width:100%;height:6em" name="' + name + '" id="' + name + '">' + value + '</textarea></p>'
            );

            return p;
        },


        /**
         * Build html for a text input
         *
         * @param {String} description title to show user
         * @param {String} value the current value
         *
         * @return String
         */
        url: function (description, value) {

            if (value == undefined) {
                value = '';
            }


            //Give it a random name so labels and multi-dialogs work
            var name = $.fn.generateUUID();

            var p = $('<p class="dialogElement" data-dialogElementName="' + name + '"></p>');

            p.append('<label for="' + name + '">' + description + '</label><br>');
            var input = $('<input type="text" name="' + name + '" value="' + value + '">');
            p.append(input);

            attachPageListAutoComplete(input);

            return p;
        },

        textWithAjaxValidator: function (description, value, urlToValidator, disallowSpaces, successCallback) {

            if (value == undefined) {
                value = '';
            }

            var validatorId = $.fn.generateUUID();
            var name = $.fn.generateUUID();

            var p = $(
                '<p class="dialogElement ajaxTextInput" data-dialogElementName="' + name + '">' +
                '<label for="' + name + '">' + description + '</label><br>' +
                '<span id="' + validatorId + '" style="float: right;"></span> ' +
                '<input type="text" id="' + name + '" name="' + name + '" value="' + value + '"></p>'
            );

            $('body').on(
                'keyup', "#" + name, function () {
                    var validationContainer = $("#" + validatorId);
                    methods.validateInput(
                        this,
                        validationContainer,
                        urlToValidator,
                        disallowSpaces,
                        successCallback
                    );
                }
            );

            return p;
        },

        /**
         * Build html for a password input
         *
         * @param {String} description title to show user
         * @param {String} value the current value
         *
         * @return String
         */
        password: function (description, validationDescription, value) {

            if (value == undefined) {
                value = '';
            }

            //Give it a random name so labels and multi-dialogs work
            var name = $.fn.generateUUID();

            var p = $(
                '<p class="dialogElement" data-dialogElementName="' + name + '"><label for="' + name + '">' + description + '</label><br>' +
                '<input type="password" id="' + name + '"  name="' + name + '" value="' + value + '"></p>'
            );

            //Give it a random name so labels and multi-dialogs work
            var validationName = $.fn.generateUUID();

            var validationP = $(
                '<p class="dialogElement"><label for="' + validationName + '">' + validationDescription + '</label><br>' +
                '<input type="password" id="' + validationName + '" name="' + validationName + '" value="' + value + '"></p>'
            );

            var divId = $.fn.generateUUID();

            var div = $("<div></div>").append(p).append(validationP);

            $('body').on(
                'keyup', "#" + validationName, function () {
                    var passwordField = $("#" + name);
                    var validationField = $("#" + validationName);

                    var password = $(passwordField).val();
                    var validationPassword = $(validationField).val();

                    if (password !== validationPassword) {
                        $(passwordField).addClass('RcmErrorInputHightlight');
                        $(passwordField).removeClass('RcmOkInputHightlight');
                        $(validationField).addClass('RcmErrorInputHightlight');
                        $(validationField).removeClass('RcmOkInputHightlight');
                    } else {
                        $(passwordField).removeClass('RcmErrorInputHightlight');
                        $(passwordField).addClass('RcmOkInputHightlight');
                        $(validationField).removeClass('RcmErrorInputHightlight');
                        $(validationField).addClass('RcmOkInputHightlight');
                    }
                }
            );

            return div;
        },

        /**
         * Build html for a text input
         *
         * @param {String} description title to show user
         * @param {String} value the current value
         *
         * @return String
         */
        date: function (description, value) {

            if (value == undefined) {
                value = '';
            }

            //Give it a random name so labels and multi-dialogs work
            var name = $.fn.generateUUID();

            var p = $(
                '<p class="dialogElement" data-dialogElementName="' + name + '"><label for="' + name + '">' + description + '</label>' +
                '<br></p>'
            );
            var input = $('<input name="' + name + '" value="' + value + '">');
            p.append(input);
            input.datepicker();

            return p;
        },

        /**
         * Follows a more standard interface than the normal "select". This makes it compatible
         * with the newer block system and "field-dialog" editor.
         *
         * Build html for a select drop down box
         *
         * @param {String} description title to show user
         * @param {Object} choices options {value: display, value2: display2}
         * @param {String} [value] current choice key
         * @param {Boolean} [allowCustomValues] allow user to enter custom values that
         *                  are no in the select
         *
         * @return {String}
         */
        selectWithOptions: function (description, value, options) {

            if (!'options' in options) {
                throw '"choices" field missing from options.'
            }

            return selectFieldFunction(
                description,
                options['options'],
                value,
                options['allowCustomValues']
            )
        },

        /**
         * Build html for a select drop down box
         *
         * @param {String} description title to show user
         * @param {Object} choices options {value: display, value2: display2}
         * @param {String} [value] current choice key
         * @param {Boolean} [allowCustomValues] allow user to enter custom values that
         *                  are no in the select
         *
         * @return {String}
         */
        select: selectFieldFunction,


        /**
         * Build a check box
         *
         * @param {String} description name to show user
         * @param {Boolean} checked is it checked?
         *
         * @return {String}
         */
        checkBox: function (description, checked) {

            //Give it a random name so labels and multi-dialogs work
            var name = $.fn.generateUUID();

            var checkedHtml = '';
            if (checked) {
                checkedHtml = ' checked="checked"';
            }
            var p = $(
                '<p class="dialogElement dialogElementCheckBox" data-dialogElementName="' + name + '"><input type="checkbox"' + checkedHtml + ' name="' + name +
                '" value="true" />' + description + '</p>'
            );

            return p;
        },

        /**
         * Build html for a text input
         *
         * Due to ckEditor limitations, this must be called AFTER .dialog is called
         *
         * @param {String} description title to show user
         * @param {String} value the current value
         *
         * @return String
         */
        richEdit: function (description, value) {
            if (value == undefined || value == '' || value == null) {
                value = '<p>&nbsp;</p>';
            }

            var id = $.fn.generateUUID();
            var div = $('<div id="' + id + '" data-rcm-html-edit>' + value + '</div>');
            var p = $(
                '<p class="dialogElement" data-dialogRichEditId="' + id + '">' +
                '<label>' + description + '</label><br>' +
                '</p>'
            );
            p.append(div);
            p.append('<br>');
            setTimeout(
                function () {
                    rcmAdminService.angularCompile(
                        p, function () {
                        }
                    );
                },
                100
            );

            return p;
        },

        validateInput: function (inputField, resultContainer, ajaxPath, disallowSpaces, successCallback) {

            if (typeof(disallowSpaces) == 'undefined') {
                disallowSpaces = false;
            }

            var inputValue = null;

            if (disallowSpaces) {
                /* Get the value of the input field and filter */
                inputValue = $(inputField).val().toLowerCase().replace(
                    /\s/g,
                    '-'
                ).replace(/[^A-Za-z0-9\-\_]/g, "");
                $(inputField).val(inputValue);
            } else {
                inputValue = $(inputField).val();
            }

            /* make sure that the page name is greater then 1 char */
            if (inputValue.length < 1) {
                methods.inputFieldError(inputField, resultContainer);
                $(resultContainer).html('');
                return false;
            }

            /* Check name via rest service */
            var dataOk = false;

            var dataToSend = {
                'checkValue': inputValue
            };

            $.getJSON(
                ajaxPath, dataToSend, function (data) {
                    if (data.dataOk == 'Y') {
                        methods.inputFieldOk(inputField, resultContainer);
                        if (typeof(successCallback) === 'function') {
                            successCallback.call(this, inputValue);
                        }
                    } else if (data.dataOk != 'Y') {
                        methods.inputFieldError(inputField, resultContainer);
                    } else {
                        methods.inputFieldFatalError(inputField, resultContainer);
                    }
                }
            ).error(
                function () {
                    methods.inputFieldFatalError(inputField, resultContainer);
                }
            );

            return dataOk;
        },

        inputFieldError: function (inputField, resultContainer) {
            $(resultContainer).removeClass('ui-icon-check');
            $(resultContainer).addClass('ui-icon-alert').addClass('ui-icon');
            $(inputField).addClass('RcmErrorInputHightlight');
            $(inputField).removeClass('RcmOkInputHightlight');

        },

        inputFieldFatalError: function (inputField, resultContainer) {
            $(resultContainer).html('<p style="color: #FF0000;">Error!</p>');
            $(inputField).addClass('RcmErrorInputHightlight');
            $(inputField).removeClass('RcmOkInputHightlight');
        },

        inputFieldOk: function (inputField, resultContainer) {
            $(resultContainer).removeClass('ui-icon-alert');
            $(resultContainer).addClass('ui-icon-check').addClass('ui-icon');
            $(inputField).removeClass('RcmErrorInputHightlight');
            $(inputField).addClass('RcmOkInputHightlight');
        },

        /**
         * We override val() to use this function so we can get to the element
         * that actually holds the value
         * @param dialogElement
         * @return {*}
         */
        getDialogElementVal: function () {

            var dialogElement = arguments[0];
            var newVal = (arguments[1]) ? arguments[1] : null;

            var name = dialogElement.attr('data-dialogElementName');

            //Get Value if not passed in
            if (newVal == null) {
                if (typeof(name) != 'undefined') {
                    //Used for must input types
                    var inputElement
                        = dialogElement.find('[name="' + name + '"]');
                    if (inputElement.attr('type') == 'checkbox') {
                        return inputElement.is(':checked');
                    } else {
                        return inputElement.val();
                    }
                } else {
                    //For ck editor inputs

                    var editId = dialogElement.attr('data-dialogRichEditId');
                    if (typeof(editId) != 'undefined') {
                        return $('#' + editId).html();
                    }
                }
            } else {
                if (typeof(name) != 'undefined') {
                    //Used for must input types
                    dialogElement.find('[name="' + name + '"]').val(newVal);

                    //Trigger change for images
                    if ($(dialogElement).hasClass('imageInput')) {
                        $(dialogElement).children('img').attr('src', newVal);
                    }

                    return this;
                } else {
                    var editId = dialogElement.attr('data-dialogRichEditId');
                    if (typeof(editId) != 'undefined') {
                        return $('#' + editId).html();
                    }

                    return this;
                }
            }
        }
    };

    /**
     * Holds the original .prop() so we can call it with our modified .prop()
     * @type {Function}
     */
    var originalProp = $.fn.prop;

    /**
     * .Prop over ride for check boxes
     * @param value
     * @constructor
     */
    $.fn.prop = function (propertyName, value) {
        if (this.hasClass('dialogElementCheckBox')) {
            return this.find('input').prop(propertyName, value);
        }

        //Catch all others
        if (typeof value == 'undefined') {
            return originalProp.call(this, propertyName);
        }
        return originalProp.call(this, propertyName, value);
    };

    /**
     * Holds the original .val() so we can call it with our modified .val()
     * @type {Function}
     */
    var originalVal = $.fn.val;


    /**
     * We override val() to use our own function so we can get to the element
     * that actually holds the value. This is also useful for ckEditor.
     * @param value
     * @return {*}
     */
    $.fn.val = function (value) {

        var elementToGetVal = this.find('.dialogElement:first');

        if (elementToGetVal.length == 0 && this.hasClass('dialogElement')) {
            elementToGetVal = this;
        }

        if (elementToGetVal.length > 0) {
            if (typeof value == 'undefined') {
                return methods.getDialogElementVal(elementToGetVal);
            } else {
                return methods.getDialogElementVal(elementToGetVal, value);
            }
        }

        //Catch all others
        if (typeof value == 'undefined') {
            return originalVal.call(this);
        }
        return originalVal.call(this, value);
    };

    /**
     * Follows namespace pollution avoidance advice
     * from http://docs.jquery.com/Plugins/Authoring
     * @param {String} inputType
     * @param {String} label
     * @param [option1]
     * @param [option2]
     * @param [option3]
     * @return {Object}
     */
    $.fn.dialogIn = function (inputType, label, option1, option2, option3) {

        var p;

        // Method calling logic
        if (methods[inputType]) {
            p = methods[inputType].apply(
                this, Array.prototype.slice.call(arguments, 1)
            );
        } else if (typeof inputType === 'object' || !method) {
            p = methods.init.apply(this, arguments);
        } else {
            $.error(
                'Method ' + inputType + ' does not exist on jquery-dialog-inputs'
            );
            return null;
        }

        if (this instanceof jQuery) {
            this.append(p);
            return this;
        } else {
            return p;
        }

    };

    /**
     * Object with input jQuery objects in it
     * @param inputs
     */
    $.fn.appendMulti = function (inputs) {
        for (var key in inputs) {
            this.append(inputs[key]);
        }
        return this;
    };

    /**
     * Generates RFC4122 v4 compliant random ids
     * @return {String}
     */
    $.fn.generateUUID = function () {
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(
            /[xy]/g,
            function (c) {
                var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
                return v.toString(16);
            }
        );
    };
})(jQuery);

/**
 *
 * @param inputType
 * @param description
 * @param value
 * @param options
 * @param depricatedUsedToBeOption3
 */
jQuery.dialogIn = function (inputType, description, value, options, depricatedUsedToBeOption3) {
    return $.fn.dialogIn.apply(this, arguments);
};

jQuery.generateUUID = function () {
    return $.fn.generateUUID();
}
/**
 * </jquery-dialog-inputs>
 */
