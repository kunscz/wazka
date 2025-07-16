<template>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    width="16" height="16" viewBox="0 0 24 24" fill="none"
    stroke="currentColor" stroke-width="2"
    stroke-linecap="round" stroke-linejoin="round"
    :class="[
      'transition-transform duration-300 text-sidebar-text',
      open ? 'rotate-90' : 'rotate-0',
    ]"
  >
    <polyline points="6 9 12 15 18 9" />
  </svg>
</template>

<script setup lang="ts">
   defineProps<{ open: boolean }>()
</script>