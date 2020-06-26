declare interface RcmAdminPage {
    plugins: {[name: string]: RcmAdminPluginData};
    type: string;
    name: string;
    revision: number;
}
