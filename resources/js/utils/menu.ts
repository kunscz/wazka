import type { Menu, NavItem, Permission } from '@/types';
import { route } from 'ziggy-js';
import { resolveIcon } from '@/utils/icons';

// Recursively filters the menu tree based on user permission names
export function filterMenusByPermission(menus: Menu[], allowed: string[]): Menu[] {
	return menus
		.filter(menu => {
			const perms = menu.permissions ?? [];
			return perms.every(p => allowed.includes(p.name))
		})
		.map(menu => ({
			...menu,
			children: filterMenusByPermission(menu.children, allowed),
		}));
}

export async function mapMenusToNavItems(menus: Menu[]): Promise<NavItem[]> {
	return await Promise.all(
		menus.map(async menu => ({
			title: menu.label,
			href: menu.route_name ? route(menu.route_name) : null,
			icon: await resolveIcon(menu.icon ?? 'Folder'),
			children: menu.children.length
				? await mapMenusToNavItems(menu.children)
				: [],
		}))
	);
}