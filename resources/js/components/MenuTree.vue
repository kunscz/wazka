<script setup lang="ts">
defineProps<{
  menus: Array<any>
}>()

const emit = defineEmits(['select'])

const handleClick = (menu: any) => {
  emit('select', menu)
}
</script>

<template>
  <div class="space-y-2">
    <h2 class="text-lg font-semibold">Menu Tree</h2>
    <ul class="pl-2 border-l border-gray-300">
      <TreeNode
        v-for="menu in menus"
        :key="menu.id"
        :menu="menu"
        @select="handleClick"
      />
    </ul>
  </div>
</template>