<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
defineProps<{ users: Array<any> }>()

const edit = (id: number) => router.visit(route('core.users.edit', id))
</script>

<template>
  <AppLayout>
    <Head title="Users" />
    <div class="p-6">
      <h1 class="text-xl font-bold mb-4">Users</h1>
      <table class="min-w-full table-auto text-sm">
        <thead class="bg-gray-100">
          <tr><th>Name</th><th>Email</th><th>Roles</th><th /></tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.roles.map(r => r.name).join(', ') }}</td>
            <td><button @click="edit(user.id)">Edit</button></td>
          </tr>
        </tbody>
      </table>
    </div>
  </AppLayout>
</template>