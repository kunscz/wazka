<script setup lang="ts">
import { resolveIcon } from '@/utils/icons'
import { ref, watch, onMounted } from 'vue'
import type { LucideIcon } from 'lucide-vue-next'

const props = defineProps<{
	modelValue: boolean
	iconOn: string // e.g. "Eye"
	iconOff: string // e.g. "EyeOff"
	size?: number
	color?: string
	class?: string
	tooltip?: string
}>()

const emit = defineEmits<{
	(e: 'update:modelValue', value: boolean): void
}>()

const icon = ref<LucideIcon>()

const loadIcon = async () => {
	const name = props.modelValue ? props.iconOn : props.iconOff
	icon.value = await resolveIcon(name)
}

watch(() => props.modelValue, loadIcon)
onMounted(loadIcon)

const toggle = () => emit('update:modelValue', !props.modelValue)
</script>

<template>
	<button
		type="button"
		:title="props.tooltip"
		@click="toggle"
		:class="`inline-flex items-center justify-center ${props.class ?? ''}`"
	>
		<component
			:is="icon"
			:class="`w-${props.size ?? 4} h-${props.size ?? 4}`"
			:style="{ color: props.color ?? 'inherit' }"
			role="img"
			aria-hidden="true"
		/>
	</button>
</template>