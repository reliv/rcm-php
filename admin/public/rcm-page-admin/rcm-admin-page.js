// @ts-check

/**
 * RcmAdminPage AKA RcmPage
 *
 * @param {unknown} _ Unused
 * @param {() => void} onInitted
 * @param {RcmAdminService} rcmAdminService
 * @constructor
 */
var RcmAdminPage = function (
    _,
    onInitted,
    rcmAdminService
) {

    var self = this;
    this.model = rcmAdminService.model.RcmPageModel;
    this.containerModel = rcmAdminService.model.RcmContainerModel;
    this.pluginModel = rcmAdminService.model.RcmPluginModel;

    this.saveUrl = rcmAdminService.config.saveUrl;

    this.events = rcmAdminService.rcmEventManager;
    this.editing = []; // page, layout, sitewide
    this.editMode = false;
    this.arrangeMode = false;

    /** @type {{[id: string]: RcmAdminContainer}} */
    this.containers = {};

    /** @type {{[name: string]: RcmAdminPluginData}} */
    this.plugins = {};

    this.loading = 0;

    /**
     * setLoading
     * @param {string} name
     * @param {number} amount
     */
    this.setLoading = function (name, amount) {
        rcmLoading.setLoading(
            name,
            amount
        );
    };

    /**
     * setEditingOn
     * @param type
     * @returns viod
     */
    this.setEditingOn = function (type) {

        if (self.editing.indexOf(type) < 0) {
            self.editing.push(type);
            self.onEditChange();
        }
    };

    /**
     * setEditingOff
     * @param type
     * @returns viod
     */
    this.setEditingOff = function (type) {

        if (self.editing.indexOf(type) > -1) {

            self.editing.splice(
                self.editing.indexOf(type),
                1
            );

            self.onEditChange();
        }
    };

    /**
     * onEditChange
     */
    this.onEditChange = function () {

        self.editMode = (self.editing.length > 0);

        self.events.trigger('editingStateChange', self);
    };

    /**
     * arrange
     * @param state
     */
    this.arrange = function (state) {

        if (typeof state === 'undefined') {
            // default is on
            state = true;
        }

        self.arrangeMode = (state === true);

        self.events.trigger('arrangeStateChange', self.arrangeMode);
    };

    /**
     * save
     */
    this.save = function () {

        self.registerObjects(
            function (page) {

                self.setLoading(
                    'RcmAdminPage',
                    0
                );
                var pagedata = self.getData();

                var data = pagedata.page;

                // loop containers and fire saves... aggregate data and sent to server
                data.plugins = {};

                var promiseArray = [];

                jQuery.each(
                    self.plugins,
                    function (key, plugin) {
                        promiseArray.push(
                            plugin.getSaveData().then(
                                function (pluginData) {
                                    data.plugins[key] = pluginData;
                                }
                            )
                        );
                    }
                );

                return Promise.all(
                    promiseArray
                ).then(
                    function () {
                        jQuery.ajax(
                            {
                                url: self.saveUrl + '/' + data.type + '/' + data.name + '/' + data.revision,
                                type: 'POST',
                                data: JSON.stringify(data),
                                contentType: 'application/json',
                                dataType: 'json',
                            }
                        ).done(
                            function (msg) {
                                self.setLoading(
                                    'RcmAdminPage',
                                    1
                                );
                                //self.events.trigger('alert', {type:'success',message: 'Page saved'});
                                if (msg.redirect) {
                                    window.location = msg.redirect;
                                } else {

                                    self.events.trigger(
                                        'alert', {
                                            type: 'warning',
                                            message: msg,
                                        }
                                    );
                                }

                            }
                        ).fail(
                            function (msg) {
                                self.setLoading(
                                    'RcmAdminPage',
                                    1
                                );
                                self.events.trigger(
                                    'alert', {
                                        type: 'warning',
                                        message: msg,
                                    }
                                );
                            }
                        );
                    }
                );
            }
        );
    };

    /**
     * cancel
     */
    this.cancel = function () {

        self.setLoading(
            'RcmAdminPage.cancel',
            0
        );

        self.events.trigger('cancel', {page: self});

        window.location = /** @type {any} */ (window.location.pathname);
    };

    /**
     * refresh
     */
    this.refresh = function (onComplete) {

        self.registerObjects(
            function (page) {
                self.events.trigger('refresh', {page: page});
                if (typeof onComplete === 'function') {
                    onComplete(self);
                }
            }
        );
    };

    this.getData = function () {
        return self.model.getData();
    };

    /**
     * getPlugin
     * @param {number} pluginId
     * @returns {RcmAdminPlugin?}
     */
    this.getPlugin = function (pluginId) {
        if (self.plugins[pluginId]) {
            return self.plugins[pluginId];
        }

        return null;
    };

    /**
     * addPlugin
     * @param {string} containerId
     * @param {number} pluginId
     */
    this.addPlugin = function (containerId, pluginId) {

        if (!self.plugins[pluginId]) {

            self.plugins[pluginId] = new RcmAdminPlugin(
                self,
                pluginId,
                self.containers[containerId],
                rcmAdminService,
                rcmAdminBlockEditorRegistry
            );

            self.plugins[pluginId].init();
        }

        self.plugins[pluginId].container = self.containers[containerId];

        self.events.trigger('addPlugin', pluginId);

        return self.plugins[pluginId];
    };

    /**
     * removePlugin
     * @param {number} pluginId
     */
    this.removePlugin = function (pluginId) {
        if (self.plugins[pluginId]) {
            self.plugins[pluginId].remove(
                function (plugin) {
                    delete (self.plugins[pluginId]);
                    self.events.trigger('removePlugin', pluginId);
                }
            );
        }
    };

    /**
     * @todo return {Promise}
     * registerObjects
     * - Update object list based on DOM state
     * - should be called after DOM update
     * @param {() => void} onComplete
     */
    this.registerObjects = function (onComplete) {

        var containerElms = self.containerModel.getElms();

        var containerElm = null;
        var containerId = null;

        var pluginsRemove = [];
        var pluginElms = [];
        var pluginElm = null;

        /** @type {number} */
        var pluginId = null;

        jQuery.each(
            containerElms,
            function (key, value) {

                containerElm = jQuery(value);
                containerId = self.containerModel.getId(containerElm);

                if (!self.containers[containerId]) {

                    self.containers[containerId] = new RcmAdminContainer(
                        self,
                        containerId,
                        self.containerModel
                    );
                }

                pluginElms = self.pluginModel.getElms(containerId);

                jQuery.each(
                    pluginElms,
                    function (pkey, pvalue) {

                        pluginElm = jQuery(pvalue);
                        pluginId = self.pluginModel.getId(pluginElm);

                        self.addPlugin(containerId, pluginId);

                        pluginsRemove.push(pluginId);
                    }
                );
            }
        );

        // remove if no longer in DOM
        jQuery.each(
            self.plugins,
            function (prkey, prvalue) {
                if (pluginsRemove.indexOf(prvalue.id) < 0) {
                    self.removePlugin(prvalue.id);
                }
            }
        );

        self.events.trigger('registerObjects', self.plugins);

        if (typeof onComplete === 'function') {
            onComplete(self);
        }
    };

    /**
     * init
     * @param onComplete
     */
    this.init = function (onComplete) {

        self.registerObjects(
            function (page) {

                if (typeof onComplete === 'function') {
                    onComplete(self);
                }
            }
        );
    };

    this.init(onInitted);
};
