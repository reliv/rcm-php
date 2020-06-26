declare interface RcmAdminService {
    model: {
        RcmPageModel: RcmPageModel;
        RcmPluginModel: RcmPluginModel;
        RcmContainerModel: RcmContainerModel;
    };

    config: {
        saveUrl: string;
    }

    rcmEventManager: RcmEventManager;
}
