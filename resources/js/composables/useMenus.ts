import { ref } from 'vue'
import type { MenuNode } from '@/types/MenuNode'

export function useMenus() {
  const menus = ref<MenuNode[]>([])

  const fetchMenuTree = async (): Promise<MenuNode[]> => {
    const { data } = await window.axios.get('/api/menus/tree')
    menus.value = data
    return data
  }

  const createMenu = async (payload: Partial<MenuNode>) => {
    return await window.axios.post('/api/menus', payload)
  }

  const updateMenu = async (id: number, payload: Partial<MenuNode>) => {
    return await window.axios.put(`/api/menus/${id}`, payload)
  }

  const deleteMenu = async (id: number) => {
    return await window.axios.delete(`/api/menus/${id}`)
  }

  return {
    menus,
    fetchMenuTree,
    createMenu,
    updateMenu,
    deleteMenu,
  }
}