<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const name = ref('')
const permissionIds = ref<number[]>([])

function submit() {
  router.post('/admin/roles', {
    name: name.value,
    permissions: permissionIds.value,
  })
}
</script>

<template>
  <div>
    <h1>Create Role</h1>
    <input v-model="name" placeholder="Role name" />
    <!-- Checkbox list of permissions goes here -->
    <button @click="submit">Create</button>
  </div>
</template>