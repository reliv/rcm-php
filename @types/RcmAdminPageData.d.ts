declare interface RcmAdminPageData {
    plugins: {[name: string]: RcmAdminPluginData};
    type: string;
    name: string;
    revision: number;
}
