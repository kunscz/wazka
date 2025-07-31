<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRoles } from '@/composables/useRoles';
import type { Role } from '@/types'

const props = defineProps<{
  role?: Role
}>()

const emit = defineEmits<{ (e: 'saved'): void }>()

const { createRole, updateRole, syncRolePermissions, fetchPermissions } = useRoles()

const name = ref(props.role?.name ?? '')
const allPermissions = ref<string[]>([])
const selectedPermissions = ref<string[]>([])

onMounted(async () => {
  allPermissions.value = await fetchPermissions()
})

watch(() => props.role, (r) => {
  name.value = r?.name ?? ''
  selectedPermissions.value = r?.permissions ?? []
})

const handleSubmit = async () => {
  let roleId = props.role?.id

  if (roleId) {
    await updateRole(roleId, { name: name.value })
    await syncRolePermissions(roleId, selectedPermissions.value)
  } else {
    const created = await createRole({ name: name.value })
    await syncRolePermissions(created.id, selectedPermissions.value)
  }

  emit('saved')
}
</script>

<template>
  <form class="space-y-6" @submit.prevent="handleSubmit">
    <div>
      <label class="block font-medium mb-1">Role Name</label>
      <input
        v-model="name"
        type="text"
        class="border rounded px-3 py-2 w-full"
        placeholder="e.g. Administrator"
      />
    </div>

    <div>
      <label class="block font-medium mb-1">Permissions</label>
      <div class="grid grid-cols-2 gap-2">
        <label
          v-for="perm in allPermissions"
          :key="perm"
          class="flex items-center gap-2 text-sm"
        >
          <input
            type="checkbox"
            :value="perm"
            v-model="selectedPermissions"
            class="accent-indigo-600"
          />
          {{ perm }}
        </label>
      </div>
    </div>

    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
      {{ props.role ? 'Update Role' : 'Create Role' }}
    </button>
  </form>
</template>