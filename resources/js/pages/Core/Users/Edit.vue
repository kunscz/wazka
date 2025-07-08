<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { useForm, Head } from '@inertiajs/vue3'

const props = defineProps<{ user: any, roles: Record<string, string> }>()
const user = props.user

const form = useForm({
  name: user.name,
  email: user.email,
  roles: user.roles.map((r: any) => r.name)
})

const submit = () => form.put(route('core.users.update', user.id))
</script>

<template>
  <AppLayout>
    <Head title="Edit User" />
    <form @submit.prevent="submit" class="space-y-4 p-6 max-w-lg mx-auto">
      <div>
        <label>Name</label>
        <input v-model="form.name" type="text" class="input" />
      </div>
      <div>
        <label>Email</label>
        <input v-model="form.email" type="email" class="input" />
      </div>
      <div>
        <label>Roles</label>
        <select v-model="form.roles" multiple class="input">
          <option v-for="(label, name) in roles" :key="name" :value="name">{{ name }}</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </AppLayout>
</template>