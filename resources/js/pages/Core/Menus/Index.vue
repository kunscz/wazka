<script setup lang="ts">
	import { ref, onMounted, computed } from 'vue'
	import AppLayout from '@/layouts/AppLayout.vue'
	import MenuTree from '@/components/MenuTree.vue'
	import MenuForm from '@/components/MenuForm.vue'
	import { useMenus } from '@/composables/useMenus'
	import type { MenuNode } from '@/types/MenuNode'
	import { filterTreeByActive } from '@/utils/filterTreeByActive'

	const menus = ref<MenuNode[]>([])
	const activeMenu = ref<MenuNode | null>(null)
	const { fetchMenuTree } = useMenus()

	const showInactive = ref(false)

	const loadMenus = async () => {
		// if (!menus) return
		// menus.value = await fetchMenuTree()

		const result = await fetchMenuTree()
		// console.log('typeof: ', typeof(result))

		menus.value = Array.isArray(result) ? result : []
		// console.log('MenuTree:', menus.value)
	}

	// const filteredMenus = computed(() => {
	// 	const list = menus.value ?? []
	// 	return showInactive.value
	// 		? list
	// 		: list.filter(menu => menu.is_active)
	// })
	const filteredMenus = computed(() => {
		return filterTreeByActive(menus.value ?? [], showInactive.value)
	})

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
		<MenuTree :menus="filteredMenus" :showInactive="showInactive" @select="handleSelect" @toggle-inactive="showInactive = $event" />

		<!-- Menu Form with Parent Options -->
		<MenuForm
			:menu="activeMenu"
			:parent-options="filteredMenus.filter(m => !activeMenu || m.id !== activeMenu.id)"
			@saved="handleSaved"
		/>
	</div>
	</AppLayout>
</template>