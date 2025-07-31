<script setup lang="ts">
import { computed } from 'vue'
import Checkbox from '@/components/ui/checkbox/Checkbox.vue' // Assumes reusable checkbox component

import type { Permission } from '@/types'

const props = defineProps<{
  modelValue: number[]
  options: Permission[]
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: number[]): void
}>()

const isSelected = (id: number) => props.modelValue.includes(id)

const toggle = (id: number) => {
  const updated = isSelected(id)
    ? props.modelValue.filter(pid => pid !== id)
    : [...props.modelValue, id]

  emit('update:modelValue', updated)
}
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div
      v-for="perm in props.options"
      :key="perm.id"
      class="flex items-start space-x-2"
    >
      <Checkbox
        :checked="isSelected(perm.id)"
        @change="toggle(perm.id)"
        :aria-label="`Toggle ${perm.name} permission`"
      />
      <div>
        <div class="font-medium text-sm">{{ perm.name }}</div>
        <div class="text-xs text-muted">{{ perm.name || 'No description available' }}</div>
      </div>
    </div>
  </div>
</template>