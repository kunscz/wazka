import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';
export type { NavBase, NavGroup, NavItem, NavLink } from './navItem';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

// export interface NavItem {
//     title: string;
//     href: string | null;
//     icon?: LucideIcon;
//     isActive?: boolean;
//     children?: NavItem[];
// }

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    roles: Role[];
    permissions: Permission[];
}

export interface Permission {
    id: number
    name: string
    guard_name: string
    created_at?: string
    updated_at?: string
}

export interface Role {
    id: number
    name: string
    guard_name: string
    permissions: Permission[]
}

export interface RolePayload {
  name: string
}

export interface SyncPermissionsPayload {
  permissions: string[]
}

export interface Menu {
    id: number
    label: string
    icon?: string
    route_name?: string
    parent_id?: number | null
    sort_order: number
    is_active: boolean
    permissions: Permission[]
    children: Menu[]
}

export type BreadcrumbItemType = BreadcrumbItem;
