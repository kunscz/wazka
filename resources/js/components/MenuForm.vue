<script setup lang="ts">
import { reactive, watch, toRefs, ref } from 'vue'
import type { MenuNode } from '@/types/MenuNode'
import { useMenus } from '@/composables/useMenus'
import { usePermissions } from '@/composables/usePermissions'
import { useRoutes } from '@/composables/useRoutes'

const props = defineProps<{
	menu: MenuNode | null
	parentOptions:MenuNode[]
}>()

const emit = defineEmits<{ (e: 'saved'): void }>()

const { createMenu, updateMenu } = useMenus()
const { fetchPermissions, attachPermissionToMenu } = usePermissions()

const availablePermissions = ref<string[]>([])
const selectedPermission = ref('')
const permissionList = ref<string[]>([])

const routeSuggestions = ref<string[]>([])
const filteredRoutes = ref<string[]>([])
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
})

watch(
  () => props.menu,
  async (menu) => {
    if (menu) {
      form.label = menu.label
      form.route_name = menu.route_name ?? ''
      form.url = menu.url ?? ''
      form.icon = menu.icon ?? ''
      form.parent_id = menu.parent_id ?? null
      form.sort_order = menu.sort_order ?? 99
      form.is_active = menu.is_active
      permissionList.value = menu.permissions ?? []
    } else {
      Object.assign(form, {
        label: '',
        route_name: '',
        url: '',
        icon: '',
        parent_id: null,
        sort_order: 99,
        is_active: true,
        is_manual: true,
      })
      permissionList.value = []
    }

    availablePermissions.value = await fetchPermissions()
  },
  { immediate: true }
)

watch(() => form.route_name, async (val) => {
  if (!routeSuggestions.value.length) {
    routeSuggestions.value = await fetchRoutes()
  }
  filteredRoutes.value = routeSuggestions.value.filter(r =>
    r.toLowerCase().includes(val.toLowerCase())
  )
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
	<div class="bg-white p-4 rounded shadow">
		<h2 class="text-lg font-semibold mb-4">
			{{ props.menu ? 'Edit Menu' : 'Create Menu' }}
		</h2>

		<form @submit.prevent="handleSubmit" class="space-y-4">
			<input v-model="form.label" type="text" class="input" placeholder="Label" required />

			<input v-model="form.route_name" type="text" class="input" placeholder="Route Name" />
			<AutocompleteInput
				v-model="form.route_name"
				:options="routeSuggestions"
				placeholder="Search route name"
			/>

			<input v-model="form.url" type="text" class="input" placeholder="External URL" />
			<input v-model="form.icon" type="text" class="input" placeholder="Icon" />
			<input v-model.number="form.sort_order" type="number" class="input" placeholder="Sort Order" />
			
			<div class="flex items-center gap-2">
			<input v-model="form.is_active" type="checkbox" />
			<label class="text-sm">Active</label>
			</div>

			<button type="submit" class="btn btn-primary">
			{{ props.menu ? 'Update' : 'Create' }}
			</button>
		</form>

		<!-- Linked Permissions -->
		<div v-if="permissionList.length" class="mt-6">
			<h3 class="text-sm font-semibold">Linked Permissions</h3>
			<ul class="mt-1 list-disc list-inside text-sm text-gray-600">
			<li v-for="perm in permissionList" :key="perm">{{ perm }}</li>
			</ul>
		</div>

		<!-- Permission Picker -->
		<div v-if="props.menu" class="mt-6">
			<h3 class="text-sm font-semibold mb-2">Attach Permission</h3>
			<select v-model="selectedPermission" class="input">
			<option value="">-- Select Permission --</option>
			<option v-for="perm in availablePermissions" :key="perm" :value="perm">
				{{ perm }}
			</option>
			</select>
			<button @click="handleAttachPermission" class="btn btn-secondary mt-2">
			Attach
			</button>
		</div>
	</div>
   
	<div>
		<label class="block text-sm font-medium">Parent Menu</label>
		<select v-model="form.parent_id" class="input">
			<option :value="null">-- No Parent --</option>
			<option
				v-for="parent in parentOptions"
				:key="parent.id"
				:value="parent.id"
			>
				{{ parent.label }}
			</option>
		</select>
	</div>
</template>

<style scoped>
.input {
	width: 100%;
	padding: 0.5rem;
	border: 1px solid #d1d5db;
	border-radius: 4px;
}
.btn-primary {
	background-color: #2563eb;
	color: white;
	padding: 0.5rem 1rem;
	border-radius: 4px;
}
.btn-secondary {
	background-color: #4b5563;
	color: white;
	padding: 0.4rem 0.8rem;
	border-radius: 4px;
}
.autocomplete-results {
  border: 1px solid #ccc;
  max-height: 200px;
  overflow-y: auto;
  margin-top: 0.25rem;
}
.autocomplete-result {
  padding: 0.5rem;
  cursor: pointer;
}
.autocomplete-result:hover {
  background-color: #f3f4f6;
}

</style>