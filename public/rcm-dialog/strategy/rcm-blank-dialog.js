/**
 * Get Module
 */
angular.module(
    'RcmDialog'
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
);