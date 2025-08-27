<script setup lang="ts">
	import { ref, watch, computed } from 'vue';
	import Modal from '@/components/ui/modal/Modal.vue';
	import InputError from '@/components/InputError.vue';
	import { Input } from '@/components/ui/input';
	import { Button } from '@/components/ui/button';
	import CheckboxGroup from '@/components/ui/checkbox/CheckboxGroup.vue';
	import Select from '@/components/ui/dropdown-menu/DropdownMenu.vue';
	import RoleDropdownSelector from '@/pages/Core/Users/RoleDropdownSelector.vue';
	import { useUsers } from '@/composables/useUsers';
	import type { User, Role, Permission } from '@/types';

	const props = defineProps<{
		user?: User
		roles?: Role[]
		permissions?: Permission[]
		open: boolean
	}>()

	const form = ref({
		name: '',
		email: '',
		password: '',
		roleIds: [] as number[],
		permissionIds: [] as number[],
	})

	const emit = defineEmits<{
		(e: 'close'): void
		(e: 'saved'): void
	}>()

	const { createUser, updateUser } = useUsers()

	const isSubmitting = ref(false)
	const isEditMode = computed(() => !!props.user)
	const errors = ref<Partial<Record<keyof typeof form.value, string>>>({})
	const isReady = computed(() => !!props.roles && !!props.permissions)

	const validateForm = (): boolean => {
		errors.value = {}

		if (!form.value.name.trim()) {
			errors.value.name = 'Name is required.'
		}

		if (!form.value.email.trim()) {
			errors.value.email = 'Email is required.'
		} else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(form.value.email)) {
			errors.value.email = 'Email is invalid.'
		}

		if (!isEditMode.value && !form.value.password.trim()) {
			errors.value.password = 'Password is required for new users.'
		}

		if (form.value.roleIds.length === 0) {
			errors.value.roleIds = 'At least one role must be selected.'
		}

		console.log('Validation errors:', errors.value);
		return Object.keys(errors.value).length === 0
	}

	watch(
		() => props.user,
		(user) => {
			if (user) {
				form.value.name = user.name
				form.value.email = user.email
				form.value.password = ''
				form.value.roleIds = user.roles?.map(r => r.id)
				form.value.permissionIds = user.permissions?.map(p => p.id) ?? []
			} else {
				form.value.name = ''
				form.value.email = ''
				form.value.password = ''
				form.value.roleIds = []
				form.value.permissionIds = []
			}
		},
		{ immediate: true }
	)

	const handleSubmit = async () => {
		console.log('Submitting form with data:', form.value);
		if (!validateForm()) return

		isSubmitting.value = true
		try {
			if (isEditMode.value && props.user) {
				await updateUser(props.user.id, form.value)
			} else {
				console.log('Creating user with data:', form.value);
				await createUser(form.value)
			}
			emit('saved')
		} catch (error) {
			console.error('User save failed:', error)
		} finally {
			isSubmitting.value = false
		}
	}

	console.log('userprops', props)
	</script>

<template>
	<div class="max-h-[90vh] overflow-y-auto">
		<Modal :open="true" @close="emit('close')">
			<template #title>
				{{ isEditMode ? 'Edit User' : 'Create User' }}
			</template>

			<form id="userForm" @submit.prevent="handleSubmit" class="grid grid-cols-1 gap-6" v-if="isReady">
				<div class="grid gap-2">
					<Input
						v-model="form.name"
						label="Name"
						placeholder="e.g. Jane Doe"
						required
						:error="errors.name"
					/>
					<InputError :message="errors.name" />
				</div>

				<div class="grid gap-2">
					<Input
						v-model="form.email"
						label="Email"
						placeholder="e.g. jane@example.com"
						type="email"
						required
						:error="errors.email"
					/>
				</div>

				<div class="grid gap-2">
					<Input
						v-model="form.password"
						label="Password"
						placeholder="Enter password"
						type="password"
						:required="!isEditMode"
						:error="errors.password"
					/>
				</div>

				<div class="grid gap-2">
					<RoleDropdownSelector
						v-model="form.roleIds"
						:roles="(props.roles ?? []).map(r => ({ label: r.name, value: r.id }))"
						:multiple="true"
						:disabled="false"
						class="cursor-pointer"
						placeholder="Select Role(s)"
					/>
				</div>

				<div class="grid gap-2">
					<label class="text-sm font-medium">Permissions</label>
					<div class="grid grid-cols-2 gap-2">
						<CheckboxGroup
							v-model="form.permissionIds"
							:options="(props.permissions ?? []).map(p => ({ label: p.name, value: p.id }))"
						/>
					</div>
				</div>
			</form>

			<div v-else class="text-sm text-muted">Loading form data... </div>

			<template #footer>
				<div class="flex justify-end space-x-2 mt-6">
				<Button variant="ghost" @click="emit('close')" class="cursor-pointer">Cancel</Button>
				<Button :loading="isSubmitting" form="userForm" @click="handleSubmit" class="cursor-pointer">
					{{ isEditMode ? 'Update' : 'Create' }}
				</Button>
				</div>
			</template>
		</Modal>
	</div>
</template>