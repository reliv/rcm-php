/**
 * RcmAdminModel
 * @constructor
 */
var RcmAdminModel = function () {

    var self = this;

    /**
     * RcmPageModel
     */
    self.RcmPageModel = {

        /**
         * getDocument
         * @param onComplete
         * @returns {*}
         */
        getDocument: function (onComplete) {

            var doc = jQuery(document);

            if (typeof onComplete === 'function') {
                onComplete(doc)
            }

            return doc;
        },

        /**
         * getElm
         * @param onComplete
         * @returns {*}
         */
        getElm: function (onComplete) {

            var elm = jQuery('body');

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        /**
         * getData
         * @param onComplete
         * @returns {{}}
         */
        getData: function (onComplete) {

            var data = JSON.parse(jQuery('meta[property="rcm:page"]').attr('content'));

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        },

        /**
         * setData
         * @param data
         * @param onComplete
         * @returns {*}
         */
        setData: function (data, onComplete) {

            var currentData = self.RcmPageModel.getData();

            var newData = jQuery.extend(true, currentData, data);

            self.RcmPageModel.buildPageMeta(newData);

            var tagData = JSON.stringify(newData);

            jQuery('meta[property="rcm:page"]').attr('content', tagData);

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        },

        /**
         * buildPageMeta
         * @param data
         * @param onComplete
         * @returns {*}
         */
        buildPageMeta: function (data, onComplete) {

            var headElm = jQuery('head');

            var titleElm = headElm.find('title');
            //if title tag doesn't exists then adding it to head
            if (headElm.find('title').length == 0) {

                titleElm = jQuery('title');
                headElm.append(titleElm);
            }

            headElm.find('title').html(data.site.title + ' - ' + data.page.title);

            var metaDesciption = headElm.find('meta[name="description"]');

            //if meta description doesn't exists then adding it to head
            if (metaDesciption.length == 0) {

                metaDesciption = jQuery('<meta>');
                metaDesciption.attr('name', 'description');
                headElm.append(metaDesciption);
            }

            metaDesciption.attr('content', data.page.description);

            var metaKeywords = headElm.find('meta[name="keywords"]');

            //if meta keywords doesn't exists then adding it to head
            if (metaKeywords.length == 0) {

                metaKeywords = jQuery('<meta>');
                metaKeywords.attr('name', 'keywords');
                jQuery('head').append(metaKeywords);
            }

            metaKeywords.attr('content', data.page.keywords);

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        }
    };

    /**
     * RcmContainerModel
     */
    self.RcmContainerModel = {

        /**
         * getElms
         * @param onComplete
         * @returns {*}
         */
        getElms: function (onComplete) {

            var pageElm = self.RcmPageModel.getElm();

            var elms = pageElm.find('[data-containerId]');

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms;
        },

        /**
         * getElm
         * @param containerId
         * @param onComplete
         * @returns {*}
         */
        getElm: function (containerId, onComplete) {

            var pageElm = self.RcmPageModel.getElm();

            var elm = pageElm.find("[data-containerId='" + containerId + "']");

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        /**
         * getId
         * @param containerElm
         * @param onComplete
         * @returns {*}
         */
        getId: function (containerElm, onComplete) {

            var id = containerElm.attr('data-containerId');

            if (typeof onComplete === 'function') {
                onComplete(id)
            }

            return id;
        },

        /**
         * getData
         * @param containerId
         * @param onComplete
         * @returns {{}}
         */
        getData: function (containerId, onComplete) {

            var data = {};

            var elm = self.RcmContainerModel.getElm(containerId);

            data.id = containerId;

            data.revision = elm.attr('data-containerRevision');

            if (elm.attr('data-isPageContainer') == 'Y') {
                data.type = 'page';
            } else {
                data.type = 'layout';
            }

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        }
    };

    /**
     * RcmPluginModel
     */
    self.RcmPluginModel = {

        /**
         * getPluginContainerSelector
         * @param pluginId
         * @returns {string}
         */
        getPluginContainerSelector: function (pluginId) {

            return ('[data-rcmPluginInstanceId="' + pluginId + '"] .rcmPluginContainer');
        },

        /**
         * getElms
         * @param containerId
         * @param onComplete
         * @returns {*}
         */
        getElms: function (containerId, onComplete) {

            var containerElm = self.RcmContainerModel.getElm(containerId);

            var elms = containerElm.find('[data-rcmPluginInstanceId]');

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms;
        },

        /**
         * getElm
         * @param containerId
         * @param pluginId
         * @param onComplete
         * @returns {*}
         */
        getElm: function (containerId, pluginId, onComplete) {

            var containerElm = self.RcmContainerModel.getElm(containerId);

            var elm = containerElm.find('[data-rcmPluginInstanceId="' + pluginId + '"]');

            elm = jQuery(elm[0]);

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        /**
         * deleteElm
         * @param containerId
         * @param pluginId
         * @param onComplete
         * @returns {*}
         */
        deleteElm: function (containerId, pluginId, onComplete) {

            var elm = self.RcmPluginModel.getElm(containerId, pluginId);

            elm.remove();

            if (typeof onComplete === 'function') {
                onComplete(elm)
            }

            return elm;
        },

        /**
         * setId AKA InstanceId
         * @param pluginElm
         * @param value
         * @param onComplete
         * @returns {*}
         */
        setId: function (pluginElm, value, onComplete) {

            pluginElm.attr('data-rcmPluginInstanceId', value);

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * getId AKA InstanceId
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getId: function (pluginElm, onComplete) {

            var id = pluginElm.attr('data-rcmPluginInstanceId');

            if (typeof onComplete === 'function') {
                onComplete(id)
            }

            return id;
        },

        /**
         * setSitewideName AKA DisplayName
         * @param pluginElm
         * @param value
         * @param onComplete
         * @returns {*}
         */
        setSitewideName: function (pluginElm, value, onComplete) {

            pluginElm.attr('data-rcmPluginDisplayName', value);

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * getSitewideName AKA DisplayName
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getSitewideName: function (pluginElm, onComplete) {

            var value = pluginElm.attr('data-rcmPluginDisplayName');

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },
        /**
         * getName
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getName: function (pluginElm, onComplete) {

            var name = pluginElm.attr('data-rcmPluginName');

            if (typeof onComplete === 'function') {
                onComplete(name)
            }

            return name;
        },

        /**
         * setRowNumber
         * @param pluginElm
         * @param rowNumber
         * @param onComplete
         * @returns {*}
         */
        setRowNumber: function (pluginElm, rowNumber, onComplete) {

            // @todo If this is used, then we need to move the elm in the DOM too and update css
            pluginElm.attr('data-rcmPluginRowNumber', rowNumber);

            if (typeof onComplete === 'function') {
                onComplete(rowNumber)
            }

            return rowNumber;
        },

        /**
         * getRowNumber
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getRowNumber: function (pluginElm, onComplete) {

            var value = pluginElm.parent().index();

            // Sync value for server
            pluginElm.attr('data-rcmPluginRowNumber', value);

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * setClass
         * @param pluginElm
         * @param value
         * @param onComplete
         * @returns {*}
         */
        setClass: function (pluginElm, defaultClass, columnClass, onComplete) {

            var value = defaultClass + ' ' + columnClass;

            pluginElm.attr('class', value);

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * getClass
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getClass: function (pluginElm, onComplete) {

            var value = pluginElm.attr('class');

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * setCustomClass
         * @param pluginElm
         * @param value
         * @param onComplete
         * @returns {*}
         */
        setDefaultClass: function (pluginElm, value, onComplete) {

            pluginElm.attr('data-rcmPluginDefaultClass', value);

            var columnClass = self.RcmPluginModel.getColumnClass(pluginElm);

            self.RcmPluginModel.setClass(pluginElm, value, columnClass);

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * getDefaultClass
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getDefaultClass: function (pluginElm, onComplete) {

            var value = pluginElm.attr('data-rcmPluginDefaultClass');

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * setColumnClass
         * @param pluginElm
         * @param value
         * @param onComplete
         * @returns {*}
         */
        setColumnClass: function (pluginElm, value, onComplete) {

            pluginElm.attr('data-rcmPluginColumnClass', value);

            var defaultClass = self.RcmPluginModel.getDefaultClass(pluginElm);

            self.RcmPluginModel.setClass(pluginElm, defaultClass, value);

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * getColumnClass
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getColumnClass: function (pluginElm, onComplete) {

            var value = pluginElm.attr('data-rcmPluginColumnClass');

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * getOrder
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getOrder: function (pluginElm, onComplete) {

            var value = pluginElm.index();

            pluginElm.attr('data-rcmPluginRenderOrderNumber', value);

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * setIsSitewide
         * @param pluginElm
         * @param value
         * @param onComplete
         * @returns {*}
         */
        setIsSitewide: function (pluginElm, value, onComplete) {

            if (value) {
                value = 1;
                pluginElm.attr('data-rcmSiteWidePlugin', value);

            } else {
                value = 0;
                pluginElm.attr('data-rcmSiteWidePlugin', null);
            }

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * isSitewide
         * @param pluginElm
         * @param onComplete
         * @returns {boolean}
         */
        isSitewide: function (pluginElm, onComplete) {

            var value = (pluginElm.attr('data-rcmSiteWidePlugin') == '1' || pluginElm.attr(
                'data-rcmSiteWidePlugin'
            ) == 'Y');

            if (typeof onComplete === 'function') {
                onComplete(value)
            }

            return value;
        },

        /**
         * getData
         * @param containerId
         * @param id AKA InstanceId
         * @param onComplete
         * @returns {{}}
         */
        getData: function (containerId, id, onComplete) {

            var data = {};

            var elm = self.RcmPluginModel.getElm(containerId, id);

            data.containerId = containerId;

            data.instanceId = self.RcmPluginModel.getId(elm);

            data.isSitewide = self.RcmPluginModel.isSitewide(elm);

            data.name = self.RcmPluginModel.getName(elm);

            data.sitewideName = self.RcmPluginModel.getSitewideName(elm);

            data.columnClass = self.RcmPluginModel.getColumnClass(elm);

            data.rowNumber = self.RcmPluginModel.getRowNumber(elm);

            if (typeof onComplete === 'function') {
                onComplete(data)
            }

            return data;
        },
        /**
         * getPluginContainer
         * @param pluginElm
         * @param onComplete
         * @returns {*}
         */
        getPluginContainer: function (pluginElm, onComplete) {

            var pluginContainerElm = pluginElm.find('.rcmPluginContainer');

            if (typeof onComplete === 'function') {
                onComplete(pluginContainerElm)
            }

            return pluginContainerElm;
        },
        /**
         * getEditorElms
         * @param containerId
         * @param pluginId
         * @param onComplete
         * @returns {{}}
         */
        getEditorElms: function (containerId, pluginId, onComplete) {

            var elm = self.RcmPluginModel.getElm(containerId, pluginId);

            var richEditors = elm.find('[data-richEdit]');
            var textEditors = elm.find('[data-textEdit]');

            var elms = {};

            richEditors.each(
                function (index) {
                    elms[jQuery(this).attr('data-richEdit')] = this;
                }
            );

            textEditors.each(
                function (index) {
                    elms[jQuery(this).attr('data-textEdit')] = this;
                }
            );

            if (typeof onComplete === 'function') {
                onComplete(elms)
            }

            return elms;
        },
        /**
         * getInstanceConfig
         * @param containerId
         * @param pluginId
         * @param onComplete
         */
        getInstanceConfig: function (containerId, pluginId, onComplete) {

            var elm = self.RcmPluginModel.getElm(containerId, pluginId);

            var url = '/api/admin/instance-configs/'
                + self.RcmPluginModel.getName(elm)
                + '/'
                + self.RcmPluginModel.getId(elm);

            //Hide while loading
            elm.hide();

            jQuery.getJSON(
                url,
                function (result) {
                    elm.show();

                    onComplete(result.instanceConfig, result.defaultInstanceConfig);
                }
            );
        }
    }
};
