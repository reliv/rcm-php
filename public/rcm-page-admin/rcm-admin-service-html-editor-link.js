/**
 * getHtmlEditorLink - creates an angular friendly method
 * @param rcmHtmlEditorInit
 * @param rcmHtmlEditorDestroy
 * @returns {Function}
 */
RcmAdminService.getHtmlEditorLink = function (rcmHtmlEditorInit, rcmHtmlEditorDestroy, directiveId) {

    return function (tElem) {

        var page = RcmAdminService.getPage();

        return function (scope, elm, attrs, ngModel) {

            var config = null;

            // global check for extra options, these will merge with the current
            // option presets
            if (typeof RcmThemeConfig == 'object' && typeof RcmThemeConfig.rcmAdminHtmlEditorOptions == 'object') {
                config = RcmThemeConfig.rcmAdminHtmlEditorOptions;
            }

            scope.rcmAdminPage = page;

            var localId = attrs[directiveId];

            var toggleEditors = function () {

                var pluginId = elm.attr('html-editor-plugin-id');

                // if (pluginId)

                if (!page.plugins[pluginId]) {
                    return;
                }

                if (page.plugins[pluginId].canEdit()) {

                    rcmHtmlEditorInit(
                        scope,
                        elm,
                        attrs,
                        ngModel,
                        config
                    );
                } else {

                    rcmHtmlEditorDestroy(
                        attrs.id
                    );
                }
            };

            page.events.on(
                'editingStateChange',
                toggleEditors
            );


            page.events.on(
                'updateView',
                toggleEditors
            );
        }
    }
};
