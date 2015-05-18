/**
 * Created by bjanish on 3/6/15.
 */

RcmAdminService.rcmAdminPageNotFound = {

    onEditChange: function(page){

        var pageData = page.model.getData();

        if(page.editMode) {
            if (pageData.page.name != pageData.requestedPage.name) {
                var actions = {
                    close: {
                        type: 'button',
                        label: 'Cancel',
                        css: 'btn btn-default',
                        method: function () {
                            window.location = "/";
                        }
                    },
                    save: {
                        label: 'Create new page',
                        css: 'btn btn-primary'
                    }
                };
                var dialog = RcmDialog.buildDialog(
                    'rcm-page-not-found-123',
                    "Page does not exist. Create a new one?", '/rcm-admin/page/new?url=' + pageData.requestedPage.name + '',
                    'RcmFormDialog',
                    actions
                );
                setTimeout(
                    function () {
                        dialog.open();
                    },
                    500
                );
            }
        }
    },

    init: function(){
        var page = RcmAdminService.getPage(
            function (page) {
                page.events.on(
                    'editingStateChange', RcmAdminService.rcmAdminPageNotFound.onEditChange
                );
            }
        );
    }
};

RcmAdminService.rcmAdminPageNotFound.init();