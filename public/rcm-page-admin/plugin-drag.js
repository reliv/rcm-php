var RcmPluginDrag = {

    /**
     * Sets up everything to do with plugin dragging
     */
    refresh: function () {
        $('html').addClass('rcmArrangingPlugins');
        RcmPluginDrag.setExtraRowCount(1);
        RcmPluginDrag.makePluginsDraggable();
        RcmPluginDrag.makePluginsSortable();
    },

    /**
     * Sets the number of extra empty rows in each container
     * @todo empty must be last one
     * @param {int} extraRowCount send 0 or 1
     */
    setExtraRowCount: function (extraRowCount) {
        $.each(
            $('.rcmContainer'), function () {
                var container = $(this);
                $.each(
                    container.children('.row'), function () {
                        var row = $(this);
                        //If row is empty
                        if (row.children().length == 0) {
                            row.remove();
                        }
                    }
                );
                for (var i = 0; i < extraRowCount; i++) {
                    //do not add extra row at the bottom of GuestTopNav, causes issues
                    if (container.attr('data-containerid') != 'guestTopNavigation') {
                        container.append($('<div class="row"></div>'));
                    }
                }
            }
        );
    },
    /**
     * Make plugins in the layout editor menu draggable
     */
    makePluginsDraggable: function () {
        $(".availablePluginsMenu .rcmPluginDrag").each(
            function () {
                RcmPluginDrag.makePluginItemDragable($(this));
            }
        );
    },

    makePluginItemDragable: function (pluginItem) {
        try {
            pluginItem.draggable('destroy');
        } catch (e) {
            //No problem
        }
        pluginItem.draggable(
            {
                cursorAt: {left: 40, top: 10},
                helper: function () {
                    return RcmPluginDrag.pluginDraggableHelper(this)
                },
                drag: function () {
                    RcmPluginDrag.pluginDraggableDrag(this);
                },
                revert: 'invalid',
                connectToSortable: '.rcmContainer > .row',
                appendTo: 'body'
            }
        );

    },

    /**
     * Callback for Draggable - Helper
     *
     * @param container
     * @return {*|jQuery|HTMLElement}
     */
    pluginDraggableHelper: function (container) {
        var pluginContainer = $(container).find(".rcmPlugin");
        var pluginData = RcmPluginDrag.getPluginContainerInfo(pluginContainer);

        // greater than 0 not sitewide instance
        if (pluginData.instanceId < 0) {
            $(pluginContainer).attr(
                'data-rcmPluginInstanceId',
                pluginData.instanceId * 10
            );
        }
        var helper = $(pluginContainer).clone(false);
        //Get Ajax
        RcmPluginDrag.pluginDraggableStart(helper, pluginContainer);

        return $(helper);
    },

    /**
     * Callback for Draggable - Start. Preforms Ajax Request for new
     * Plugin instance to add to page.
     */
    pluginDraggableStart: function (helper, pluginContainer) {
        var pluginInstanceContainer = $(pluginContainer).find('.rcmPluginContainer');
        if ($(pluginInstanceContainer).html() != '') {
            return;
        }
        var pluginData = RcmPluginDrag.getPluginContainerInfo(pluginContainer);
        var url = '/rcm-admin-get-instance/' + pluginData.pluginName + '/' + pluginData.instanceId;
        //        var url = '/fakePluginInhstanceTrash';
        $.get(
            url,
            function (data) {
                RcmPluginDrag.getInstanceSuccessCallback(
                    data,
                    helper,
                    pluginContainer
                )
            }
        );
    },

    /**
     * Runs after a successful ajax request for a new plugin.
     *
     * @param data
     * @param helper
     * @param pluginContainer
     */
    getInstanceSuccessCallback: function (data, helper, pluginContainer) {
        $(helper).html(data);
        $(pluginContainer).find(".rcmPluginContainer").html(data);
    },

    /**
     * Callback for Draggable - Drag event
     */
    pluginDraggableDrag: function (container) {
        /* This is required for adding items to an empty
         * sortable. the sortable "change" event handles
         * everything else.
         */
        var placeHolder = $('.rcmPluginSortPlaceHolder');
        /*
         * If placeholder exists and has not yet been filled with a plugin
         */
        if (placeHolder.length && !placeHolder.html().length) {
            RcmPluginDrag.pluginDragPlaceHolder($(container).find(".rcmPlugin"));
                }
            },

            /**
             * Fix for containers that have no current plugins.
             *
             * @param container
             */
            pluginDragPlaceHolder: function (container) {
            var placeHolder = $('.rcmPluginSortPlaceHolder');
            // If placeholder exists and has not yet been filled with a plugin
            if (placeHolder.length && !placeHolder.html().length) {
                // Copy plugin css classes
                placeHolder.attr(
                    'class',
                    container.attr('class')
                    + ' rcmPluginSortPlaceHolder'
                );
                // Copy plugin html
                placeHolder.html(container.html());
            }
        },

        /**
         * Makes plugins sortable.
         */
        makePluginsSortable: function () {
            try {
                $(".rcmContainer > .row").sortable('destroy');
            } catch (e) {
                //No problem
            }
            $(".rcmContainer > .row").sortable(
                {
                    connectWith: '.rcmContainer > .row',
                    dropOnEmpty: true,
                    helper: "original",
                    tolerance: 'pointer',
                    placeholder: "rcmPluginSortPlaceHolder",
                    forcePlaceholderSize: false,
                    handle: '.rcmHandle.sortableMenu',
                    change: function (event, ui) {
                        RcmPluginDrag.pluginSortableChange(ui);
                    },
                    receive: function (event, ui) {
                        RcmPluginDrag.pluginSortableReceive(this, ui);
                    },
                    start: function (event, ui) {
                        RcmPluginDrag.pluginSortableStart(ui);
                },
                stop: RcmPluginDrag.pluginSortableStop
            }
        );
    },

    /**
     * Plugin Sortable Change event
     *
     * @param ui
     */
    pluginSortableChange: function (ui) {
        var pluginDiv;
        var placeHolder = $('.rcmPluginSortPlaceHolder');
        if (placeHolder.length && !placeHolder.html().length) {
            if (ui.item.hasClass('rcmPluginDrag')) {
                pluginDiv = $(ui.item).find(".rcmPlugin");
            } else {
                pluginDiv = ui.item;
            }
            placeHolder.attr(
                'class',
                pluginDiv.attr('class') + ' rcmPluginSortPlaceHolder'
            );
            placeHolder.html(pluginDiv.html());
        }
    },

    /**
     * pluginSortableStart
     * @param ui
     */
    pluginSortableStart: function (ui) {
        /* Advise the editor that we are moving it's container */
        var richEdit = $(ui.item).find('[data-richedit]');
        if (richEdit.length > 0) {
            var pluginContainer = $(richEdit).closest('.rcmPlugin');
            //me.rcmPlugins.removeRichEdits(
            //    pluginContainer,
            //    RcmPluginDrag.getPluginContainerInfo(pluginContainer)
            //);
            //me.editor.startDrag(richEdit);
        }
    },

    /**
     * pluginSortableStop
     * @param ui
     */
    pluginSortableStop: function (event, ui) {

        RcmAdminService.getPage().registerObjects();
        setTimeout(
            function () {
                RcmPluginDrag.refresh();//Fix Rows
            }, 100
        );
        return true;
    },
    /**
     * Tells the sortable objects what to do with a new plugin.
     *
     * @param container
     * @param ui
     */
    pluginSortableReceive: function (container, ui) {
        //Get the current Item
        var newItem = $(container).find(".rcmPluginDrag");
        //Find the actual plugin instance
        var initialInstance = $(ui.item).find(".initialState");
        var isPageContainer = $(container).attr('data-isPageContainer') == 'Y';
        var badMsg = 'Site-wide plugins should only be added to the inner page,' +
            ' not the outer layout.';
        var pluginData;

        if ($(initialInstance).is('.initialState')) {
            //New plugin received
            var dragDiv = $(initialInstance).find(".rcmPlugin");

            pluginData = RcmPluginDrag.getPluginContainerInfo(dragDiv);

            var newDiv = dragDiv.clone(false);

            $(newItem).replaceWith($(newDiv));

            if (pluginData.isSiteWide && !isPageContainer) {
                // We were removing the plugin, but now we just warn them
                $().alert(badMsg);
            }

        } else {
            //Existing plugin received
            var plugin = $(ui.item);

            pluginData = RcmPluginDrag.getPluginContainerInfo(plugin);

            if (pluginData.isSiteWide && !isPageContainer) {
                $(ui.sender).sortable('cancel');
                $().alert(badMsg);
                return;
            }
        }

        var page = RcmAdminService.getPage();

        page.registerObjects(
            function () {

                page.registerObjects();
                var rcmAdminPlugin = page.getPlugin(pluginData.instanceId);
                rcmAdminPlugin.updateView();
            }
        );
    },

    /**
     * getPluginContainerInfo
     * @param container
     * @returns {{pluginName: (*|jQuery), isSiteWide: (*|jQuery), instanceId: (*|jQuery), displayName: (*|jQuery)}}
     */
    getPluginContainerInfo: function (container) {
        var pluginContainer = container;
        if (!pluginContainer.hasClass('rcmPlugin')) {
            pluginContainer = container.closest('.rcmPlugin');
        }
        var pluginData = {
            pluginName: $(pluginContainer).attr('data-rcmPluginName'),
            isSiteWide: RcmAdminService.model.RcmPluginModel.isSitewide($(pluginContainer)),
            instanceId: $(pluginContainer).attr('data-rcmPluginInstanceId'),
            displayName: $(pluginContainer).attr('data-rcmPluginDisplayName')
        };
        if (pluginData.displayName != undefined) {
            pluginData.displayName = pluginData.displayName.replace(/\s/g, '-');
        }
        pluginData.editClass = pluginData.pluginName + 'Edit';
        return pluginData;
    }
};
