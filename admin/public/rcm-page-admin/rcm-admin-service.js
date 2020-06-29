/**
 * RcmAdminService
 * @param {jQuery} jQuery
 * @param {angular} angular
 * @param {rcmAdminServiceConfig} rcmAdminServiceConfig
 * @param {RcmEventManager} rcmEventManager
 * @param {rcmBlockConfigs} rcmBlockConfigs
 * @param {RcmAdminModel} RcmAdminModel
 * @param {RcmAdminViewModel} RcmAdminViewModel
 * @param {RcmAdminPageData} RcmAdminPage
 * @constructor
 */
var RcmAdminService = function (
    jQuery,
    angular,
    rcmAdminServiceConfig,
    rcmEventManager,
    rcmBlockConfigs,
    RcmAdminModel,
    RcmAdminViewModel,
    RcmAdminPage
) {

    var self = this;

    /**
     * config
     */
    self.config = rcmAdminServiceConfig;

    /**
     * page
     */
    self.page = null;

    /**
     * RcmEventManager
     * @constructor
     */
    self.rcmEventManager = rcmEventManager;

    /**
     * canEdit - server check if use can edit
     * @param callback
     */
    self.canEdit = function (callback) {
        //ajax call to canEdit service
        jQuery.ajax(
            {
                url: self.config.apiUrls.canEdit,
                type: 'post',
                dataType: 'json',
            }
        )
            .done(
                function (data) {
                    /** {bool} */
                    var canEdit = data.data.canEdit;
                    self.rcmEventManager.trigger('rcmAdminService.editCheck', canEdit);
                    if (typeof callback === 'function') {
                        callback(canEdit);
                    }
                }
            )
            .fail(
                function () {
                    self.rcmEventManager.trigger('rcmAdminService.editCheck', false);
                    if (typeof callback === 'function') {
                        callback(false);
                    }
                }
            );
    };

    /**
     * model
     */
    self.model = new RcmAdminModel(
        rcmBlockConfigs
    );

    /**
     * viewModel
     */
    self.viewModel = null;

    /**
     * buildViewModel
     */
    self.buildViewModel = function () {

        self.viewModel = new RcmAdminViewModel(self.config, self.model, self.page);

        self.page.events.on(
            'alert',
            self.viewModel.alertDisplay
        );
    };

    /**
     * getPage
     * @param onBuilt
     * @returns {RcmAdminPage}|null
     */
    self.getPage = function (onBuilt) {

        if (!self.page) {

            self.page = new RcmAdminPage(
                self.model.RcmPageModel.getElm(),
                function (page) {

                    self.page = page;

                    self.buildViewModel();

                    if (typeof onBuilt === 'function') {
                        onBuilt(page);
                    }
                },
                self
            );
        } else {
            if (typeof onBuilt === 'function') {
                onBuilt(self.page);
            }
        }

        return self.page;
    };

    /**
     * getPlugin
     * @param id
     * @param onComplete
     * @returns {RcmAdminPlugin}
     */
    self.getPlugin = function (id, onComplete) {

        var page = self.getPage(
            function (page) {
                if (typeof onComplete === 'function') {
                    onComplete(page.getPlugin(id));
                }
            }
        );

        return page.getPlugin(id);
    };

    /**
     * angularCompile - only use this if you need to compile after dom change!!!!!
     * @param elm
     * @param fn
     */
    self.angularCompile = function (elm, fn) {

        var compile = angular.element(elm).injector().get('$compile');

        var scope = angular.element(elm).scope();

        compile(elm.contents())(scope);

        if (scope.$$phase || scope.$root.$$phase) {

            scope.$apply(fn);
        } else {

            if (typeof fn === 'function') {
                fn();
            }
        }
    };
};

var rcmAdminService = new RcmAdminService(
    window.jQuery,
    window.angular,
    window.rcmAdminServiceConfig,
    new RcmEventManager(),
    window.rcmBlockConfigs,
    window.RcmAdminModel,
    window.RcmAdminViewModel,
    window.RcmAdminPage
);
