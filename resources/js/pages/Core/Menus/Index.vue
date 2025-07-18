<script setup lang="ts">
import { ref, onMounted } from 'vue'
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

const handleSelect = (menu: MenuNode) => {
  if (!menu) return
  activeMenu.value = menu
}

const handleSaved = async () => {
  await loadMenus()
  activeMenu.value = null
}

onMounted(loadMenus)
</script>

<template>
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
</template>