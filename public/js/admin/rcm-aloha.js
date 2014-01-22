/**
 * Content Manager JS wrapper for Aloha
 *
 * This WYSIWYG editor is NOT Ready for the CMS.  There is too much new
 * development going on to implement.  This file is here as a place holder
 * so once the editor is ready we'll be implementing this one as well.
 *
 * @type {Object}
 */
function RcmAlohaEditor(config) {
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
        alert("This Editor is NOT ready for the CMS!  Please select another editor for the time being.")
    };

    /**
     * Add the toolbar menu and keep it in view
     */

    me.addAlohaToolbars = function() {
        Aloha.ready( function() {
            Aloha.jQuery('#hiddenEditorContainer').aloha();
        });


    };

    /**
     * Add a hidden editor to keep the toolbars in view.
     */
    me.addHiddenEditorForToolbars = function() {
        var hiddenEditor = $('<div id="hiddenEditor"></div>');
        var hiddenEditorContainer = $('<div id="hiddenEditorContainer"' +
            'style="' +
            'position: fixed; top: ' +
            '200px; left:200px; ' +
            'height:220px;' +
            'width:220px;' +
            'border: 1px solid #000000;' +
            '"' +
            '>' +
            '</div>');

        $(hiddenEditorContainer).append(hiddenEditor);
        $("body").append(hiddenEditorContainer);
    };
}
