declare interface RcmPluginModel {
    getElms(containerId: string): unknown[];
    getId(pluginElm: unknown): number;
}
