// stores/menu.ts
import { defineStore } from 'pinia';
import type { Menu } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { filterMenusByPermission } from '@/utils/menu';

export const useMenuStore = defineStore('menu', {
	state: () => ({
		rawMenus: [] as Menu[],
		filteredMenus: [] as Menu[],
	}),
	actions: {
		async fetchMenus() {
			const { data } = await window.axios.get('/api/menus');
			this.rawMenus = data;

			const page = usePage();
			const userPermission = page.props.auth.user.permissions.map(p => p.name);
			this.filteredMenus = filterMenusByPermission(this.rawMenus, userPermission);
		},
	},
});