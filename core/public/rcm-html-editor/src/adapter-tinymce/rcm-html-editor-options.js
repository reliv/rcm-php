/**
 * RcmHtmlEditorOptions
 * @param rcmHtmlEditorConfig
 */
var RcmHtmlEditorOptions = function (rcmHtmlEditorConfig) {

    var self = this;

    /**
     * get options based on the config settings
     * @param type
     * @returns {*}
     */
    self.getHtmlOptions = function (type) {

        if (!type) {

            return rcmHtmlEditorConfig.htmlEditorOptions.defaults;
        }

        if (rcmHtmlEditorConfig.htmlEditorOptions[type]) {

            return rcmHtmlEditorConfig.htmlEditorOptions[type]
        }

        return rcmHtmlEditorConfig.htmlEditorOptions.defaults;
    };

    /**
     * build settings based on the attrs and config
     * @param id
     * @param scope
     * @param attrs
     * @param config
     * @returns {{}}
     */
    self.buildHtmlOptions = function (id, scope, attrs, config) {

        var options = {};
        var settings = {};

        if (typeof config !== 'object') {

            config = {};
        }

        if (attrs.htmlEditorOptions) {
            try {
                var attrConfig = scope.$eval(attrs.htmlEditorOptions);
            } catch (e) {

            }

            if (typeof attrConfig === 'object') {

                config = angular.extend(attrConfig, config);
            }
        }

        options = angular.copy(self.getHtmlOptions(attrs.htmlEditorType));

        settings = angular.extend(options, config); // copy(options);

        settings.mode = 'exact';
        settings.elements = id;
        settings.fixed_toolbar = true;

        // set some overrides based on attr html-editor-attached-toolbar
        if (typeof attrs.htmlEditorAttachedToolbar !== 'undefined') {

            settings.inline = true;
            settings.fixed_toolbar_container = rcmHtmlEditorConfig.toolbar_container_prefix + id;
            settings.fixed_toolbar = false;

            // @todo NOT SUPPORTED: attr html-editor-show-hide-toolbar
            //if (typeof attrs.htmlEditorShowHideToolbar !== 'undefined') {
            //    settings.show_hide_toolbar = true;
            //}
        }

        // set some overrides based on attr html-editor-base-url
        if (attrs.htmlEditorBaseUrl) {
            settings.baseUrl = attrs.htmlEditorBaseUrl;
        }

        if (attrs.htmlEditorSize) {
            settings.toolbar_items_size = attrs.htmlEditorSize; // 'small'
        }

        return settings
    };
};