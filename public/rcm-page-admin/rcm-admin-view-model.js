/**
 * RcmAdminViewModel
 * @param config
 * @param model RcmAdminModel
 * @param page RcmAdminPage
 * @constructor
 */
var RcmAdminViewModel = function (config, model, page) {

    var self = this;

    self.config = config;

    self.model = model;

    self.page = page;

    self.rcmColunmResize = rcmColunmResize;

    self.rcmPluginDrag = RcmPluginDrag;

    self.RcmPluginViewModel = {

        makeSiteWide: function (container) {
            var pluginName = $.dialogIn('text', 'Plugin Name', '');
            var form = $('<form></form>')
                .append(pluginName)
                .dialog(
                    {
                        title: 'Create Site Wide Plugin',
                        modal: true,
                        width: 620,
                        buttons: {
                            Cancel: function () {
                                $(this).dialog("close");
                            },
                            Ok: {
                                "class": "okButton",
                                text: 'Ok',
                                click: function () {

                                    //Get user-entered data from form
                                    self.model.RcmPluginModel.setIsSitewide(
                                        $(container),
                                        true
                                    );
                                    $(container).attr(
                                        'data-rcmplugindisplayname',
                                        pluginName.val()
                                    );

                                    $(this).dialog("close");
                                }
                            }
                        }
                    }
                );
        },

        /**
         * enableEdit
         * @param elm
         * @param onComplete
         */
        enableEdit: function (elm, onComplete) {

            var id = self.model.RcmPluginModel.getId(elm);

            elm.removeClass('rcmPluginLocked');
            elm.unbind('dblclick');
            elm.attr('editing', true);

            jQuery.contextMenu('destroy', '[data-rcmPluginInstanceId=' + id + ']');

            self.RcmPluginViewModel.createEditableButtons(
                elm,
                function (elm) {
                    self.RcmPluginViewModel.disableLinks(elm, onComplete);
                }
            );
        },

        /**
         * disableEdit
         * @param elm
         * @param type
         * @param onComplete
         */
        disableEdit: function (elm, type, onComplete) {

            var id = self.model.RcmPluginModel.getId(elm);

            var page = self.page;

            var unlock = function () {

                jQuery().confirm(
                    self.config.unlockMessages[type].message,
                    function () {
                        page.setEditingOn(type);
                    },
                    null,
                    self.config.unlockMessages[type].title
                );
            };

            // Add CSS
            elm.addClass('rcmPluginLocked');

            // context menu and double click
            elm.dblclick(unlock);
            //elm.click(unlock);

            jQuery.contextMenu(
                {
                    selector: '[data-rcmPluginInstanceId=' + id + ']',

                    //Here are the right click menu options
                    items: {
                        unlockMe: {
                            name: 'Unlock',
                            icon: 'delete',
                            callback: unlock
                        }
                    }
                }
            );
            self.RcmPluginViewModel.createEditableButtons(
                elm,
                function (elm) {
                    self.RcmPluginViewModel.disableLinks(elm, onComplete);
                }
            );
        },

        /**
         * enableEdit
         * @param elm
         * @param onComplete
         */
        enableArrange: function (elm, onComplete) {

            self.RcmPluginViewModel.createLayoutHelper(elm);

            self.RcmPluginViewModel.enableResize(elm);

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        },

        /**
         * disableArrange
         * @param elm
         * @param onComplete
         */
        disableArrange: function (elm, onComplete) {
            //@todo - remove elements
            var id = self.model.RcmPluginModel.getId(elm);

            jQuery('[id="rcmLayoutEditHelper' + id + '"]').remove();

            elm.hover(
                function () {
                    return false;
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        },

        /**
         * enableResize
         * @param elm
         * @param onComplete
         */
        enableResize: function (elm, onComplete) {

            self.rcmColunmResize.init(elm);

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        },

        /**
         * disableLinks
         * @param elm
         * @param onComplete
         */
        disableLinks: function (elm, onComplete) {
            // Disable normal events
            var donDoIt = function () {
                if (!jQuery(this).hasClass('RcmKeepEnabled')) {
                    return false;
                }
            };
            elm.find('button').unbind();
            elm.find('[role="button"]').unbind();
            elm.find('button').click(donDoIt);
            elm.find('a').click(donDoIt);
            elm.find('form').submit(donDoIt);
            elm.find('form').unbind();

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        },

        /**
         * createLayoutHelper
         * @param elm
         * @param onComplete
         */
        createLayoutHelper: function (elm, onComplete) {

            var id = self.model.RcmPluginModel.getId(elm);

            var page = self.page;

            var isSitewide = self.model.RcmPluginModel.isSitewide(elm);

            var sitewideOption = '';

            if (!isSitewide) {
                sitewideOption = '<li><a href="#" class="rcmSiteWidePluginMenuItem">Mark as site-wide</a></li>';
            }

            var menu = jQuery(
                '<div id="rcmLayoutEditHelper' + id + '" class="rcmLayoutEditHelper">' +
                '</div>'
            );

            var sortableMenu = jQuery(
                ' <div class="rcmHandle sortableMenu" title="Move Plugin">' +
                '   <div class="icon"></div>' +
                ' </div>'
            );

            var containerMenu = jQuery(
                ' <div class="rcmHandle containerMenu" title="Container Menu">' +
                '   <div class="icon"></div>' +
                ' </div>'
            );

            var rcmContainerMenuList = jQuery(
                '  <ul>' +
                '   ' + sitewideOption +
                '   <li><a href="#" class="rcmDeletePluginMenuItem">Delete Plugin</a> </li>' +
                '   <li><a href="#" class="rcmResetSizePluginMenuItem">Reset Size</a> </li>' +
                '  </ul>'
            );

            var rcmContainerMenu = jQuery(
                ' <div class="rcmContainerMenu" title="Container Menu">' +
                ' </div>'
            );

            if (elm.pluginMenu && typeof elm.pluginMenu === 'object') {
                var menuOptionsAElm = null;
                var menuOptionsElm = null;
                jQuery.each(
                    elm.pluginMenu,
                    function (index, value) {
                        menuOptionsAElm = jQuery(
                            '<a href="#" class="rcmPluginMenuItem ' + index + '">' + value.title + '</a>'
                        );
                        menuOptionsElm = jQuery('<li></li>');
                        menuOptionsAElm.click(
                            function() {
                                rcmContainerMenu.hide();
                                value.method();
                            }
                        );
                        menuOptionsElm.append(
                            menuOptionsAElm
                        );
                        rcmContainerMenuList.append(
                            menuOptionsElm
                        );
                    }
                );
            }

            rcmContainerMenu.append(
                rcmContainerMenuList
            );

            rcmContainerMenu.hide();

            rcmContainerMenu.hover(
                function () {
                    rcmContainerMenu.show();
                },
                function () {
                    rcmContainerMenu.hide();
                }
            );
            containerMenu.hover(
                function () {
                    rcmContainerMenu.show();
                },
                function () {
                    rcmContainerMenu.hide();
                }
            );

            menu.append(sortableMenu);

            menu.append(containerMenu);

            menu.append(rcmContainerMenu);

            elm.prepend(menu);

            elm.hover(
                function () {
                    jQuery(this).find(".rcmLayoutEditHelper").each(
                        function () {
                            jQuery(this).show();
                        }
                    );
                },
                function () {
                    jQuery(this).find(".rcmLayoutEditHelper").each(
                        function () {
                            jQuery(this).hide();
                        }
                    )
                }
            );

            elm.find(".rcmDeletePluginMenuItem").click(
                function (e) {
                    // me.layoutEditor.deleteConfirm(this);
                    page.removePlugin(id);

                    page.registerObjects();
                    e.preventDefault();
                    self.rcmPluginDrag.refresh();
                }
            );

            elm.find(".rcmSiteWidePluginMenuItem").click(
                function (e) {
                    self.RcmPluginViewModel.makeSiteWide(
                        jQuery(this).parents(
                            ".rcmPlugin"
                        )
                    );
                    e.preventDefault();
                }
            );

            elm.find(".rcmResetSizePluginMenuItem").click(
                function (e) {
                    self.rcmColunmResize.setClass(
                        elm,
                        self.rcmColunmResize.defaultClass
                    );
                    e.preventDefault();
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        },


        /**
         * createEditableButtons
         * @todo This is currently one-way, if an edit is canceled, buttons are not returned to normal
         * @param elm
         * @param onComplete
         */
        createEditableButtons: function (elm, onComplete) {

            elm.find('button').each(
                function (index, element) {

                    var curElement = jQuery(element);
                    var newElm = jQuery('<div role="button"></div>');

                    var curHtml = curElement.html();
                    if (curHtml) {
                        newElm.html(curHtml);
                    }

                    var curClass = curElement.attr('class');
                    if (curClass) {
                        newElm.attr('class', curClass);
                    }

                    var curId = curElement.attr('id');
                    if (curId) {
                        newElm.attr('id', curId);
                    }

                    var curTextEdit = curElement.attr('data-textedit');
                    if (curTextEdit) {
                        newElm.attr('data-textedit', curTextEdit);
                    }

                    curElement.after(newElm);
                    curElement.remove();
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(elm);
            }
        }
    };

    /**
     * alertDisplay
     * @param alert
     */
    self.alertDisplay = function (alert) {

        if (alert.message.status >= 500 && alert.message.status < 600) {
            alert.message.responseText = 'An error occurred during execution; please try again later.'
        }

        if (!alert.message.statusText) {
            'An error occurred'
        }

        $().alert(alert.message.responseText, null, alert.message.statusText);
    };

    /**
     *
     * @type {{dialog: null, timout: null}}
     */
    self.loadingDialog = {
        dialog: null,
        timout: null
    };
};
