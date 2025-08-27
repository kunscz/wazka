<script setup lang="ts">
	import {
		DropdownMenu, DropdownMenuTrigger,
		DropdownMenuContent, DropdownMenuCheckboxItem,
		DropdownMenuRadioGroup, DropdownMenuRadioItem,
		DropdownMenuSeparator, DropdownMenuLabel,
	} from '@/components/ui/dropdown-menu';
	import type { DropdownMenuCheckboxItemProps } from 'reka-ui';

	import { Button } from '@/components/ui/button';
	import { computed, watch, reactive, watchEffect } from 'vue'

	const props = defineProps<{
		roles: { value: number; label: string }[]
		modelValue: number[]
		multiple?: boolean
		disabled?: boolean
		placeholder?: string
	}>()
	
	const emit = defineEmits<{
		(e: 'update:modelValue', value: number[]): void
	}>()

	const selectedRoleId = computed<string | undefined>(() => 
		props.modelValue.length > 0 ? String(props.modelValue[0]) : undefined
	)

	const selectedRoles = computed(() => Array.isArray(props.modelValue) ? props.modelValue : [])

	const isSelected = (id: number) => {
		return Array.isArray(props.modelValue)
			? props.modelValue.includes(id)
			: props.modelValue === id
	}

	const toggleRole = (id: number, checked: boolean) => {
		console.log('modelValue: ', props.modelValue)
		if (!Array.isArray(props.modelValue)) return
		const updated = checked
			? [...props.modelValue, id]
			: props.modelValue.filter(rid => rid !== id)
		emit('update:modelValue', updated)
	}

	const handleRadioUpdate = (val: string) => {
		const num = Number(val)
		if (!isNaN(num)) emit('update:modelValue', [num])
	}

	const selectedLabel = computed(() => {
		if (!Array.isArray(props.modelValue)) return props.placeholder
		const labels = props.modelValue
			.map(id => props.roles.find(r => r.value === id)?.label)
			.filter(Boolean)
		return labels.length ? "Roles: " + labels.join(', ') : props.placeholder
	})

	const roleCheckboxMap = reactive<Record<number, boolean>>({})

	watchEffect(() => {
		props.roles.forEach(role => {
			roleCheckboxMap[role.value] = props.modelValue.includes(role.value)
		})
	})

	watch(roleCheckboxMap, (newMap) => {
		const updated = Object.entries(newMap)
			.filter(([_, checked]) => checked)
			.map(([id]) => Number(id))
		emit('update:modelValue', updated)
	}, { deep: true })

	console.log('RoleDropdownSelector initialized with props:', props)
	console.log('roleChecked', roleCheckboxMap[2]);
</script>

	<template>
	<DropdownMenu>
		<DropdownMenuTrigger as-child>
			<Button :disabled="props.disabled" variant="outline">
			{{ selectedLabel }}
			</Button>
		</DropdownMenuTrigger>

		<DropdownMenuContent class="min-w-[12rem]">
			<template v-if="props.multiple">
				<DropdownMenuLabel>Roles</DropdownMenuLabel>
				<DropdownMenuSeparator />
				<DropdownMenuCheckboxItem
					v-for="role in props.roles"
					:key="role.value"
					v-model:model-value="roleCheckboxMap[role.value]"
				>
					<!-- :checked="selectedRoles?.includes(role.value)" -->
					<!-- @update:checked="(checked: boolean) => toggleRole(role.value, checked)" -->
					{{ role.label }}
				</DropdownMenuCheckboxItem>
			</template>

			<template v-else>
			<DropdownMenuRadioGroup
				:model-value="selectedRoleId"
				@update:modelValue="handleRadioUpdate"
			>
				<DropdownMenuRadioItem
					v-for="role in props.roles"
					:key="role.value"
					:value="String(role.value)"
				>
					{{ role.label }}
				</DropdownMenuRadioItem>
			</DropdownMenuRadioGroup>
			</template>
		</DropdownMenuContent>
	</DropdownMenu>
	</template>