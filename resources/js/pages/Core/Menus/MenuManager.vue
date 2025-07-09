<script setup lang="ts">
import { ref, onMounted } from 'vue'
import MenuTree from '@/components/MenuTree.vue'
import MenuForm from '@/components/MenuForm.vue'
import { useMenus } from '@/composables/useMenus'

const { fetchMenuTree } = useMenus()
const menus = ref([])
const activeMenu = ref(null)

const loadMenus = async () => {
  menus.value = await fetchMenuTree()
}

const handleSelect = (menu) => {
  activeMenu.value = menu
}

const handleSaved = async () => {
  await loadMenus()
}
onMounted(loadMenus)
</script>

<template>
  <div class="grid grid-cols-2 gap-6 p-6">
    <MenuTree :menus="menus" @select="handleSelect" />
    <MenuForm :menu="activeMenu" @saved="handleSaved" />
  </div>
</template>