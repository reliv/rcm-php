/**
 * Get Module
 */
angular.module(
    'RcmDialog'
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
                '            <button ng-show="dialog.actions.close.type == \'button\'" type="button" class="close" aria-hidden="true" data-ng-click="dialog.actions.close.method()">&times;</button>' +
                '            <h1 class="modal-title" id="myModalLabel">{{dialog.title}}</h1>' +
                '        </div>' +
                '        <div class="modal-body" style="height: 400px">' +
                '            <iframe src="{{dialog.url}}" style="width: 100%; height: 380px" frameborder="0"></iframe>' +
                '        </div>' +
                '        <div class="modal-footer">' +
                '            <button ng-repeat="(key, action) in dialog.actions" ng-show="action.type == \'button\'" type="button" class="{{action.css}}" data-ng-click="action.method()" >' +
                '                {{action.label}}' +
                '            </button>' +
                '        </div>' +
                '    </div>' +
                '</div>' +
                '</div>'

            }
        }
    ]
);