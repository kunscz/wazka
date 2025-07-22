<script setup lang="ts">
	import { reactive, ref, watch, computed } from 'vue'
	import type { MenuNode } from '@/types/MenuNode'
	import { useMenus } from '@/composables/useMenus'
	import { usePermissions } from '@/composables/usePermissions'
	import { useRoutes } from '@/composables/useRoutes'

	// Starter Kit UI components
	import Card from '@/components/ui/card/Card.vue'
	import Input from '@/components/ui/input/Input.vue'
	import Label from '@/components/ui/label/Label.vue'
	import Checkbox from '@/components/ui/checkbox/Checkbox.vue'
	import Button from '@/components/ui/button/Button.vue'
	import Separator from '@/components/ui/separator/Separator.vue'
	import ComboboxInput from './ui/combobox/ComboboxInput.vue'
	import AutocompleteInput from '@/components/ui/autocomplete/AutocompleteInput.vue'

	const props = defineProps<{
		menu: MenuNode | null
		parentOptions: MenuNode[]
	}>()

	const emit = defineEmits<{ (e: 'saved'): void }>()

	const { createMenu, updateMenu } = useMenus()
	const { fetchPermissions, attachPermissionToMenu } = usePermissions()
	const { fetchRoutes } = useRoutes()

	const form = reactive({
		label: '',
		route_name: '',
		url: '',
		icon: '',
		sort_order: 99,
		parent_id: null as number | null,
		is_active: true,
		is_manual: true,
		parent: props.menu?.parent ?? { label: '-- No Parent --', id: null }
	})

	const availablePermissions = ref<string[]>([])
	const selectedPermission = ref('')
	const permissionList = ref<string[]>([])
	const routeSuggestions = ref<string[]>([])
	const isFocused = ref(false)

	const filteredRoutes = computed(() => {
	const input = form.route_name.toLocaleLowerCase()
	const filtered = routeSuggestions.value.filter(r =>
		r.toLowerCase().includes(input)
	)
	return [...new Set(filtered)]
	})

	watch(() => props.menu, async (menu) => {
	console.log('menuForm', menu);
	if (menu) {
		Object.assign(form, {
			label: menu.label,
			route_name: menu.route_name ?? '',
			url: menu.url ?? '',
			icon: menu.icon ?? '',
			sort_order: menu.sort_order ?? 99,
			parent_id: menu.parent_id ?? null,
			is_active: Boolean(menu.is_active),
			is_manual: menu.is_manual ?? true,
		})
		permissionList.value = menu.permissions ?? []
	} else {
		Object.assign(form, {
			label: '',
			route_name: '',
			url: '',
			icon: '',
			sort_order: 99,
			parent_id: null,
			is_active: true,
			is_manual: true,
		})
		permissionList.value = []
	}

	// get permissions route still broken
	try {
		availablePermissions.value = await fetchPermissions()
	} catch (err) {
		console.error('Failed to fetch permissions:', err)
	}
	
	})

	watch(() => form.route_name, async () => {
	if (!routeSuggestions.value.length) {
		routeSuggestions.value = await fetchRoutes()
	}
	})
	
	const handleSubmit = async () => {
	if (props.menu?.id) {
		await updateMenu(props.menu.id, form)
	} else {
		await createMenu(form)
	}
	emit('saved')
	}

	const handleAttachPermission = async () => {
	if (props.menu?.id && selectedPermission.value) {
		await attachPermissionToMenu(props.menu.id, selectedPermission.value)
		permissionList.value.push(selectedPermission.value)
		selectedPermission.value = ''
	}
	}
	</script>

	<template>
	<Card class="p-4 space-y-6">
		<div class="space-y-4">
			<div>
			<Label for="label">Label</Label>
			<Input id="label" v-model="form.label" placeholder="Menu label" />
			</div>

			<!-- <div>
			<Label for="route">Route Name</Label>
			<Input id="route" v-model="form.route_name" placeholder="Route (e.g. core.menus.index)"
				@focus="isFocused = true"
				@blur="isFocused = false"
			/>
			<div v-if="isFocused && filteredRoutes.length" class="mt-2 space-y-1 text-sm border rounded bg-popover p-2">
				<div
					v-for="r in filteredRoutes"
					:key="r"
					@mousedown.prevent="form.route_name = r"
					class="cursor-pointer px-2 py-1 hover:bg-muted rounded"
				>
					{{ r }}
				</div>
			</div>
			</div> -->

			<div>
			<Label for="route">Route Name</Label>
			<AutocompleteInput
				v-model="form.route_name"
				:options="filteredRoutes"
				placeholder="Search route name"
			/>
			</div>

			<div>
			<Label for="url">External URL</Label>
			<Input id="url" v-model="form.url" placeholder="Optional external link" />
			</div>

			<div>
			<Label for="icon">Icon</Label>
			<Input id="icon" v-model="form.icon" placeholder="Lucide icon name (e.g. ListTree)" />
			</div>

			<div>
			<Label for="sort">Sort Order</Label>
			<Input id="sort" type="number" v-model.number="form.sort_order" />
			</div>

			<div class="flex items-center gap-2 mt-2">
			<Checkbox id="active" v-model="form.is_active" />
			<Label for="active">Active</Label>
			</div>

			<!-- <div>
			<Label for="parent">Parent Menu</Label>
			<select id="parent" v-model="form.parent_id" class="w-full mt-1 rounded border p-2">
				<option :value="null">-- No Parent --</option>
				<option
					v-for="parent in parentOptions"
					:key="parent.id"
					:value="parent.id"
				>
					{{ parent.label }}
				</option>
			</select>
			</div> -->

			<div>
			<Label>Parent Menu</Label>
			<ComboboxInput
				v-model="form.parent"
				:options="[
					{ label: '-- No Parent --', id: null },
					...parentOptions.map(p => ({ label: p.label, id: p.id }))
				]"
				placeholder="Select parent menu"
			/>
			</div>

			<Button type="submit" class="mt-4 w-full" @click="handleSubmit">
			{{ props.menu ? 'Update Menu' : 'Create Menu' }}
			</Button>
		</div>

		<Separator />

		<div v-if="permissionList.length">
			<Label>Linked Permissions</Label>
			<ul class="list-disc list-inside text-sm mt-1 space-y-1">
			<li v-for="perm in permissionList" :key="perm">{{ perm }}</li>
			</ul>
		</div>

		<div v-if="props.menu" class="space-y-2">
			<Label>Attach Permission</Label>
			<select v-model="selectedPermission" class="w-full rounded border p-2">
			<option value="">-- Select Permission --</option>
			<option v-for="perm in availablePermissions" :key="perm" :value="perm">
				{{ perm }}
			</option>
			</select>
			<Button appearance="secondary" @click="handleAttachPermission">
			Attach
			</Button>
		</div>
	</Card>
	</template>