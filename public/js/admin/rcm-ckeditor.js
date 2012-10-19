/**
 * Content Manager JS wrapper for CKeditor
 *
 * @type {Object}
 */
function RcmCkEditor(config) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmEdit}
     */
    var me = this;

    /**
     * Set the config items
     *
     * @type {String}
     */
    me.config = config;

    /**
     * Add the toolbars for CKEditor
     */
    me.init = function() {
        me.addHiddenEditorForToolbars();
        me.addCkToolbars();
    };

    /**
     * Initialize edit mode
     */
    me.initEditMode = function() {};

    /**
     * Add the CKEditor Toolbars
     */
    me.addCkToolbars = function() {
        //Draw the toolbars and hide the editor
        $("#hiddenEditor").ckeditor(function() {
                var cmTopAdminPanel = $("#ContentManagerTopAdminPanel");

                $("#ToolBarSpacer").height(
                    cmTopAdminPanel.height()
                );

                cmTopAdminPanel.find('[role="button"]').each(function(){
                    $(this).addClass('cke_disabled');
                    $(this).unbind('click').attr('onclick', null).attr('onkeydown', null);
                });
            },
            me.config
        );

    };

    /**
     * Setup Rich Edits.
     *
     * @param container
     * @param textAreaId
     * @return {*|jQuery}
     */
    me.addRichEditor = function(container, textAreaId) {
        //Get Current HTML of div area
        var htmlToAddToTextArea = $(container).html();

        var newTextAres = $('<textarea id="'+textAreaId+'" >'+htmlToAddToTextArea+'</textarea>');

        $(container).html(newTextAres);

        newTextAres.ckeditor(me.config);

        return $(newTextAres).ckeditorGet();
    };

    me.getRichEditorData = function(editor)  {
        var returnData = {};

        if ($.isFunction(editor.getData)) {
            returnData.html = editor.getData();

            if (returnData == undefined || returnData == '') {
                return false;
            }

            returnData.assets = me.getAssets(returnData.html);

            return returnData;
        }

        return false;
    };

    /**
     * Setup HTML5 edits.
     *
     * @param container
     * @param textAreaId
     * @return {*}
     */
    me.addHtml5Editor = function(container, textAreaId) {

        //Used to keep IDE from whining.
        $('#'+textAreaId);

        $(container).attr('contentEditable',true).css('cursor','text');

        return container
    };

    /**
     * Get data from an HTML5 edit area.
     *
     * @param editor
     * @return {*}
     */
    me.getHtml5EditorData = function(editor)  {

        var returnData = {};

        returnData.html = $(editor).html();

        if (returnData == undefined || returnData == '') {
            return false;
        }

        returnData.assets = me.getAssets(returnData.html);

        return returnData;
    };

    me.getAssets = function (htmlToCheck) {

        var assets = [];

        //Record what assets this ckEdit is using
        var html=$('<div></div>');
        html.append(htmlToCheck);

        html.find('img').each(function(key, ele){
            assets.push(
                $(ele).attr('src')
            );
        });

        html.find('a').each(function(key, ele){
            assets.push(
                $(ele).attr('href')
            );
        });

        html.find('embed').each(function(key, ele){
            assets.push(
                $(ele).attr('src')
            );
        });

        return assets;
    };

    /**
     * Add a hidden editor to keep the toolbars in view.
     */
    me.addHiddenEditorForToolbars = function() {
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
            } catch (err) {return}

            var textAreaId = $(textarea).attr('id');
            editor.destroy();
            $("#hiddenEditor").ckeditorGet().focus();
            var tempDiv = $('<div class="tempDragEditorDiv" id="'+textAreaId+'"></div>');
            $(tempDiv).html(ckData);
            $(textarea).replaceWith(tempDiv);
        }
    }

    me.stopDrag = function(container) {
        var tempDiv = $(container).find('.tempDragEditorDiv');

        if (tempDiv.length > 0) {
            var textAreaId = $(tempDiv).attr('id');
            var ckData = $(tempDiv).html();
            var replacementTextarea = $('<textarea id="'+textAreaId+'"></textarea>');
            $(replacementTextarea).html(ckData);
            $(tempDiv).replaceWith(replacementTextarea);
            $("#"+textAreaId).ckeditor(rcmCkConfig).focus();
        }
    }
}
