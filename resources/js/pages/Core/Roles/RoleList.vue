<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoles } from '@/composables/useRoles'
import type { Role } from '@/types'

const { fetchRoles, deleteRole } = useRoles()

const roles = ref<Role[]>([])
const isLoading = ref(true)

onMounted(async () => {
  roles.value = await fetchRoles()
  isLoading.value = false
})

const handleDelete = async (id: number) => {
  await deleteRole(id)
  roles.value = await fetchRoles() // Refresh list
}
</script>

<template>
  <div class="space-y-4">
    <h2 class="text-lg font-semibold">Roles</h2>
    <div v-if="isLoading">Loading...</div>
    <table
      v-else
      class="min-w-full border border-neutral-300 rounded-md overflow-hidden"
    >
      <thead>
        <tr class="bg-neutral-100 text-left">
          <th class="px-4 py-2">Name</th>
          <th class="px-4 py-2">Permissions</th>
          <th class="px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="role in roles"
          :key="role.id"
          class="border-t hover:bg-neutral-50"
        >
          <td class="px-4 py-2">{{ role.name }}</td>
          <td class="px-4 py-2">{{ role.permissions.length }} permission(s)</td>
          <td class="px-4 py-2 space-x-2">
            <button class="text-sm text-blue-600 hover:underline">Edit</button>
            <button
              class="text-sm text-red-600 hover:underline"
              @click="handleDelete(role.id)"
            >
              Delete
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>