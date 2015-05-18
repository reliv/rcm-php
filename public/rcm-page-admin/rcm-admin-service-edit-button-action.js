/**
 * rcmAdminEditButtonAction - Actions for links and AngularJS directives
 * @todo might require $apply
 * @param editingState
 * @param onComplete
 */
RcmAdminService.rcmAdminEditButtonAction = function (editingState, onComplete) {

    var page = RcmAdminService.getPage();
    page.refresh(
        function (page) {

            if (!editingState) {
                editingState = 'page';
            }

            //Needed to show plugin borders when hovering in edit mode
            if (editingState == 'page' || editingState == 'arrange') {
                $('html').addClass('rcmEditingPlugins');
            }else{
                $('html').removeClass('rcmEditingPlugins');
            }

            if (editingState == 'arrange') {

                page.setEditingOn('page');
                page.setEditingOn('layout');
                page.setEditingOn('sitewide');
                RcmAvailablePluginsMenu.build();

                page.arrange(true);

                RcmPluginDrag.refresh();

                return;
            }

            if (editingState == 'cancel') {
                page.cancel();
                return;
            }

            if (editingState == 'save') {
                page.save();
                return;
            }

            page.setEditingOn(editingState);

            if (typeof onComplete === 'function') {

                onComplete();
            }
        }
    );
};
