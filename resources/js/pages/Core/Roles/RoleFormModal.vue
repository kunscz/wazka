<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import Modal from '@/components/ui/modal/Modal.vue'
import Input from '@/components/ui/input/Input.vue'
import Button from '@/components/ui/button/Button.vue'
import PermissionMultiSelect from './PermissionMultiSelect.vue'

import type { Role, Permission } from '@/types'
import { useRoles } from '@/composables/useRoles'

const props = defineProps<{
  role?: Role
}>()

const emit = defineEmits<{ (e: 'close'): void; (e: 'saved'): void }>()

const form = ref({ name: '', guard: '', permissionIds: [] as number[] })
const availablePermissions = ref<Permission[]>([])

const {
  fetchPermissions,
  createRole,
  updateRole,
  syncRolePermissions
} = useRoles()

onMounted(async () => {
  availablePermissions.value = await fetchPermissions()

  if (props.role) {
    form.value.name = props.role.name
    form.value.guard = props.role.guard_name
    form.value.permissionIds = props.role.permissions.map(p => p.id)
  }
})

watch(() => props.role, (role) => {
  if (role) {
    form.value.name = role.name
    form.value.guard = role.guard_name
    form.value.permissionIds = role.permissions.map(p => p.id)
  } else {
    form.value = { name: '', guard: '', permissionIds: [] }
  }
})

const handleSubmit = async () => {
  const payload = { name: form.value.name, guard_name: form.value.guard }

  let roleId = props.role?.id

  if (roleId) {
    await updateRole(roleId, payload)
  } else {
    const created = await createRole(payload)
    roleId = created.id
  }

  await syncRolePermissions(roleId!, form.value.permissionIds)
  emit('saved')
}
</script>

<template>
  <Modal title="Role Details" @close="emit('close')">
    <form class="space-y-6" @submit.prevent="handleSubmit">
      <div>
        <label class="block text-sm font-medium mb-1" for="name">Role Name</label>
        <Input id="name" v-model="form.name" placeholder="e.g. Administrator" />
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="guard">Guard Name</label>
        <Input id="guard" v-model="form.guard" placeholder="e.g. web, api" />
      </div>

      <div>
        <label class="block text-sm font-medium mb-2">Permissions</label>
        <PermissionMultiSelect
          v-model="form.permissionIds"
          :options="availablePermissions"
        />
      </div>

      <div class="flex justify-end gap-2">
        <Button appearance="ghost" @click="emit('close')">Cancel</Button>
        <Button type="submit">{{ props.role ? 'Update' : 'Create' }}</Button>
      </div>
    </form>
  </Modal>
</template>
