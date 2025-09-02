<script setup lang="ts">
import { ref, onMounted, watch, reactive } from 'vue'
import Modal from '@/components/ui/modal/Modal.vue'
import Input from '@/components/ui/input/Input.vue'
import Button from '@/components/ui/button/Button.vue'
import PermissionMultiSelect from './PermissionMultiSelect.vue'

import type { Role, Permission } from '@/types'
import { useRoles } from '@/composables/useRoles'
import InputError from '@/components/InputError.vue'

const props = defineProps<{
	role?: Role
}>()

const emit = defineEmits<{ (e: 'close'): void; (e: 'saved'): void }>()

// const form = ref({ name: '', guard: '', permissionIds: [] as number[] })
const form = reactive({
	name: '',
	guard: '',
	permissionIds: [] as number[],
	errors: {} as Record<string, string>
})

const availablePermissions = ref<Permission[]>([])
const errors = ref<Partial<Record<keyof typeof form, string>>>({})

const {
	fetchPermissions,
	createRole,
	updateRole,
	syncRolePermissions
} = useRoles()

onMounted(async () => {
	availablePermissions.value = await fetchPermissions()

	if (props.role) {
		form.name = props.role.name
		form.guard = props.role.guard_name
		form.permissionIds = props.role.permissions.map(p => p.id)
	}

	console.log('Available permissions loaded:', form.permissionIds)
})

watch(() => props.role, (role) => {
	if (role) {
		form.name = role.name
		form.guard = role.guard_name
		form.permissionIds = role.permissions.map(p => p.id)
	} else {
		// form = { name: '', guard: '', permissionIds: [] }
	}
})

watch(() => form.permissionIds, (newVal) => {
	console.log('Permission IDs updated:', newVal)
})

const validateForm = (): boolean => {
		errors.value = {}

		if (!form.name.trim()) {
			errors.value.name = 'Name is required.'
		}

		if (!form.guard.trim()) {
			errors.value.guard = 'Guard is required.'
		}

		return Object.keys(errors.value).length === 0
	}

const handleSubmit = async () => {
	console.log('Submitting form with data:', form)
	if (!validateForm()) return

	const payload = {
		name: form.name,
		guard_name: form.guard,
		permissions: form.permissionIds
	}

	let roleId = props.role?.id

	if (roleId) {
		await updateRole(roleId, payload)
	} else {
		const created = await createRole(payload)
		console.log('Created role:', created)
		roleId = created.role.id
	}

	await syncRolePermissions(roleId!, form.permissionIds)
	emit('saved')
}
</script>

<template>
  <Modal title="Role Details" @close="emit('close')">
    <form class="space-y-6" @submit.prevent="handleSubmit">
      <div>
        <label class="block text-sm font-medium mb-1" for="name">Role Name</label>
        <Input id="name" v-model="form.name" placeholder="e.g. Administrator" />
		  <InputError :message="errors.name" />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="guard">Guard Name</label>
        <Input id="guard" v-model="form.guard" placeholder="e.g. web, api" />
		  <InputError :message="errors.guard" />
      </div>

      <div>
        <label class="block text-sm font-medium mb-2">Permissions</label>
        <PermissionMultiSelect
          v-model="form.permissionIds"
          :options="availablePermissions"
        />
      </div>

      <div class="flex justify-end gap-2">
        <Button appearance="ghost" class="cursor-pointer" @click="emit('close')">Cancel</Button>
        <Button class="cursor-pointer" type="submit">{{ props.role ? 'Update' : 'Create' }}</Button>
      </div>
    </form>
  </Modal>
</template>
