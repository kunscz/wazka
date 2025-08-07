<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import Card from '@/components/ui/card/Card.vue'
import Button from '@/components/ui/button/Button.vue'
import UserFormModal from './UserFormModal.vue'
import { useUsers } from '@/composables/useUsers'
import type { UserResponse } from '@/composables/useUsers'
import type { Role, User } from '@/types'
import AppLayout from '@/layouts/AppLayout.vue'

const { fetchUsers, deleteUser } = useUsers()

const response = ref<UserResponse>()
const isModalOpen = ref(false)
const selectedUser = ref<User | null>(null)
const isLoading = ref(false)

const users = computed(() => response.value?.users ?? [])
const roles = computed(() => response.value?.roles ?? [])
const permissions = computed(() => response.value?.permissions ?? [])

const loadUsers = async () => {
   try {
      isLoading.value = true
      response.value = await fetchUsers()
      console.log('Users loaded:', response.value)
   } catch (error) {
      console.error('Failed to load users:', error)
      // Optionally show toast or fallback UI
   } finally {
      isLoading.value = false
   }
}

onMounted(async () => {
   await loadUsers()
})

const handleCreate = () => {
   selectedUser.value = null
   isModalOpen.value = true
}

const handleEdit = (user: User) => {
   selectedUser.value = user
   isModalOpen.value = true
}

const handleDelete = async (user: User) => {
   const index = response.value?.users.findIndex(u => u.id === user.id) ?? -1

   if (index >= 0) {
      response.value?.users.splice(index, 1)
   }
   
   try {
      await deleteUser(user.id)
   } catch (error) {
      console.error('Delete failed:', error)
      await loadUsers() // fallback reload
   }
}

const handleSaved = async () => {
   isModalOpen.value = false
   selectedUser.value = null
   await loadUsers()
}
</script>

<template>
   <AppLayout>
   <Card class="space-y-6 p-6">
      <div class="flex justify-between items-center">
      <h2 class="text-lg font-semibold">User Management</h2>
      <Button @click="handleCreate" size="sm">+ Create User</Button>
      </div>

      <div v-if="response?.users?.length" class="overflow-x-auto">
      <table class="min-w-full text-sm border rounded">
         <thead class="bg-muted text-muted-foreground">
            <tr>
            <th class="px-4 py-2">#</th>
            <th class="px-4 py-2">Name</th>
            <th class="px-4 py-2">Email</th>
            <th class="px-4 py-2">Roles</th>
            <th class="px-4 py-2">Permissions</th>
            <th class="px-4 py-2">Actions</th>
            </tr>
         </thead>
         <tbody>
            <tr v-for="(user, index) in response.users" :key="user.id"
               class="border-t hover:bg-muted/40"
            >
               <td class="px-4 py-2">{{ index + 1 }}</td>
               <td class="px-4 py-2 font-medium">{{ user.name }}</td>
               <td class="px-4 py-2">{{ user.email }}</td>
               <td class="px-4 py-2">
                  <span v-if="user.roles.length">
                     {{ user.roles.map(r => r.name).join(', ') }}
                  </span>
                  <span v-else class="text-muted-foreground italic">None</span>
               </td>
               <td class="px-4 py-2">
                  <span v-if="user.permissions.length">
                     {{ user.permissions.map(p => p.name).join(', ') }}
                  </span>
                  <span v-else class="text-muted-foreground italic">None</span>
               </td>
               <td class="px-4 py-2 space-x-2">
                  <Button size="sm" variant="ghost" @click="handleEdit(user)">Edit</Button>
                  <Button size="sm" variant="ghost" @click="handleDelete(user)">Delete</Button>
               </td>
            </tr>
         </tbody>
      </table>
      </div>

      <div v-else class="text-sm text-muted-foreground italic">
      No users found. Click "Create User" to add one.
      </div>

      <UserFormModal
         v-if="isModalOpen"
         :open="isModalOpen"
         :user="selectedUser ?? undefined"
         :roles="roles"
         :permissions="permissions"
         @close="isModalOpen = false"
         @saved="handleSaved"
      />
   </Card>
   </AppLayout>
</template>