/**
 * Content Manager JS wrapper for TinyMce
 *
 * @type {Object}
 */
function RcmTinyMceEditor(config) {
    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmEdit}
     */
    var me = this;

    /**
     * Set the config items
     *
     * @type {Object}
     */
    me.config = config;

    me.toolbarAdded = false;

    /**
     * Add the toolbars for CKEditor
     */
    me.init = function() {
        me.addHiddenEditorForToolbars();
        me.addTinyMceToolbars();
    };

    /**
     * Initialize edit mode
     */
    me.initEditMode = function() {};


    /**
     * Add Editor for a passed in container
     *
     * @param container Containter to convert to rich edit
     * @param textAreaId ID for textArea
     * @return {*|jQuery}
     */
    me.addRichEditor = function(container, textAreaId) {
        var config = me.config;

        // Add Focus and Blur events to show/hide toolbars
        config.setup = function(ed){
            ed.onInit.add(function(ed){

                $(ed.getDoc()).contents().find('body').focus(function(){
                    me.addFocus(ed.editorId);
                });

                $(ed.getDoc()).contents().find('body').blur(function(){
                    me.addBlur(ed.editorId);
                });
            });
        };

        // Add ElFinder to Editor
        config.file_browser_callback = function(field_name, url, type, win) {
            var elfinder_url = '/elfinder/elfinder.html';    // use an absolute path!
            tinyMCE.activeEditor.windowManager.open({
                file: elfinder_url,
                title: 'elFinder 2.0',
                width: 900,
                height: 450,
                resizable: 'yes',
                inline: 'yes',    // This parameter only has an effect if you use the inlinepopups plugin!
                popup_css: false, // Disable TinyMCE's default popup CSS
                close_previous: 'no'
            }, {
                window: win,
                input: field_name
            });
            return false;
        };


        //Get Current HTML of div area
        var htmlToAddToTextArea = $(container).html();

        //Create the new TextArea for Rich Edits
        var newTextArea = $(
            '<textarea style="width: 100%; height: 100%;" id="'
                +textAreaId
                +'" >'
                +htmlToAddToTextArea
                +'</textarea>'
        );

        //Change container to textarea
        $(container).html(newTextArea);

        //Initiate TinyMce on new textarea
        newTextArea.tinymce(
            config
        );

        return $(newTextArea);
    };

    me.getRichEditorData = function(editor)  {
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


    /*********************************/
    /*      Ugly Toolbar Hacks       */
    /*  Feel Free to Fix.  Please    */
    /*  Commit Changes Back to Main  */
    /*          Project              */
    /*********************************/

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

    /**
     * Add the toolbars to the top of the screen
     */
    me.addTinyMceToolbars = function() {

        var config = me.config;

        /**
         * @todo - Ugly hack to make sure toolbar is loaded before resizing
         *         Needed for FireFox at the moment
         */

        config.setup = function(e) {
            e.onInit.add(function() {
                me.addToolbarsCallback();
            });
        };

        $("#hiddenEditor").tinymce(
            config
        );

    };

    /**
     * Editor Focus Event Handler to show/move toolbar to top admin bar
     *
     * @param containId
     */
    me.addFocus = function (containId) {
        $(".mainToolBar").hide();
        me.moveToolbarToLocation(containId, containId+'main_container');
    };

    /**
     * Editor Blur Event Handler to hide toolbars and show default toolbar
     * @param containId
     */
    me.addBlur = function (containId) {
        $("."+containId+'main_container').hide();
        $(".mainToolBar").show();
    };

    /**
     * Add the defualt toolbar to the top of the page as a place holder.  Used
     * to keep the UI consistent.
     */
    me.addToolbarsCallback = function() {

        if (me.toolbarAdded === true) {
            return;
        }

        me.moveToolbarToLocation("hiddenEditor", 'mainToolBar');
        me.toolbarAdded = true;
    };

    /**
     * Move the selected toolbar to the top of the page.
     *
     * @param containerId
     * @param extraclass
     */
    me.moveToolbarToLocation = function(containerId,extraclass) {

        if($('#ckEditortoolbar').has("#"+containerId+"_external").length > 0) {
            $("."+containerId+'main_container').show();
            return;
        }

        $("#"+containerId+"_external").appendTo('#ckEditortoolbar').wrap("<div class='defaultSkin "+extraclass+"'></div>");

        me.toolbarUglyWorkAroundForAdminMenu(containerId);
    };

    /**
     * REALLY UGLY!!! NEEDS FIXED.  Set an interval because for some reason
     * the toolbar is not completely ready when the init method is called.
     *
     * @todo - This fixes creates a bug that causes the toolbar menu to appear
     * to flash when clicking on editor areas.
     *
     * @param containerId
     */
    me.toolbarUglyWorkAroundForAdminMenu = function(containerId) {
        setInterval(
            function(){
                $("#"+containerId+"_external").show().css({
                    'position':'relative',
                    'top':'0px',
                    'left':'0px'
                });

                $("#ToolBarSpacer").height(
                    $('#ckEditortoolbar').height()
                );
            },
            5
        );
    };

    me.startDrag = function (container) {

    };

    me.stopDrag = function(container) {

    };
}