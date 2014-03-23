/**
 * Content Manager JS wrapper for CKeditor
 *
 * @type {Object}
 */
function RcmCkEditor(config) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     */
    var me = this;

    /**
     * Set the config items
     */
    me.config = config;

    /**
     * Add the toolbars for CKEditor
     */
    me.init = function () {
        CKEDITOR.disableAutoInline = true;
        me.addHiddenEditorForToolbars();
        me.addCkToolbars();

        /*This ugly hack prevents ckeditors from vanishing when resizing google
         chrome's window or developer tools */
        $(window).resize(function () {
            setTimeout(
                function () {
                    $('iframe').attr('style', 'height:100%;width:100%;')
                },
                100
            );
        });
    };

    /**
     * Initialize edit mode
     */
    me.initEditMode = function () {
    };

    /**
     * Add the CKEditor Toolbars
     */
    me.addCkToolbars = function () {
        var editor = CKEDITOR.inline('hiddenEditor', me.config);
        editor.on("instanceReady", function (event) {

            var cmTopAdminPanel = $("#ContentManagerTopAdminPanel");
            $("#ToolBarSpacer").height(
                cmTopAdminPanel.height()
            );
        });
        //Draw the toolbars and hide the editor
//        $("#hiddenEditor").ckeditor(function() {
//                var cmTopAdminPanel = $("#ContentManagerTopAdminPanel");
//
//                $("#ToolBarSpacer").height(
//                    cmTopAdminPanel.height()
//                );
//
//                cmTopAdminPanel.find('[role="button"]').each(function(){
//                    $(this).addClass('cke_disabled');
//                    $(this).unbind('click').attr('onclick', null).attr('onkeydown', null);
//                });
//            },
//            me.config
//        );

    };

    me.addRichEditor = function (container, textAreaId, instanceId) {
        //Hack to keep CKEdits the correct size
//        var parent = $(container).parent();
//        $(parent).width($(parent).width());

        //Get Current HTML of div area
        var htmlToAddToTextArea = $(container).html();

        var newTextAres = $('<div id="' + instanceId + '_' + textAreaId + '" contenteditable="true">' + htmlToAddToTextArea + '</div>');

        $(container).html(newTextAres);

        var editor = CKEDITOR.inline(instanceId + '_' + textAreaId, me.config);

        return editor;
    };

    me.getRichEditorData = function (editor) {
        var returnData = {};

        if ($.isFunction(editor.getData)) {
            returnData.html = editor.getData();

            if (returnData == undefined || returnData == '') {
                return false;
            }

            return returnData;
        }

        return false;
    };

    me.addHtml5Editor = function (container, textAreaId, instanceId) {

        //Used to keep IDE from whining.
        //$('#'+textAreaId);

        $(container).attr('contentEditable', true)
            .attr('id', instanceId + '_' + textAreaId).css('cursor', 'text');

        if ($(container).is('div')
            || $(container).is('p')
            ) {
            var editor = CKEDITOR.inline(instanceId + '_' + textAreaId, me.config);

            return editor
        }

        $(container).attr('contentEditable', true).css('cursor', 'text');

        return container
    };

    /**
     * Converts a given element to a rich edit. WARNING: changes ele ID
     * @param ele
     * @return {*}
     */
    me.convertToHtml5Editor = function (ele) {
        var id = $.fn.generateUUID();
        ele.attr('contenteditable', true)
            .attr('id', id)
            .css('cursor', 'text');
        CKEDITOR.inline(id, me.config);
    };

    /**
     * Get data from an HTML5 edit area.
     *
     * @param editor
     * @return {*}
     */
    me.getHtml5EditorData = function (editor) {

        if ($.isFunction(editor.getData)) {
            return me.getRichEditorData(editor);
        }

        var returnData = {};
        returnData.html = $(editor).html();

        return returnData;

    };

    /**
     * Add a hidden editor to keep the toolbars in view.
     */
    me.addHiddenEditorForToolbars = function () {
        var hiddenEditor = $('<div id="hiddenEditor"></div>');
        var hiddenEditorContainer = $('<div id="hiddenEditorContainer"' +
            'style="' +
            'position: fixed; top: ' +
            '0px; left:-999999px; ' +
            'height:20px;"' +
            '>' +
            '</div>');

        $(hiddenEditorContainer).append(hiddenEditor);
        $("body").append(hiddenEditorContainer);
    };

    me.startDrag = function (container) {
        var textarea = $(container).find("textarea");

        if (textarea.length > 0) {
            var editor = textarea.ckeditorGet();
            try {
                var ckData = editor.getData();
            } catch (err) {
                return
            }

            editor.destroy();
            $(container).html(ckData);


        }
    };

    me.stopDrag = function (container) {
        var tempDiv = $(container).find('.tempDragEditorDiv');

        if (tempDiv.length > 0) {
            var textAreaId = $(tempDiv).attr('id');
            var ckData = $(tempDiv).html();
            var replacementTextarea = $('<textarea id="' + textAreaId + '"></textarea>');
            $(replacementTextarea).html(ckData);
            $(tempDiv).replaceWith(replacementTextarea);
            $("#" + textAreaId).ckeditor(rcmCkConfig).focus();
        }
    };
}
