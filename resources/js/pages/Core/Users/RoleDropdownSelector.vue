<script setup lang="ts">
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuCheckboxItem,
  DropdownMenuRadioGroup,
  DropdownMenuRadioItem,
  DropdownMenuSeparator,
  DropdownMenuLabel,
} from '@/components/ui/dropdown-menu';

import { Button } from '@/components/ui/button';
import { computed } from 'vue'

const props = defineProps<{
  roles: { value: number; label: string }[]
  modelValue: number[] | number
  multiple?: boolean
  disabled?: boolean
  placeholder?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: number[] | number): void
}>()

const isSelected = (id: number) => {
  return Array.isArray(props.modelValue)
    ? props.modelValue.includes(id)
    : props.modelValue === id
}

const toggleRole = (id: number, checked: boolean) => {
  if (!Array.isArray(props.modelValue)) return
  const updated = checked
    ? [...props.modelValue, id]
    : props.modelValue.filter(rid => rid !== id)
  emit('update:modelValue', updated)
}
console.log('RoleDropdownSelector props:', props)
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button :disabled="props.disabled" variant="outline">
        {{ props.placeholder || 'Select Role(s)' }}
      </Button>
    </DropdownMenuTrigger>

    <DropdownMenuContent class="min-w-[12rem]">
      <template v-if="props.multiple">
         <DropdownMenuLabel>Roles</DropdownMenuLabel>
         <DropdownMenuSeparator />
        <DropdownMenuCheckboxItem
          v-for="role in props.roles"
          :key="role.value"
          :checked="isSelected(role.value)"
          @update:checked="(checked: boolean) => toggleRole(role.value, checked)"
        >
          {{ role.label+'t' }}
        </DropdownMenuCheckboxItem>
      </template>

      <template v-else>
        <DropdownMenuRadioGroup
          :model-value="props.modelValue"
          @update:modelValue="val => emit('update:modelValue', val)"
        >
          <DropdownMenuRadioItem
            v-for="role in props.roles"
            :key="role.value"
            :value="role.value"
          >
            {{ role.label }}
          </DropdownMenuRadioItem>
        </DropdownMenuRadioGroup>
      </template>
    </DropdownMenuContent>
  </DropdownMenu>
</template>