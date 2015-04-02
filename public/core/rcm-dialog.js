var RcmDialog = {

    service: null,

    defaultStrategy: 'rcmBlankDialog',

    /**
     * dialogs
     */
    dialogs: {},

    /**
     * eventManager
     */
    eventManager: {

        events: {},

        on: function (event, id, method) {

            if (!this.events[event]) {
                this.events[event] = {};
            }

            this.events[event][id] = method;
        },

        trigger: function (event, args) {

            if (this.events[event]) {
                jQuery.each(
                    this.events[event],
                    function (index, value) {
                        value(args);
                    }
                );
            }
        }
    },

    /**
     * buildDialog
     */
    buildDialog: function (id, title, url, strategyName, actions) {

        var dialog = new RcmDialog.dialog();

        if (strategyName) {
            dialog.strategyName = strategyName;
        } else {
            dialog.strategyName = new RcmDialog.defaultStrategy;
        }

        if (id) {
            dialog.id = id;
        } else {
            dialog.id = url;
        }

        dialog.loading = true;

        dialog.title = title;
        dialog.url = url;

        if (actions) {
            dialog.actions = actions;
        }

        RcmDialog.addDialog(dialog);

        return dialog;
    },

    /**
     * dialog
     */
    dialog: function () {

        var self = this;
        self.id = 0;
        self.loading = true;
        self.strategyName = null;
        self.title = '';
        self.url = '';
        self.elm = null;
        self.openState = 'init';
        // @todo make this a part of the actions
        self.params = {
            saveLabel: 'Save',
            closeLabel: 'Close'
        };

        self.preOpened = false;

        self.actions = {
            close: function () {
                self.close();
            }
        };

        self.setElm = function (elm) {

            self.elm = elm;
            self.syncEvents();

            // If open was called before the elm is set, then we should open now
            if (self.preOpened) {
                self.open();
            }
        };

        self.getDirectiveName = function () {

            return self.strategyName.replace(
                /([a-z])([A-Z])/g,
                '$1-$2'
            ).toLowerCase();
        };

        self.setAction = function (actionName, method) {

            self.actions[actionName] = method;

            RcmDialog.eventManager.trigger(
                'dialog.setAction',
                self
            );
        };

        self.open = function () {

            RcmDialog.eventManager.trigger(
                'dialog.open',
                self
            );

            // Set flag if elm is not ready
            if (!self.elm) {
                self.preOpened = true;
            }

            if (self.elm && self.openState !== 'open') {

                self.openState = 'open';
                self.loading = true;

                self.elm.modal('show');
            }
        };

        self.close = function () {

            RcmDialog.eventManager.trigger(
                'dialog.close',
                self
            );

            if (self.elm && self.openState !== 'closed') {

                self.openState = 'close';
                self.elm.modal('hide');
            }
        };

        self.syncEvents = function () {

            if (self.elm.modal) {

                self.elm.on(
                    'show.bs.modal',
                    function (event) {
                        self.openState = 'opening';
                    }
                );

                self.elm.on(
                    'shown.bs.modal',
                    function (event) {
                        self.openState = 'opened';
                    }
                );

                self.elm.on(
                    'hide.bs.modal',
                    function (event) {
                        self.openState = 'closing';
                    }
                );

                self.elm.on(
                    'hidden.bs.modal',
                    function (event) {
                        self.openState = 'closed';
                        if(self.actions.close){
                            self.actions.close();
                        }
                        self.elm.remove();
                        //scope.$destroy();
                        self.elm = null;
                    }
                );
            }
        }
    },

    /**
     * addDialog
     * @param addDialog
     */
    addDialog: function (dialog) {

        RcmDialog.dialogs[dialog.id] = dialog;
    },

    /**
     * getDialog
     * @param dialogId
     * @returns {*}
     */
    getDialog: function (dialogId) {

        return RcmDialog.dialogs[dialogId];
    }
};

/**
 * <RcmDialog>
 */
