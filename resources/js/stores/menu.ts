// stores/menu.ts
import { defineStore } from 'pinia';
import type { Menu } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { filterMenusByPermission } from '@/utils/menu';
import { buildTree } from '@/utils/buildMenuTree';

export const useMenuStore = defineStore('menu', {
	state: () => ({
		rawMenus: [] as Menu[],
		// filteredMenus: [] as Menu[],
		menuMap: {} as Record<number, Menu>,
		showInactive: false,
	}),
	actions: {
		async fetchMenus() {
			const { data } = await window.axios.get(route('core.menus.tree'));

			this.rawMenus = data;
			this.menuMap = Object.fromEntries(this.rawMenus.map(menu => [menu.id, menu]));
		},
		toggleShowInactive() {
			this.showInactive = !this.showInactive;
		},
	},
	getters: {
		activeMenus: (state) => {
			const page = usePage();
			const userPermission = page.props.auth.user.permissions.map(p => p.name);

			const activeOnly = state.rawMenus.filter(menu => menu.is_active)
			return filterMenusByPermission(activeOnly, userPermission)
		},
		filteredMenus: (state) => {
			const page = usePage();
			const userPermission = page.props.auth.user.permissions.map(p => p.name);

			const visibleMenus = state.showInactive
				? state.rawMenus
				: state.rawMenus.filter(menu => menu.is_active);

			return filterMenusByPermission(visibleMenus, userPermission);
		},
		menuTree: (state) => {
			const page = usePage();
			const userPermission = page.props.auth.user.permissions.map(p => p.name);

			const visibleMenus = state.showInactive
				? state.rawMenus
				: state.rawMenus.filter(menu => menu.is_active);

			const filtered = filterMenusByPermission(visibleMenus, userPermission);
			return buildTree(filtered);
		},
		getBreadcrumb: (state) => (id: number): Menu[] => {
			const breadcrumb: Menu[] = [];
			let currentMenu: Menu | undefined = state.menuMap[id];

			while (currentMenu && typeof currentMenu === 'object' && 'id' in currentMenu) {
				breadcrumb.unshift(currentMenu);
				currentMenu = currentMenu.parent_id ? state.menuMap[currentMenu.parent_id] : undefined;
			}

			return breadcrumb;
		}
	}
});