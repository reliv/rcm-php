declare interface RcmAdminPageData {
    containers: {
        [containerName: string]: {
            [pluginId: number]: RcmAdminPluginData;
        };
    };
    type: string;
    name: string;
    revision: number;
}
