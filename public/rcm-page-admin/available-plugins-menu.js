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
            var minify = $('<div class="panel-minify"></div>');
            var plus = $('<div class="glyphicon glyphicon-plus panel-minified-show"></div>');
            plus.click(menu.toggleMinified);
            var minus = $('<div class="glyphicon glyphicon-minus panel-minified-hide"></div>');
            minus.click(menu.toggleMinified);
            minify.append(plus);
            minify.append(minus);
            header.append(minify);

            var heading = $('<div class="panel-heading"><h1>Available Plugins</h1></div>');
            header.append(heading);
            menu.append(header);

            var pluginListEle = $('<div class="panel-group panel-minified-hide" id="availablePluginsGroup">');
            menu.append(pluginListEle);
            menu.draggable({cancel: '.panel-group'});
            var categoryIndex = 0;
            var newInstanceId = 0;

            $.each(
                Object.values(window.rcmBlockConfigs).sort(function (a, b) {
                    var textA = a.label.toUpperCase();
                    var textB = b.label.toUpperCase();
                    if (a.name == 'RcmHtmlArea') {
                        /**
                         * Is a bit wierd but this puts the most used plugin at the top
                         * so admins don't freak out. The next version of the CMS will have
                         * a search box that should aleviate problems like this.
                         */
                        return -1;
                    }
                    return (textA < textB) ? -1 : (textA > textB) ? 1 : 0;
                }),
                function (pluginInfoIndex, pluginInfo) {
                    var displayNameStr = pluginInfo.name
                    newInstanceId--;
                    var instanceId = newInstanceId;
                    var plugin = $('<div class="rcmPluginDrag panel-inner"></div>');
                    plugin.appendTo(pluginListEle);
                    plugin.data('pluginName', pluginInfo.name);

                    var icon = $('<img>');
                    var iconSrc = pluginInfo.icon;
                    if (!iconSrc) {
                        iconSrc = '/modules/rcm/images/no-plugin-icon.png';
                    }
                    icon.attr('src', iconSrc);
                    icon.appendTo(plugin);
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

        } else {

            RcmAvailablePluginsMenu.menu.remove();
            RcmAvailablePluginsMenu.menu = null;
        }
    }
};


