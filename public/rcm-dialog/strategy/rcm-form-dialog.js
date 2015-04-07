/**
 * Get Module
 */
angular.module(
    'RcmDialog'
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

                    var existingAction = scope.dialog.getAction('save');

                    var formAction = new RcmDialog.action();

                    formAction.type = 'button';
                    formAction.label = 'Save';
                    formAction.css = 'btn btn-primary';
                    formAction.method = function () {

                        scope.dialog.loading = true;
                        // @todo may need scope.$apply()
                        var content = elm.find(".modal-body");
                        var form = elm.find('form');
                        var actionUrl = form.attr('action');

                        jQuery.post(actionUrl, form.serialize())
                            .done(
                            function (data) {
                                formAction.type = 'hide';
                                scope.dialog.loading = false;
                                scope.$apply();
                            }
                        )
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
                    };

                    if(existingAction) {
                        formAction = angular.extend(formAction, existingAction);
                    }

                    scope.dialog.setAction(
                        'save',
                        formAction
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
                '    <button ng-show="dialog.actions.close.type == \'button\'" type="button" class="close" aria-hidden="true" data-ng-click="dialog.actions.close.method()">&times;</button>' +
                '    <h1 class="modal-title" id="myModalLabel">{{dialog.title}}</h1>' +
                '   </div>' +
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
);