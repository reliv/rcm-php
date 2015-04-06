/**
 * Get Module
 */
angular.module(
    'RcmDialog'
)

/**
 * @deprecated
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
);
