/**
 * RcmAdminPlugin - AKA RcmPlugin - AKA pluginHandler
 *
 * @param {RcmAdminPage} page
 * @param id
 * @param {RcmAdminContainer} container
 * @param {RcmAdminService} rcmAdminService
 * @param {RcmAdminBlockEditorRegistry} rcmAdminBlockEditorRegistry
 * @constructor
 */
var RcmAdminPlugin = function (
    page,
    id,
    container,
    rcmAdminService,
    rcmAdminBlockEditorRegistry
) {
    var self = this;

    self.model = rcmAdminService.model.RcmPluginModel;
    self.viewModel = rcmAdminService.viewModel.RcmPluginViewModel;
    self.containerModel = rcmAdminService.model.RcmContainerModel;

    self.angularCompile = rcmAdminService.angularCompile;

    self.page = page;
    self.id = id;

    self.container = container;
    self.editMode = null;
    self.pluginObject = null;
    self.isInitted = false;

    self.instanceConfig = null;
    self.defaultInstanceConfig = null;

    /**
     * pluginMenu
     * @type {{'optionId': {'title': 'String', 'method': {function}}}}
     */
    self.pluginMenu = null;

    /**
     * getType
     * @returns string
     */
    self.getType = function () {

        if (self.getData().isSitewide) {
            return 'sitewide';
        }

        return self.container.getData().type;
    };

    self.getInstanceId = function () {
        return self.getId();
    };

    /**
     * getElm
     * @returns {elm}
     */
    self.getElm = function () {

        return self.model.getElm(self.container.id, self.id);
    };

    /**
     * getId
     * @returns {*}
     */
    self.getId = function () {

        return self.id;
    };

    /**
     * getName
     * @returns {*|string}
     */
    self.getName = function () {

        var pluginElm = self.getElm();

        return self.model.getName(pluginElm);
    };

    /**
     * getOrder
     * @returns {*}
     */
    self.getOrder = function () {

        var pluginElm = self.getElm();

        return self.model.getOrder(pluginElm);
    };

    /**
     * getPluginContainer
     * @returns {*}
     */
    self.getPluginContainer = function () {
        var pluginElm = self.getElm();

        return self.model.getPluginContainer(pluginElm);
    };

    /**
     * getEditor
     * @returns {*}
     */
    self.getEditor = function () {
        return self.model.getEditor(self.getName());
    };

    /**
     * getInstanceConfig
     * @param onComplete
     *
     * @return {Promise}
     */
    self.getInstanceConfig = function (onComplete) {
        if (self.instanceConfig && self.defaultInstanceConfig) {
            // This path needed for preview to work
            return new Promise(
                function (resolve, reject) {
                    resolve(self.instanceConfig, self.defaultInstanceConfig);

                    if (typeof onComplete === 'function') {
                        onComplete(self.instanceConfig, self.defaultInstanceConfig);
                    }
                }
            );
        }

        return new Promise(
            function (resolve, reject) {
                self.model.getInstanceConfig(
                    self.container.id,
                    self.id,
                    function (instanceConfig, defaultInstanceConfig) {
                        self.instanceConfig = instanceConfig;
                        self.defaultInstanceConfig = defaultInstanceConfig;

                        resolve(instanceConfig, defaultInstanceConfig);

                        if (typeof onComplete === 'function') {
                            onComplete(instanceConfig, defaultInstanceConfig);
                        }
                    }
                );
            }
        );
    };

    /**
     * getData
     * @returns {*}
     */
    self.getData = function () {

        var data = self.model.getData(self.container.id, self.id);

        data.rank = self.getOrder();

        data.containerType = self.container.getData().type;

        return data;
    };

    /**
     * getEditorData
     * @returns {{}}
     */
    self.getEditorData = function () {

        var editors = self.getEditorElms();

        var data = {};

        jQuery.each(
            editors,
            function (key, elm) {
                data[key] = jQuery(elm).html();
            }
        );

        return data;
    };

    /**
     * getSaveData
     *
     * @param onComplete
     * @return {Promise}
     */
    self.getSaveData = function (onComplete) {
        var pluginObject = self.getPluginObject();

        var saveData = {};

        if (pluginObject.getSaveData) {
            saveData = pluginObject.getSaveData();
        }

        if (saveData instanceof Promise) {
            return new Promise(
                function (resolve, reject) {
                    saveData.then(
                        function (saveData) {
                            var pluginData = self.buildSaveData(saveData);
                            resolve(pluginData);
                            // @BC
                            if (typeof onComplete === 'function') {
                                onComplete(self);
                            }
                        }
                    )
                }
            );
        }

        return new Promise(
            function (resolve, reject) {
                var pluginData = self.buildSaveData(saveData);
                resolve(pluginData);
                // @BC
                if (typeof onComplete === 'function') {
                    onComplete(self);
                }
            }
        );
    };

    /**
     * @param saveData
     */
    self.buildSaveData = function (saveData) {
        var pluginData = self.getData();

        pluginData.saveData = {};

        jQuery.extend(pluginData.saveData, saveData);

        var editorData = self.getEditorData();

        jQuery.extend(pluginData.saveData, editorData);

        return pluginData;
    };

    /**
     * getPluginObject
     * @returns RcmPluginEditJs
     */
    self.getPluginObject = function () {

        if (self.pluginObject) {
            return self.pluginObject;
        }

        self.pluginObject = rcmAdminBlockEditorRegistry.buildEditor(
            self.getEditor(),
            self
        );

        if (self.pluginObject) {
            return self.pluginObject;
        }

        /* NOOP pluginObject */
        self.pluginObject = rcmBlockEditorNoopFactory(self);

        return self.pluginObject;
    };

    /**
     * getEditorElms
     * @returns {*}
     */
    self.getEditorElms = function () {

        return self.model.getEditorElms(self.container.id, self.id);
    };

    /**
     * endLoading
     * @param amount 0 to 1
     */
    self.setLoading = function (amount) {

        rcmLoading.setLoading(
            'RcmPlugin.' + self.id,
            amount
        );
    };

    /**
     * isLoading
     */
    self.isLoading = function () {

        return rcmLoading.isLoading('RcmPlugin.' + self.id);
    };

    /**
     * Add a menu item with a method to be called on click
     * @param optionId
     * @param title
     * @param method
     * @returns {null|{}|*}
     */
    self.addPluginMenu = function (optionId, title, method) {

        if (self.pluginMenu === null) {
            self.pluginMenu = {};
        }

        if (self.pluginMenu[optionId]) {
            console.warn('Duplicate pluginMenu added: ' + optionId);
        }

        self.pluginMenu[optionId] = {
            title: title,
            method: method
        };

        return self.pluginMenu;
    };

    /**
     * @todo return {Promise}
     * prepareEditors
     * @param onComplete
     */
    self.prepareEditors = function (onComplete) {

        var editors = self.getEditorElms();

        jQuery.each(
            editors,
            function (index, value) {
                value.setAttribute('html-editor-plugin-id', self.id);
            }
        );

        self.page.events.trigger('prepareEditors', self);

        if (typeof onComplete === 'function') {
            onComplete(self);
        }
    };

    /**
     * canEdit
     * @returns boolean
     */
    self.canEdit = function () {

        var editing = self.page.editing;

        var type = self.getType();

        return (editing.indexOf(type) > -1);
    };

    /**
     * @todo return {Promise}
     * remove
     * @param onComplete
     */
    self.remove = function (onComplete) {
        self.viewModel.disableArrange(
            self.getElm(),
            function () {
                self.model.deleteElm(self.container.id, self.id);

                if (typeof onComplete === 'function') {
                    onComplete(self);
                }
            }
        );
    };

    /**
     * initEdit
     * @param onInitted function
     * @param refresh bool
     */
    self.initEdit = function (onInitted, refresh) {
        var elm = self.getElm();
        self.viewModel.enableEdit(
            elm,
            function (elm) {
                if (!self.isInitted || refresh) {
                    var pluginObject = self.getPluginObject();

                    if (pluginObject.initEdit) {
                        pluginObject.initEdit();
                    }

                    self.pluginReady();

                    self.isInitted = true;
                    if (typeof onInitted === 'function') {
                        onInitted(self);
                    }
                }
            }
        );
    };

    /**
     * cancelEdit
     * @param onCanceled
     */
    self.cancelEdit = function (onCanceled) {

        var elm = self.getElm();
        var type = self.getType();
        self.viewModel.disableEdit(
            elm,
            type,
            function (elm) {
                if (typeof onCanceled === 'function') {
                    onCanceled(self);
                }
            }
        );
    };

    /**
     * @todo return {Promise}
     * enableArrange
     * @param onComplete
     */
    self.enableArrange = function (onComplete) {

        var elm = self.getElm();
        elm.pluginMenu = self.pluginMenu;
        self.viewModel.enableArrange(
            elm,
            onComplete
        );
    };

    /**
     * @todo return {Promise}
     * disableArrange
     * @param onComplete
     */
    self.disableArrange = function (onComplete) {

        var elm = self.getElm();
        self.viewModel.disableArrange(
            elm,
            onComplete
        );
    };

    /**
     * @todo return {Promise}
     * updateView - ONLY use this if needed - may cause issues with ng-repeat and possibly other
     * @param elm
     * @param onComplete
     */
    self.updateView = function (elm, onComplete) {

        self.prepareEditors(
            function (plugin) {

                if (!elm) {
                    elm = plugin.getElm()
                }

                self.angularCompile(
                    elm,
                    function () {
                        self.page.events.trigger('updateView', plugin);
                    }
                );

                if (typeof onComplete === 'function') {
                    onComplete(plugin);
                }
            }
        );
    };

    /**
     * @todo return {Promise}
     * Asks the plugin edit controller for its instance config, then has
     * the server re-render the plugin. This is useful for previewing
     * changes to plugins without having to save the page.
     * @param onComplete
     */
    self.preview = function (onComplete) {
        var pluginElm = self.getElm();

        var name = self.model.getName(pluginElm);
        var pluginContainer = self.model.getPluginContainer(pluginElm);
        self.instanceConfig = self.getSaveData().saveData;
        // @todo This should be in a model
        $.post(
            '/rcm/core/rpc/render-plugin-instance-preview',
            {
                pluginType: name,
                instanceId: id,
                instanceConfig: self.instanceConfig
            },
            function (data) {
                pluginContainer.html(data.html);
                self.updateView(
                    pluginContainer, function () {
                        self.initEdit(onComplete, true);
                    }
                );
            }
        );
    };

    /**
     * @todo return {Promise}
     * pluginReady - trigger post plugin ready actions/ DOM parsing
     */
    self.pluginReady = function (onComplete) {
        self.prepareEditors(
            function (plugin) {

                self.page.events.trigger('pluginReady', plugin);

                if (typeof onComplete === 'function') {
                    onComplete(plugin);
                }
            }
        );
    };

    /**
     * onEditChange
     * @param page
     */
    self.onEditChange = function (page) {

        var editMode = self.canEdit(page.editing);

        if (self.editMode !== editMode) {

            self.editMode = editMode;

            if (self.editMode) {

                self.initEdit();

            } else {

                self.cancelEdit();
            }
        }
    };

    /**
     * onArrangeStateChange
     * @param state
     */
    self.onArrangeStateChange = function (state) {

        if (state) {
            self.enableArrange();
        } else {
            self.disableArrange();
        }
    };

    /**
     * @todo return {Promise}
     * onInitComplete
     */
    self.onInitComplete = function (onComplete) {

        // initial state
        self.onEditChange(self.page);

        self.onArrangeStateChange(self.page.arrangeMode);

        if (typeof onComplete === 'function') {
            onComplete(self);
        }
    };

    /**
     * @todo return {Promise}
     * init
     */
    self.init = function (onComplete) {

        self.page.events.on('editingStateChange', self.onEditChange);

        self.page.events.on('arrangeStateChange', self.onArrangeStateChange);

        self.prepareEditors(
            function (plugin) {
                self.onInitComplete(onComplete);
            }
        );
    };
};
