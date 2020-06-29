declare interface RcmEventManager {
    trigger(name: string, ...args: any[]): void;
}
