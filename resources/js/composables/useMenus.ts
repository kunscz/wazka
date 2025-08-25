import { ref } from 'vue'
import type { MenuNode } from '@/types/MenuNode'

export function useMenus() {
	const menus = ref<MenuNode[]>([])

	const fetchMenuTree = async (): Promise<MenuNode[]> => {
		const { data } = await window.axios.get('/api/core/menus')
		menus.value = data
		return data
	}

	const storeMenu = async (payload: Record<string, any>) => {
		const res = await window.axios.post('/core/menus', payload)
		return res.data
	}

	const createMenu = async (payload: Partial<MenuNode>) => {
		return await window.axios.post('/api/core/menus', payload)
	}

	const updateMenu = async (id: number, payload: Partial<MenuNode>) => {
		const res = await window.axios.put(`/api/core/menus/${id}`, payload)
		return res.data;
	}

	const deleteMenu = async (id: number) => {
		const res = await window.axios.delete(`/api/core/menus/${id}`)
		return res.data;
	}

	return {
		menus,
		fetchMenuTree,
		storeMenu,
		createMenu,
		updateMenu,
		deleteMenu,
	}
}