/**
 * Get Module
 */
angular.module(
    'RcmDialog'
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
                '    <button ng-show="dialog.actions.close.type == \'button\'" type="button" class="close" aria-hidden="true" data-ng-click="dialog.actions.close.method()">&times;</button>' +
                '    <h1 class="modal-title" id="myModalLabel">{{dialog.title}}</h1>' +
                '   </div>' +
                '   <div class="alert alert-warning" role="alert" ng-show="error">{{error}}</div>' +
                '   <div class="modal-body"><!-- CONTENT LOADED HERE --></div>' +
                '   <div class="modal-footer">' +
                '    <button ng-repeat="(key, action) in dialog.actions" ng-show="action.type == \'button\'" type="button" class="{{action.css}}" data-ng-click="action.method()" >' +
                '        {{action.label}}' +
                '    </button>' +
                '   </div>' +
                '  </div>' +
                ' </div>' +
                '</div>'
            }
        }
    ]
)