angular.module(
    'RcmDialog',
    []
)
    .factory(
    'rcmDialogService',
    [
        '$compile',
        function ($compile) {

            return RcmDialog;
        }
    ]
)
/**
 * RcmDialog.rcmDialog
 */
    .directive(
    'rcmDialog',
    [
        '$compile',
        function ($compile) {

            var rcmDialogElm = null;

            var modalTemplate = '<div class="modal fade"' +
                'id="TEMP"' +
                    //'tabindex="-1"' + // This causes issues
                'role="dialog"' +
                'aria-labelledby="myModalLabel"' +
                'aria-hidden="true"></div>';

            var updateElm = function (dialog) {

                var id = null;
                var newModal = null;
                var newDirectiveStrat = null;

                id = dialog.strategyName + ':' + dialog.id; //.replace(/(:|\.|\[|\])/g, "\\$1")

                if (!dialog.elm) {

                    newModal = jQuery(modalTemplate);
                    newModal.attr('id', id);
                    newDirectiveStrat = jQuery('<div ' + dialog.getDirectiveName() + '="' + dialog.id + '"></div>');
                    newModal.append(newDirectiveStrat);

                    newModal.modal(
                        {
                            show: false
                        }
                    );

                    dialog.setElm(newModal);

                    newModal.on(
                        'show.bs.modal',
                        function (event) {
                            dialog.openState = 'opening';
                            $compile(dialog.elm.contents())(dialog.elm.scope());
                        }
                    );

                    rcmDialogElm.append(newModal);
                }
            };

            RcmDialog.eventManager.on(
                'dialog.open',
                'rcmDialog',
                function (dialog) {
                    updateElm(dialog);
                }
            );

            var thisCompile = function (tElement, tAttrs) {

                var thisLink = function (scope, elm, attrs, ctrl) {

                    rcmDialogElm = elm;
                };

                return thisLink;
            };

            return {
                restrict: 'A',
                compile: thisCompile
            }
        }
    ]
)

/**
 * RcmDialog.rcmBlankDialog
 */
    .directive(
    'rcmBlankDialog',
    [
        '$compile',
        '$templateCache',
        function ($compile, $templateCache) {

            var thisCompile = function (tElement, tAttrs, transclude) {

                var thisLink = function (scope, elm, attrs, ctrl) {

                    var dialogId = attrs.rcmBlankDialog;

                    scope.dialog = RcmDialog.getDialog(dialogId);

                    $templateCache.remove(scope.dialog.url);

                    scope.loading = scope.dialog.loading = false;

                    scope.$apply();
                };

                return thisLink;
            };

            return {
                restrict: 'A',
                compile: thisCompile,
                scope: [],
                template: '<div ng-include="dialog.url">--{{dialog.url}}--</div>'
            }
        }
    ]
)

