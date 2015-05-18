/**
 * **************************************************************
 * Angular JS module used to show HTML editor and toolbar on a page
 * @require:
 *  AngularJS
 *  TinyMce
 *  RcmHtmlEditor
 */
angular.module(
    'rcmAdmin',
    ['rcmApi', 'rcmAdminApi', 'RcmHtmlEditor']
)
/**
 * rcmAdminService
 */
    .factory(
    'rcmAdminService',
    [
        function () {
            return RcmAdminService;
        }
    ]
)
/**
 * rcmAdmin.rcmAdminMenuActions
 */
    .directive(
    'rcmAdminMenuActions',
    [
        '$compile',
        'rcmAdminService',
        function ($compile, rcmAdminService) {

            var thisLink = function (scope, elm, attrs) {
                scope.rcmAdminPage = rcmAdminService.getPage(
                    $compile(elm.contents())(scope)
                );
            };

            return {
                restrict: 'A',
                link: thisLink
            }
        }
    ]
)

/**
 * rcmAdmin.post
 */
    .directive(
    'rcmMenuPost',
    [
        'rcmAdminService',
        function (rcmAdminService) {

            var thisLink = function (scope, elm, attrs) {

                elm.unbind();
                elm.bind(
                    'click', null, function (e) {
                        e.preventDefault();

                        var linkHref = '';

                        if (attrs.publishUrl === undefined) {
                            linkHref = elm.find('a').attr('href');
                        } else {
                            linkHref = attrs.publishUrl;
                        }

                        jQuery('body').append('<form id="stupidPostSubmit" method="post" action="' + linkHref + '">');
                        jQuery('#stupidPostSubmit').submit();

                        /* Ajax request.  Makes publish take Twice as long, and
                         * fails silently when problems arise.  Recommended not
                         * to use, but kept here to settle any disputes.
                         */
//                        jQuery.post(elm.find('a').attr('href'), function(data) {
//                            if (data.redirect != undefined) {
//                                window.location = data.redirect;
//                            }
//                        });
                    }
                );
            };

            return {
                restrict: 'C',
                link: thisLink
            }
        }
    ]
)
/**
 * rcmAdmin.rcmAdminEditButton rcm-admin-edit-button
 */
    .directive(
    'rcmAdminEditButton',
    [
        'rcmAdminService',
        'rcmHtmlEditorService',
        function (rcmAdminService, rcmHtmlEditorService) {

            var eventsRegistered = false;

            var safeApply = function (scope, fn) {
                var phase = scope.$root.$$phase;
                if (phase == '$apply' || phase == '$digest') {
                    if (fn && (typeof(fn) === 'function')) {
                        fn();
                    }
                } else {
                    scope.$apply(fn);
                }
            };

            var thisLink = function (scope, elm, attrs) {

                scope.loading = false;

                if (!eventsRegistered) {

                    // @todo - Sync this in the service, not here
                    rcmHtmlEditorService.eventManager.on(
                        'rcmHtmlEditorService.loading.start',
                        function (obj) {
                            rcmLoading.setLoading(
                                'rcmHtmlEditorService.loading',
                                0,
                                {statusMessage: 'Editors Loading'}
                            );
                        }
                    );

                    rcmHtmlEditorService.eventManager.on(
                        'rcmHtmlEditorService.loading.change',
                        function (obj) {
                            rcmLoading.setLoading(
                                'rcmHtmlEditorService.loading',
                                obj.amount
                            );
                        }
                    );

                    rcmHtmlEditorService.eventManager.on(
                        'rcmHtmlEditorService.loading.end',
                        function (obj) {
                            rcmLoading.setLoading(
                                'rcmHtmlEditorService.loading',
                                1
                            );
                        }
                    );

                    rcmLoading.onLoadingStart(
                        function (loadingParams) {
                            scope.loading = true;
                            safeApply(scope);
                        },
                        'rcmAdminEditButton.onLoadingStart'
                    );

                    rcmLoading.onLoadingComplete(
                        function (loadingParams) {
                            scope.loading = false;
                            safeApply(scope);
                        },
                        'rcmAdminEditButton.onLoadingComplete'
                    );

                    eventsRegistered = true;
                }

                scope.rcmAdminPage = rcmAdminService.getPage();

                var editingState = attrs.rcmAdminEditButton;

                elm.unbind();
                elm.bind(
                    'click', null, function () {

                        rcmAdminService.rcmAdminEditButtonAction(
                            editingState,
                            function () {
                                scope.$apply();
                            }
                        );
                    }
                );
            };

            return {
                restrict: 'A',
                link: thisLink
            }
        }
    ]
)
/**
 * rcmAdmin.richedit
 */
    .directive(
    'richedit',
    [
        'rcmAdminService',
        'rcmHtmlEditorInit',
        'rcmHtmlEditorDestroy',
        function (rcmAdminService, rcmHtmlEditorInit, rcmHtmlEditorDestroy) {

            return {
                compile: rcmAdminService.getHtmlEditorLink(
                    rcmHtmlEditorInit,
                    rcmHtmlEditorDestroy,
                    'richedit'
                ),
                scope: {},
                restrict: 'A',
                require: '?ngModel'
            }
        }
    ]
)
/**
 * rcmAdmin.textedit
 */
    .directive(
    'textedit',
    [
        'rcmAdminService',
        'rcmHtmlEditorInit',
        'rcmHtmlEditorDestroy',
        function (rcmAdminService, rcmHtmlEditorInit, rcmHtmlEditorDestroy) {

            return {
                compile: rcmAdminService.getHtmlEditorLink(
                    rcmHtmlEditorInit,
                    rcmHtmlEditorDestroy,
                    'textedit'
                ),
                scope: {},
                restrict: 'A',
                require: '?ngModel'
            }
        }
    ]
);

rcm.addAngularModule('rcmAdmin');