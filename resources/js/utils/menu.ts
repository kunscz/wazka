import type { Menu, NavItem, NavGroup, NavLink, Permission } from '@/types';
import { route } from 'ziggy-js';
import { resolveIcon } from '@/utils/icons';

// Recursively filters the menu tree based on user permission names
export function filterMenusByPermission(menus: Menu[], allowed: string[] = [], showInactive = false): Menu[] {
	// console.log('menus', menus)

	// if (!Array.isArray(menus)) {
	// 	console.warn('Expected array, got:', menus)
	// 	return []
	// }

	return menus
		.filter(menu => {
			const perms = menu.permissions ?? [];
			const isAllowed = perms.length === 0 || perms.every(p => allowed.includes(p.name));
			const isActive = showInactive || menu.is_active;
			return isAllowed && isActive;
			// return perms.every(p => allowed.includes(p.name))
			// console.log('pperm/', menu.permissions)
			// return !perms || allowed.includes(menu.permissions);
		})
		.map(menu => ({
			...menu,
			children: filterMenusByPermission(menu.children ?? [], allowed, showInactive),
		}));
}

// export async function mapMenusToNavItems(menus: Menu[]): Promise<NavItem[]> {
//   return await Promise.all(
//     menus.map(async menu => {
// 		// const mapped = await mapMenusToNavItems(menu.children ?? [])
// 		// const children = mapped.filter((item): item is NavLink => !('children' in item))
//       const children = await mapMenusToNavItems(menu.children ?? [])
//       const base = {
//         title: menu.label,
//         href: menu.route_name ?? null,
//         icon: await resolveIcon(menu.icon ?? 'Folder'),
//       }

//       if (children.length) {
//         return {
//           ...base,
//           children, // ✅ this makes it a NavGroup
//         } satisfies NavGroup
//       } else {
//         return {
//           ...base,
//           children: undefined, // ✅ this enforces NavLink shape
//         } satisfies NavLink
//       }
//     })
//   )
// }

export function mapMenusToNavItems(menus: Menu[]): NavItem[] {
  return menus.map(menu => {
    const base = {
      title: menu.label,
      href: menu.route_name ?? null,
      iconName: menu.icon ?? 'Folder',
    }

    const children = mapMenusToNavItems(menu.children ?? [])

    return children.length
      ? { ...base, children } satisfies NavGroup
      : { ...base, children: undefined } satisfies NavLink
  })
}