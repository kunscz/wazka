<script setup lang="ts">
import { reactive, watch, toRefs } from 'vue'
import { useMenus } from '@/composables/useMenus'
import type { MenuNode } from '@/types/MenuNode'

const props = defineProps<{
  menu: MenuNode | null
}>()

const emit = defineEmits<{
  (e: 'saved'): void
}>()

const { createMenu, updateMenu } = useMenus()

const form = reactive({
  label: '',
  route_name: '',
  url: '',
  icon: '',
  parent_id: null as number | null,
  sort_order: 99,
  is_active: true,
  is_manual: true,
})

watch(
  () => props.menu,
  (menu) => {
    if (menu) {
      form.label = menu.label
      form.route_name = menu.route_name ?? ''
      form.url = menu.url ?? ''
      form.icon = menu.icon ?? ''
      form.parent_id = menu.parent_id ?? null
      form.sort_order = menu.sort_order ?? 99
      form.is_active = menu.is_active
    } else {
      Object.assign(form, {
        label: '',
        route_name: '',
        url: '',
        icon: '',
        parent_id: null,
        sort_order: 99,
        is_active: true,
        is_manual: true,
      })
    }
  },
  { immediate: true }
)

const handleSubmit = async () => {
  if (props.menu?.id) {
    await updateMenu(props.menu.id, form)
  } else {
    await createMenu(form)
  }
  emit('saved')
}
</script>

<template>
  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-lg font-semibold mb-4">
      {{ props.menu ? 'Edit Menu' : 'Create Menu' }}
    </h2>

    <form @submit.prevent="handleSubmit" class="space-y-4">
      <div>
        <label class="block text-sm font-medium">Label</label>
        <input v-model="form.label" type="text" class="input" required />
      </div>

      <div>
        <label class="block text-sm font-medium">Route Name</label>
        <input v-model="form.route_name" type="text" class="input" />
      </div>

      <div>
        <label class="block text-sm font-medium">External URL</label>
        <input v-model="form.url" type="text" class="input" />
      </div>

      <div>
        <label class="block text-sm font-medium">Icon</label>
        <input v-model="form.icon" type="text" class="input" />
      </div>

      <div>
        <label class="block text-sm font-medium">Sort Order</label>
        <input v-model.number="form.sort_order" type="number" class="input" />
      </div>

      <div class="flex items-center gap-2">
        <input v-model="form.is_active" type="checkbox" />
        <label class="text-sm">Active</label>
      </div>

      <button type="submit" class="btn btn-primary">
        {{ props.menu ? 'Update' : 'Create' }}
      </button>
    </form>
  </div>
</template>

<style scoped>
.input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}
.btn-primary {
  background-color: #2563eb;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 4px;
}
</style>