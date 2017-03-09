/**
 * Takes a block configs associative-array and returns a map of them sorted into smaller associative-arrays
 * by their category
 * @param blockConfigs
 * @returns {{}|*}
 */
function rcmAdminSortBlockConfigsByCategory(blockConfigs) {
    blockConfigsByCategory = {};
    for (blockName in blockConfigs) {
        blockConfig = blockConfigs[blockName];
        if (!blockConfigsByCategory[blockConfig.category]) {
            blockConfigsByCategory[blockConfig.category] = {};
        }
        blockConfigsByCategory[blockConfig.category][blockConfig.name] = blockConfig;
    }
    return blockConfigsByCategory;
}

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

            var accordion = $('<div class="panel-group panel-minified-hide" id="availablePluginsGroup">');
            menu.append(accordion);
            menu.draggable({cancel: '.panel-group'});
            var categoryIndex = 0;
            var newInstanceId = 0;


            $.each(
                rcmAdminSortBlockConfigsByCategory(window.rcmBlockConfigs),
                function (category, plugins) {

                    var collapseId = 'availablePluginsCollapse' + categoryIndex;

                    var group = $('<div class="panel panel-default"></div>');
                    group.appendTo(accordion);

                    var link = $('<a class="panel-link"></a>');
                    link.appendTo(group);
                    link.attr('data-parent', '#availablePluginsGroup');
                    link.attr('data-toggle', 'collapse');
                    link.attr('href', '#' + collapseId);

                    var heading = $('<div class="panel-heading"></div>');
                    heading.appendTo(link);

                    var title = $('<h4 class="panel-title"></h4>');
                    title.appendTo(heading);
                    title.html(category);

                    var collapse = $('<div class="panel-collapse collapse"></div>');
                    collapse.appendTo(group);
                    collapse.attr('id', collapseId);

                    var collapseBody = $('<div class="panel-body"></div>');
                    collapse.append(collapseBody);

                    $.each(
                        plugins,
                        function (displayNameStr, pluginInfo) {
                            newInstanceId--;
                            var instanceId = newInstanceId;
                            var plugin = $('<div class="rcmPluginDrag panel-inner"></div>');
                            plugin.appendTo(collapseBody);
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
                                'rcmPlugin ' + pluginInfo.name
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

                            var innerContainer = $('<div class="rcmPluginContainer">');
                            innerContainer.appendTo(outerContainer);
                        }
                    );
                    categoryIndex++;
                }
            );

        } else {

            RcmAvailablePluginsMenu.menu.remove();
            RcmAvailablePluginsMenu.menu = null;
        }
    }
};


