
/**
 *
 * @param {RcmAdminPlugin} pluginHandler
 * @param {Array} fields
 * @constructor
 */
var RcmBlockEditorFieldDialog = function (pluginHandler, fields) {
    var instanceId = pluginHandler.getInstanceId();
    var instanceConfig;//instanceConfig gets filled in via AJAX call below

    var dialog = new RcmBlockEditorFieldDialogDialog();

    function showEditDialog() {
        dialog.show(instanceConfig, fields, function (newInstanceConfig) {
            this.instanceConfig = newInstanceConfig;
            pluginHandler.preview();//re-render the plugin with it's new instance config
        })
    }

    // console.log(container);
    function attachEditUiListeners() {
        var container = jQuery(rcm.getPluginContainerSelector(instanceId));

        //Double clicking will show properties dialog
        container.unbind('dblclick');//Prevent multiple click handlers from being added
        container.dblclick(showEditDialog);

        //Disabled the a tag while we are editing
        container.find('a').attr('href', 'javascript:void(0)');

        //Add right click menu
        $.contextMenu({
            selector: rcm.getPluginContainerSelector(instanceId),
            //Here are the right click menu options
            items: {
                edit: {
                    name: 'Edit Properties',
                    icon: 'edit',
                    callback: function () {
                        showEditDialog();
                    }
                }
            }
        });
    }

    this.initEdit = function () {
        pluginHandler.getInstanceConfig(
            function (instanceConfigFromServer) {
                instanceConfig = instanceConfigFromServer;
                attachEditUiListeners();
            }
        );
    };

    this.getSaveData = function () {
        return instanceConfig;
    };

    //Fix "this" var in these functions
    this.initEdit = this.initEdit.bind(this);
    this.getSaveData = this.getSaveData.bind(this);
};

RcmBlockEditorFieldDialogDialog = function () {
    var form = $('<form class="simple"></form>');

    this.show = function (instanceConfig, fields, callback) {
        form.html('<span></span>');//Clear any html from previous usage of the form;
        var inputElements = {};
        fields.forEach(function (field) {
            inputElements[field.name] = jQuery.dialogIn(
                field.type,
                field.label,
                instanceConfig[field.name]
            );
            form.append(inputElements[field.name]);
        });
        form.dialog({
                title: 'Properties',
                modal: true,
                width: 620,
                buttons: {
                    Cancel: function () {
                        $(this).dialog("close");
                    },
                    Ok: function () {
                        fields.forEach(function (field) {
                            instanceConfig[field.name] = inputElements[field.name].val();
                        });

                        callback(instanceConfig);

                        $(this).dialog('close');
                    }
                }
            }
        );
    };
};
