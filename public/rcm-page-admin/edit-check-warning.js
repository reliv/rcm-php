RcmAdminService.editCheckWarning = function () {

    var showWarning = function () {
        bootbox.dialog(
            {
                size: 'small',
                title: '<h1>Session Timed Out</h1>',
                closeButton: false,
                message: 'Your session has timed out. Please log in again before editing.',
                buttons: {
                    success: {
                        label: 'Ok',
                        callback: function (result) {
                            $.ajax(
                                {
                                    url: '/api/admin/current-site',
                                    type: 'get',
                                    dataType: 'json',
                                    success: function (data) {
                                        var login = data.data;
                                        if (result) {
                                            window.location = login.loginPage + '?redirect=' + window.location.pathname;
                                        }
                                    }
                                }
                            );
                        }
                    }
                }
            }
        )
    };

    var checkCanEdit = function(canEdit) {

        if(!canEdit) {
            showWarning();
        }
    };

    var checkPageEditMode = function (page) {

        if (page.editMode) {
            RcmAdminService.canEdit(); // will trigger rcmAdminService.editCheck event
        }
    };

    RcmAdminService.rcmEventManager.on('editingStateChange', checkPageEditMode);
    RcmAdminService.rcmEventManager.on('rcmAdminService.editCheck', checkCanEdit);
};

RcmAdminService.editCheckWarning();
