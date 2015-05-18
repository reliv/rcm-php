/**
 * rcmAdminMenu
 *  @require:
 *  AngularJS
 *  rcm (rcm core)
 *  RcmDialog
 */
angular.module(
    'rcmAdminMenu',
    ['RcmDialog']
)
/**
 * rcmAdminMenu.RcmAdminMenu
 */
    .directive(
    'RcmAdminMenu',
    [
        '$log',
        function ($log) {

            var openDialog = function (scope, elm, attrs, linkElm) {

                // get strategyName
                var strategyName = null;

                var classAttr = elm.attr('class');

                if (classAttr) {
                    var classes = classAttr.split(" ");
                    if (classes[1]) {
                        strategyName = classes[1];
                    }
                }

                var rcmDialogActions = null;

                if (attrs.rcmDialogActions) {
                    try {
                        rcmDialogActions = scope.$eval(attrs.rcmDialogActions);
                    } catch (e) {

                    }
                }

                var dialog = RcmDialog.buildDialog(
                    linkElm.attr('href'), //id
                    linkElm.attr('title'),
                    linkElm.attr('href'),
                    strategyName,
                    rcmDialogActions,
                    scope
                );

                dialog.open();
            };

            var thisLink = function (scope, elm, attrs) {

                var linkElm = elm.find("a");

                linkElm.on(
                    'click', null, null, function (event) {

                        event.preventDefault();

                        RcmAdminService.canEdit(
                            function (canEdit) {
                                if (canEdit) {
                                    openDialog(scope, elm, attrs, linkElm);
                                }
                            }
                        );
                    }
                );
            };

            return {
                restrict: 'C',
                link: thisLink
            }
        }

    ]
);
rcm.addAngularModule('rcmAdminMenu');