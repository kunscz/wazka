<script setup lang="ts">
	import { ref, onMounted } from 'vue'
	import AppLayout from '@/layouts/AppLayout.vue'
	import MenuTree from '@/components/MenuTree.vue'
	import MenuForm from '@/components/MenuForm.vue'
	import { useMenus } from '@/composables/useMenus'
	import type { MenuNode } from '@/types/MenuNode'

	const menus = ref<MenuNode[]>([])
	const activeMenu = ref<MenuNode | null>(null)
	const { fetchMenuTree } = useMenus()

	const loadMenus = async () => {
		if (!menus) return
		menus.value = await fetchMenuTree()
		console.log('MenuTree:', menus.value)
	}

	const handleSelect = (menu: MenuNode | null) => {
		if (!menu) {
			activeMenu.value = menu;
		} else {
			const resolvedParent = 
				menu.parent_id !== null
					? menus.value.find(m => m.id === menu.parent_id)
					: null
				
			activeMenu.value = {
				...menu,
				parent: resolvedParent
					? { label: resolvedParent.label, id: resolvedParent.id }
					: { label: '-- No Parent --', id: null }
			}
		}
	}

	const handleSaved = async () => {
		await loadMenus()
		activeMenu.value = null
	}

	onMounted(loadMenus)
</script>

<template>
	<AppLayout>
	<div class="grid grid-cols-2 gap-6 p-6">
		<!-- Menu Tree Sidebar -->
		<MenuTree :menus="menus" @select="handleSelect" />

		<!-- Menu Form with Parent Options -->
		<MenuForm
			:menu="activeMenu"
			:parent-options="menus.filter(m => !activeMenu || m.id !== activeMenu.id)"
			@saved="handleSaved"
		/>
	</div>
	</AppLayout>
</template>