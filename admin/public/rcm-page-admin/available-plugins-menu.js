var RcmAvailablePluginsMenu = {

    menu: null,

    build: function (callback) {
        if (!RcmAvailablePluginsMenu.menu) {

            RcmAvailablePluginsMenu.menu = $('<div class="availablePluginsMenu panel panel-default"></div>');
            var menu = RcmAvailablePluginsMenu.menu;
            menu.minified = false;
            menu.toggleMinified = function () {
                menu.minified = !menu.minified;
                if (menu.minified) {
                    menu.find('.panel-minified-hide').hide();
                    menu.find('.panel-minified-show').show();
                } else {
                    menu.find('.panel-minified-hide').show();
                    menu.find('.panel-minified-show').hide();
                }
            };
            $('body').prepend(menu);
            menu.css('top', $('.rcmAdminPanelWrapper').height());
            /* HEADER */
            var header = $('<div class="panel-header"></div>');
            var heading = $('<div class="panel-heading"></div>');
            header.append(heading);
            menu.append(header);

            var headingH = $(
                '<span role="button">'
                + '<span role="button" class="glyphicon glyphicon-menu-right panel-minified-show"></span>'
                + '<span role="button" class="glyphicon glyphicon-menu-down panel-minified-hide"></span>'
                + '&nbsp;Blocks (drag onto page)'
                + '</span>'
            );
            headingH.click(menu.toggleMinified);
            heading.append(headingH)

            var pluginListEle = $('<div class="panel-group panel-minified-hide" id="availablePluginsGroup">');
            menu.append(pluginListEle);
            menu.draggable({cancel: '.panel-group'});
            var categoryIndex = 0;
            var newInstanceId = 0;
            $.each(
                Object.values(window.rcmBlockConfigs).sort(function (a, b) {
                    /**
                     * Put the most used CMS block at the top.
                     */
                    if (a.name === 'RcmHtmlArea') {
                        return -1;
                    }
                    if (b.name === 'RcmHtmlArea') {
                        return 1;
                    }
                    var textA = a.label.toUpperCase();
                    var textB = b.label.toUpperCase();
                    return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
                }),
                function (pluginInfoIndex, pluginInfo) {
                    var displayNameStr = pluginInfo.name
                    newInstanceId--;
                    var instanceId = newInstanceId;
                    var plugin = $('<div class="rcmPluginDrag panel-inner"></div>');
                    plugin.appendTo(pluginListEle);
                    plugin.data('pluginName', pluginInfo.name);

                    // var icon = $('<img>');
                    // var iconSrc = pluginInfo.icon;
                    // if (!iconSrc) {
                    //     iconSrc = '/modules/rcm/images/no-plugin-icon.png';
                    // }
                    // icon.attr('src', iconSrc);
                    // icon.appendTo(plugin);
                    var displayName = $('<span></span>');
                    displayName.appendTo(plugin);
                    displayName.html(pluginInfo.label);
                    displayName.attr('data-toggle', 'tooltip')
                    displayName.attr('title', pluginInfo.description)
                    displayName.tooltip();

                    var initialState = $('<div class="initialState"></div>');
                    initialState.css('display', 'none');
                    initialState.appendTo(plugin);

                    var colClass = 'col-sm-12';
                    var outerContainer = $('<div class="rcmPlugin">');
                    outerContainer.addClass(pluginInfo.name);
                    outerContainer.addClass(colClass);
                    outerContainer.attr(
                        'data-rcmPluginDefaultClass',
                        'content-block rcmPlugin ' + pluginInfo.name
                    );
                    outerContainer.attr(
                        'editing',
                        true
                    );
                    outerContainer.attr(
                        'data-rcmPluginInstanceId',
                        instanceId
                    );
                    outerContainer.attr(
                        'data-rcmPluginName',
                        pluginInfo.name
                    );
                    outerContainer.attr(
                        'data-rcmplugincolumnclass',
                        colClass
                    );
                    outerContainer.attr(
                        'data-rcmpluginrownumber',
                        '0'
                    );
                    outerContainer.appendTo(initialState);

                    var innerContainer = $('<div class="content-block-container rcmPluginContainer">');
                    innerContainer.appendTo(outerContainer);
                }
            );
            menu.toggleMinified();// make it start out minimized so its out of the way for most edits
        } else {

            RcmAvailablePluginsMenu.menu.remove();
            RcmAvailablePluginsMenu.menu = null;
        }
    }
};


