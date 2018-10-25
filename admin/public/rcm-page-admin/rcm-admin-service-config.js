var rcmAdminServiceConfig = {

    apiUrls: {
        canEdit: '/api/rpc/rcm-admin/can-edit',
        currentSite: '/api/admin/current-site'
    },

    saveUrl: '/rcm-admin/page/save-page',
    loadingMessages: {
        _default: {
            title: 'Loading',
            message: 'Please wait...'
        },
        save: {
            message: 'Saving page...'
        }
    },

    unlockMessages: {
        sitewide: {
            title: "Unlock Site-Wide Plugins?",
            message: "Please Note: Any changes you make to a Site-Wide plugin will be published and made live when you save your changes."
        },
        page: {
            title: "Unlock Page Plugins?",
            message: null
        },
        layout: {
            title: "Unlock Layout Plugins?",
            message: null
        }
    }
};
