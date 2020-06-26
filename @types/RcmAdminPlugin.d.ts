declare interface RcmAdminPlugin {
    getSaveData(): Promise<RcmAdminPluginData>;
    container: RcmAdminContainer;
    init(): void;
    remove(
        fn: (plugin: RcmAdminPlugin) => void
    ): void;
    id: number;
}
