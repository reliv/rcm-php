declare interface RcmLoading {
    setLoading(name: string, amount: number): void;
}

declare interface Window {
    rcmLoading: RcmLoading;
}

declare const rcmLoading: RcmLoading;
