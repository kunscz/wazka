<script setup lang="ts">
import { resolveIcon } from '@/utils/icons'
import { ref, onMounted } from 'vue'
import type { LucideIcon } from 'lucide-vue-next'

const props = defineProps<{ name: string }>()
const icon = ref<LucideIcon>()

onMounted(async () => {
  icon.value = await resolveIcon(props.name)
})
</script>

<template>
  <component :is="icon" class="w-4 h-4" />
</template>