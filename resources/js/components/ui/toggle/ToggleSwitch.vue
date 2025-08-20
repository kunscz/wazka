<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
	modelValue: boolean
	label?: string
	id?: string
	icon?: string // e.g. "eye", "eye-off"
	tooltip?: string
}>()

const emit = defineEmits<{
	(e: 'update:modelValue', value: boolean): void
}>()

const toggleId = computed(() => props.id ?? 'toggle-' + Math.random().toString(36).slice(2))
</script>

<template>
	<div class="flex items-center gap-2" :title="props.tooltip">
		<label :for="toggleId" class="text-sm font-medium flex items-center gap-1">
			<!-- Optional icon -->
			<span v-if="props.icon">
				<i :class="`icon-${props.icon}`" class="text-base opacity-70" />
			</span>
			{{ label }}
		</label>

		<input
			:id="toggleId"
			type="checkbox"
			class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
			:checked="modelValue"
			@change="emit('update:modelValue', ($event.target as HTMLInputElement)?.checked ?? false)"
		/>
	</div>
</template>