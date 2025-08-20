<script setup lang="ts">
import { resolveIcon } from '@/utils/icons'
import { ref, onMounted } from 'vue'
import type { LucideIcon } from 'lucide-vue-next'

const props = defineProps<{
	name: string
	size?: number
	color?: string
	class?: string
	ariaLabel?: string
}>()
const icon = ref<LucideIcon>()

onMounted(async () => {
	icon.value = await resolveIcon(props.name)
})
</script>

<template>
	<component
		:is="icon"
		:class="`w-${props.size ?? 4} h-${props.size ?? 4} ${props.class ?? ''}`"
		:style="{ color: props.color ?? 'inherit' }"
		:aria-label="props.ariaLabel ?? props.name"
		role="img"
	/>
</template>