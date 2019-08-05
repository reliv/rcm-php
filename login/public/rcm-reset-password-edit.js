/**
 * requires AjaxPluginEditHelper which should be included by rcm-admin
 *
 * RcmCallToActionBox
 *
 * JS for editing RcmCallToActionBox
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @author    Rod McNew <rmcnew@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 */
var RcmResetPasswordEdit = function (instanceId, container, pluginHandler) {

    /**
     * Always refers to this object unlike the 'this' JS variable;
     *
     * @type {RcmCallToActionBoxEdit}
     */
    var me = this;

    /**
     * Settings from db
     * @type {Object}
     */
    var data;

    /**
     * Default settings from config json file
     * @type {Object}
     */
    var defaultData;

    /**
     * @type {AjaxPluginEditHelper}
     */
    var ajaxEditHelper = new AjaxPluginEditHelper(instanceId, container, pluginHandler);

    /**
     * Called by content management system to make this plugin user-editable
     */
    this.initEdit = function () {
        container.find('form').unbind('submit');
        container.find('form').submit(function () {
            return false;
        });

        ajaxEditHelper.ajaxGetInstanceConfigs(
            function (returnedData, returnedDefaultData) {
                data = returnedData;
                defaultData = returnedDefaultData;

                container.dblclick(me.showEditDialog);

                //Add right click menu
                $.contextMenu({
                    selector: rcm.getPluginContainerSelector(instanceId),
                    //Here are the right click menu options
                    items: {
                        edit: {
                            name: 'Edit Properties',
                            icon: 'edit',
                            callback: function () {
                                me.showEditDialog();
                            }
                        }
                    }
                });
            }
        );
    };

    /**
     * Called by content management system to get this plugins data for saving
     * on the server
     *
     * @return {Object}
     */
    this.getSaveData = function () {
        return data;
    };

    /**
     * Displays a dialog box to edit href and image src
     */
    this.showEditDialog = function () {

        var inputGroups = ajaxEditHelper.buildInputGroups(
            [
                'translate'
            ],
            data,
            defaultData
        );

        var tabsDiv = $('' +
            '<div id="tabs">' +
            '    <ul>' +
            '       <li><a href="#tabs-form">Form Text</a></li>' +
            '       <li><a href="#tabs-prospectEmail">Email To Prospect</a></li>' +
            '       <li><a href="#tabs-thank">Thanks Page</a></li>' +
            '   </ul>' +
            '   <div id="tabs-form">' +
            '   </div>' +
            '   <div id="tabs-prospectEmail">' +
            '   </div>' +
            '   <div id="tabs-thank">' +
            '   </div>' +
            '</div>'
        );

        var emailInputs = {
            'prospectEmail': ajaxEditHelper.buildEmailInputGroup(data['prospectEmail'])
        };
        tabsDiv.find('#tabs-prospectEmail')
            .appendMulti(emailInputs['prospectEmail']);

        tabsDiv.find('#tabs-form')
            .append('<h2>Translations:</h2>')
            .appendMulti(inputGroups['translate']);

        tabsDiv.tabs();

        var thankYouInput =
            $.dialogIn('richEdit', 'Thank You Message', data['thankYou']);
        tabsDiv.find('#tabs-thank').append(thankYouInput);

        var form = $('<form></form>')
            .addClass('simple').append(tabsDiv)
            .dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {

                        data = ajaxEditHelper
                            .captureInputGroups(inputGroups, data);

                        data = ajaxEditHelper
                            .captureInputGroups(emailInputs, data);

                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};
