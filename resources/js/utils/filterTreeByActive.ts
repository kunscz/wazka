import type { MenuNode } from '@/types/MenuNode'

export function filterTreeByActive(menus: MenuNode[], showInactive: boolean): MenuNode[] {
	return menus
		.filter(menu => showInactive || menu.is_active)
		.map(menu => ({
			...menu,
			children: menu.children
				? filterTreeByActive(menu.children, showInactive)
				: [],
		}))
}