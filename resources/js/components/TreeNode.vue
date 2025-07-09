<script setup lang="ts">
const {menu} = defineProps<{
  menu: any
}>()

const emit = defineEmits(['select'])

const handleClick = () => {
  emit('select', menu)
}
</script>

<template>
  <li class="mb-1">
    <button
      class="text-left text-sm hover:text-blue-600"
      @click="handleClick"
    >
      <i :class="menu.icon" class="mr-1"></i>
      {{ menu.label }}
      <span v-if="menu.is_manual" class="ml-2 text-xs text-green-600">manual</span>
    </button>

    <ul v-if="menu.children?.length" class="pl-4 border-l border-gray-200 mt-1">
      <TreeNode
        v-for="child in menu.children"
        :key="child.id"
        :menu="child"
        @select="emit('select', $event)"
      />
    </ul>
  </li>
</template>