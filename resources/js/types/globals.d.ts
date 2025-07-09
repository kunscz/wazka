import type Pusher from "pusher-js";
import { AppPageProps } from '@/types/index';
import type { route as routeFn } from 'ziggy-js';
import { AxiosInstance, AxiosResponse } from 'axios';

declare global {
    const route: typeof routeFn;

    interface Window {
        axios: AxiosInstance;
        Echo: Echo<Pusher>;
        Pusher: Pusher;
        authUserId: number;
    }
}

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}

declare module 'vue' {
    interface ComponentCustomProperties {
        $inertia: typeof Router;
        $page: Page;
        $headManager: ReturnType<typeof createHeadManager>;
    }
}