/**
 * RcmDialog.rcmBlankIframeDialog
 */
    .directive(
    'rcmBlankIframeDialog',
    [
        '$compile',
        '$parse',
        function ($compile, $parse) {

            var thisCompile = function (tElement, tAttrs, transclude) {

                var thisLink = function (scope, elm, attrs, ctrl) {

                    var dialogId = attrs.rcmBlankIframeDialog;

                    scope.dialog = RcmDialog.getDialog(dialogId);
                    scope.dialog.loading = false;
                    scope.$apply();
                };

                return thisLink;
            };

            return {
                restrict: 'A',
                compile: thisCompile,
                scope: [],
                template: '' +
                '<div id="RcmStandardDialogTemplateIn" style="display: block;" ng-hide="dialog.loading">' +
                '<div class="modal-dialog">' +
                '    <div class="modal-content">' +
                '        <div class="modal-header">' +
                '            <button type="button" class="close" XXXdata-dismiss="modal" aria-hidden="true" data-ng-click="dialog.actions.close()">&times;</button>' +
                '            <h1 class="modal-title" id="myModalLabel">{{dialog.title}}</h1>' +
                '        </div>' +
                '        <div class="modal-body" style="height: 400px">' +
                '            <iframe src="{{dialog.url}}" style="width: 100%; height: 400px"></iframe>' +
                '        </div>' +
                '        <div class="modal-footer">' +
                '            <button type="button" class="btn btn-default" XXXdata-dismiss="modal" ng-click="dialog.actions.close()">{{dialog.params.closeLabel}}' +
                '            </button>' +
                '            <button ng-show="dialog.actions.save" type="button" class="btn btn-primary saveBtn" ng-click="dialog.actions.save()">{{dialog.params.saveLabel}}' +
                '            </button>' +
                '        </div>' +
                '    </div>' +
                '</div>' +
                '</div>'

            }
        }
    ]
)
/**
 * RcmDialog.rcmBlankSyncDialog.failed
 *  Use this for loading modules with dependencies
 *  - Use script tags in html, not the oc-lazy-loader files array in the oc-lazy-loader directive
 *  - oc-lazy-loader takes time to process dependencies
 */
    .directive(
    'rcmBlankSyncDialog',
    [
        '$log',
        '$compile',
        '$http',
        function ($log, $compile, $http) {

            var startTime = new Date().getTime();

            var thisCompile = function (elm, attrs) {

                var dialogId = attrs.rcmBlankSyncDialog;

                var dialog = RcmDialog.getDialog(dialogId);

                startTime = new Date().getTime();

                var content = jQuery.ajax(
                    {
                        async: false,
                        //cache: false,
                        url: dialog.url,
                        dataType: 'html',
                        success: function () {

                        }
                        //data : { r: Math.random() } // prevent caching
                    }
                ).responseText;

                elm.html(content);

                // hide for late compile
                var orgStyle = elm.attr('style');
                if (!orgStyle) {
                    orgStyle = '';
                }
                elm.attr('style', 'visibility: hidden');

                return {

                    pre: function (scope, elm, attrs, controller, transcludeFn) {

                        scope.dialog = dialog;

                        // @todo this is a hack to wait for any dependencies to load and then re-compile
                        var totalTime = (new Date().getTime() - startTime) * 2;

                        setTimeout(
                            function () {
                                elm.attr('style', orgStyle);
                                $compile(elm.contents())(scope);
                                scope.$apply();
                            },
                            totalTime
                        );
                    },
                    post: function (scope, elm, attrs, controller, transcludeFn) {

                        scope.dialog.loading = false;
                        // @todo may need scope.$apply()
                    }
                }
            };

            return {
                restrict: 'A',
                compile: thisCompile,
                template: '<div></div>'
            }
        }
    ]
)
/**
 * RcmDialog.rcmStandardDialog
 */
    .directive(
    'rcmStandardDialog',
    [
        '$compile',
        '$timeout',
        '$http',
        function ($compile, $timeout, $http) {

            var thisCompile = function (tElement, tAttrs, transclude) {

                var thisLink = function (scope, elm, attrs, ctrl) {

                    var dialogId = attrs.rcmStandardDialog;

                    scope.dialog = RcmDialog.getDialog(dialogId);

                    $http({method: 'GET', url: scope.dialog.url}).
                        success(
                        function (data, status, headers, config) {
                            var contentBody = elm.find(".modal-body");
                            contentBody.html(data);
                            $compile(contentBody)(scope);
                        }
                    ).
                        error(
                        function (data, status, headers, config) {
                            var msg = "Sorry but there was an error: ";
                            scope.error(msg + status);
                        }
                    );

                    scope.dialog.loading = false;

                    scope.$apply();
                };

                return thisLink;
            };

            return {
                restrict: 'A',
                compile: thisCompile,
                scope: [],
                template: '' +
                '<div id="RcmStandardDialogTemplateIn" style="display: block;" ng-hide="dialog.loading">' +
                ' <div class="modal-dialog">' +
                '  <div class="modal-content">' +
                '   <div class="modal-header">' +
                '    <button type="button" class="close" XXXdata-dismiss="modal" aria-hidden="true" data-ng-click="dialog.actions.close()">&times;</button>' +
                '    <h1 class="modal-title" id="myModalLabel">{{dialog.title}}</h1>' +
                '   </div>' +
                '   <div class="alert alert-warning" role="alert" ng-show="error">{{error}}</div>' +
                '   <div class="modal-body"><!-- CONTENT LOADED HERE --></div>' +
                '   <div class="modal-footer">' +
                '    <button type="button" class="btn btn-default" XXXdata-dismiss="modal" data-ng-click="dialog.actions.close()" >' +
                '     {{dialog.params.closeLabel}}' +
                '    </button>' +
                '    <button ng-show="dialog.actions.save" type="button" class="btn btn-primary saveBtn" data-ng-click="dialog.actions.save()" >' +
                '     {{dialog.params.saveLabel}}' +
                '    </button>' +
                '   </div>' +
                '  </div>' +
                ' </div>' +
                '</div>'
            }
        }
    ]
)
/**
 * RcmDialog.rcmFormDialog
 */
    .directive(
    'rcmFormDialog',
    [
        '$compile',
        '$http',
        function ($compile, $http) {

            var thisCompile = function (tElement, tAttrs, transclude) {

                var thisLink = function (scope, elm, attrs, ctrl) {

                    var dialogId = attrs.rcmFormDialog;

                    scope.dialog = RcmDialog.getDialog(dialogId);

                    scope.dialog.setAction(
                        'save',
                        function () {
                            scope.dialog.loading = true;
                            // @todo may need scope.$apply()
                            var content = elm.find(".modal-body");
                            var form = elm.find('form');
                            var actionUrl = form.attr('action');

                            jQuery.post(actionUrl, form.serialize())
                                .fail(
                                function () {
                                    scope.dialog.loading = false;
                                    scope.$apply();
                                }
                            )
                                .always(
                                function (data) {

                                    content.html(data);
                                    scope.dialog.loading = false;
                                    $compile(content)(scope);
                                    scope.$apply();
                                }
                            );
                        }
                    );

                    $http({method: 'GET', url: scope.dialog.url}).
                        success(
                        function (data, status, headers, config) {

                            var contentBody = elm.find(".modal-body");
                            contentBody.html(data);
                            scope.dialog.loading = false;
                            $compile(contentBody)(scope);
                            // @todo may need scope.$apply()
                        }
                    ).
                        error(
                        function (data, status, headers, config) {

                            scope.dialog.loading = false;
                            // @todo may need scope.$apply()
                        }
                    );

                    scope.$apply();
                };

                return thisLink;
            };

            return {
                restrict: 'A',
                compile: thisCompile,
                scope: [],
                template: '' +
                '<div id="RcmFormDialogIn" style="display: block;" ng-hide="dialog.loading">' +
                ' <div class="modal-dialog">' +
                '  <div class="modal-content">' +
                '   <div class="modal-header">' +
                '    <button type="button" class="close" aria-hidden="true" data-ng-click="dialog.actions.close()">&times;</button>' +
                '    <h1 class="modal-title" id="myModalLabel">{{dialog.title}}</h1>' +
                '   </div>' +
                '   <div class="modal-body"><!-- CONTENT LOADED HERE --></div>' +
                '   <div class="modal-footer">' +
                '    <button type="button" class="btn btn-default" data-ng-click="dialog.actions.close()">' +
                '     {{dialog.params.closeLabel}}' +
                '    </button>' +
                '    <button type="button" class="btn btn-primary saveBtn" data-ng-click="dialog.actions.save()" >' +
                '     {{dialog.params.saveLabel}}' +
                '    </button>' +
                '   </div>' +
                '  </div>' +
                ' </div>' +
                '</div>'
            }
        }
    ]
);
/** </RcmDialog> */
rcm.addAngularModule('RcmDialog');