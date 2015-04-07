/**
 * <RcmDialog>
 *  requires:
 *   - rcmGuid
 *   - Bootstrap v3.3.2 (http://getbootstrap.com) bootstrap.js
 */
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
     * buildDialogElement - make sure there is an element for dialog
     */
    buildDialogElement: function () {

        // Check by attribute
        var dialogElm = jQuery('body').find('[data-rcm-dialog]');

        if (dialogElm.length) {
            return;
        }

        // Check by element tag
        dialogElm = jQuery('body').find('data-rcm-dialog');

        if (dialogElm.length) {
            return;
        }

        dialogElm = jQuery('<div data-rcm-dialog="true"></div>');
        jQuery('body').prepend(dialogElm);

        angular.element(document).injector().invoke(
            function ($compile) {
                var scope = angular.element(dialogElm).scope();
                $compile(dialogElm)(scope);
            }
        );
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
            angular.forEach(
                actions,
                function (value, key) {
                    dialog.setAction(key, value);
                }
            );
        }

        RcmDialog.addDialog(dialog);

        return dialog;
    },

    /**
     * action class
     */
    action: function () {

        var self = this;

        self.type = 'button'; // disabled, button, hide
        self.label = 'button';
        self.css = 'btn btn-default';
        self.method = function () {
        }
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
        self.preOpened = false;

        self.actions = {
            close: {
                type: 'button',
                label: 'Close',
                css: 'btn btn-default',
                method: function ()
                {
                    self.close();
                }
            }
        };

        /**
         * setElm
         * @param elm
         */
        self.setElm = function (elm) {

            self.elm = elm;
            self.syncEvents();

            // If open was called before the elm is set, then we should open now
            if (self.preOpened) {
                self.open();
            }
        };

        /**
         * getDirectiveName
         * @returns {string}
         */
        self.getDirectiveName = function () {

            return self.strategyName.replace(
                /([a-z])([A-Z])/g,
                '$1-$2'
            ).toLowerCase();
        };

        /**
         * setAction
         * @param actionName
         * @param action
         */
        self.setAction = function (actionName, action) {

            if(self.actions[actionName]){
                self.actions[actionName] = angular.extend(self.actions[actionName], action);
            } else {
                self.actions[actionName] = action;
            }

            RcmDialog.eventManager.trigger(
                'dialog.setAction',
                self
            );
        };

        /**
         * getAction
         * @param actionName
         * @returns {*}
         */
        self.getAction = function (actionName){
            if(self.actions[actionName]){
                return self.actions[actionName];
            }

            return null;
        };

        /**
         * open
         */
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

        /**
         * close
         */
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

        /**
         * syncEvents
         */
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
                        if (self.actions.close && self.actions.close.type == 'button') {
                            self.actions.close.method();
                        } else {
                            self.close()
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
     * @param dialog
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
    },

    /**
     * hasDialog
     * @param dialogId
     * @returns bool
     */
    hasDialog: function (dialogId) {

        return (RcmDialog.dialogs[dialogId])
    }
};

/**
 *
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

                return function (scope, elm, attrs, ctrl) {

                    rcmDialogElm = elm;
                };
            };

            return {
                restrict: 'A',
                compile: thisCompile
            }
        }
    ]
)
    .directive(
    'rcmDialogLink',
    [
        '$log',
        function ($log) {

            var thisLink = function (scope, elm, attrs, ctrl) {

                var rcmDialogId = null;

                if (attrs.rcmDialogId) {
                    rcmDialogId = attrs.rcmDialogId;
                } else {
                    rcmDialogId = rcmGuid.generate();
                }

                if (RcmDialog.hasDialog(rcmDialogId)) {
                    $log.warn('Duplicate dialog with id ' + rcmDialogId + ' has been created, some dialogs will not work correctly.');
                }

                var rcmDialogTitle = "Dialog";

                if (attrs.rcmDialogTitle) {
                    rcmDialogTitle = attrs.rcmDialogTitle;
                }

                // URL of content to load
                var rcmDialogLink = null;
                if (attrs.rcmDialogLink) {
                    rcmDialogLink = attrs.rcmDialogLink;
                }

                var rcmDialogStrategy = 'rcmStandardDialog';
                if (attrs.rcmDialogStrategy) {
                    rcmDialogStrategy = attrs.rcmDialogStrategy;
                }

                var rcmDialogActions = null;

                if (attrs.rcmDialogActions) {
                    try {
                        rcmDialogActions = scope.$eval(attrs.rcmDialogActions);

                    } catch (e) {
                        $log.warn('rcmDialogActions for dialog ' + rcmDialogId + ' format is invalid and was ignored.');
                    }
                }

                var dialog = RcmDialog.buildDialog(
                    rcmDialogId,
                    rcmDialogTitle,
                    rcmDialogLink,
                    rcmDialogStrategy,
                    rcmDialogActions
                );

                jQuery(elm).click(
                    function () {
                        dialog.open();
                    }
                )
            };

            return {
                restrict: 'A',
                link: thisLink
            }

        }
    ]
);

/**
 * Compile Elm if dynamically created
 */
angular.element(document).ready(
    function () {
        RcmDialog.buildDialogElement();
    }
);

rcm.addAngularModule('RcmDialog');
/** </RcmDialog> */