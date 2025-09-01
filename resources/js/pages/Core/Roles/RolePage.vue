<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoles } from '@/composables/useRoles'
import type { Role } from '@/types'
import Card from '@/components/ui/card/Card.vue'
import { Button } from '@/components/ui/button'
import RoleFormModal from './RoleFormModal.vue'
import AppLayout from '@/layouts/AppLayout.vue'

const { fetchRoles, deleteRole } = useRoles()

const roles = ref<Role[]>([])
const isModalOpen = ref(false)
const selectedRole = ref<Role | null>(null)

const loadRoles = async () => {
	roles.value = await fetchRoles()
}

onMounted(loadRoles)

const handleSaved = async () => {
	isModalOpen.value = false
	selectedRole.value = null
	await loadRoles()
}

const handleEdit = (role: Role) => {
	console.log('Editing role:', role);
	selectedRole.value = role
	isModalOpen.value = true
}

const handleCreate = () => {
	selectedRole.value = null
	isModalOpen.value = true
}

const handleDelete = async (role: Role) => {
	await deleteRole(role.id)
	await loadRoles()
}
</script>

<template>
	<AppLayout>
	<Card class="space-y-6 p-6">
		<div class="flex items-center justify-between">
			<h2 class="text-lg font-semibold">Role Management</h2>
			<Button @click="handleCreate" size="sm">+ Create Role</Button>
		</div>

		<div v-if="roles.length" class="overflow-x-auto">
			<table class="min-w-full text-sm border rounded">
			<thead class="bg-muted text-muted-foreground">
				<tr>
					<th class="px-4 py-2 text-left">#</th>
					<th class="px-4 py-2 text-left">Name</th>
					<th class="px-4 py-2 text-left">Guard</th>
					<th class="px-4 py-2 text-left">Permissions</th>
					<th class="px-4 py-2 text-left">Actions</th>
				</tr>
			</thead>
			<tbody>
				<tr
					v-for="(role, index) in roles"
					:key="role.id"
					class="border-t hover:bg-muted/40"
				>
					<td class="px-4 py-2">{{ index + 1 }}</td>
					<td class="px-4 py-2 font-medium">{{ role.name }}</td>
					<td class="px-4 py-2">{{ role.guard_name }}</td>
					<td class="px-4 py-2">
						<span v-if="role.permissions.length">
							{{ role.permissions.map(p => p.name).join(', ') }}
						</span>
						<span v-else class="text-muted-foreground italic">None</span>
					</td>
					<td class="px-4 py-2 space-x-2">
						<Button size="sm" variant="ghost" @click="handleEdit(role)">Edit</Button>
						<Button size="sm" variant="ghost" @click="handleDelete(role)">Delete</Button>
					</td>
				</tr>
			</tbody>
			</table>
		</div>

		<div v-else class="text-sm text-muted-foreground italic">
			No roles found. Click "Create Role" to add one.
		</div>

		<RoleFormModal
			v-if="isModalOpen"
			:role="selectedRole ?? undefined"
			@close="isModalOpen = false"
			@saved="handleSaved"
		/>
	</Card>
	</AppLayout>
</template>