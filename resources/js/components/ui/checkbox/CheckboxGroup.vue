<script setup lang="ts">
import CheckboxWithLabel from '@/components/ui/checkbox/CheckboxWithLabel.vue'
import type { Permission } from '@/types'
import { computed } from 'vue'

const props = defineProps<{
  options: { label: string; value: number }[]
  modelValue: number[]
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: number[]): void
}>()

const selected = computed(() => props.modelValue)

const toggle = (value: number, checked: boolean) => {
  const next = checked
    ? [...selected.value, value]
    : selected.value.filter(v => v !== value)

  emit('update:modelValue', next)
}

console.log('CheckboxGroup props:', props.options, 'selected:', selected.value)
</script>

<template>
  <div class="grid grid-cols-2 gap-2">
    <CheckboxWithLabel
      v-for="opt in options"
      :key="opt.value"
      :label="opt.label"
      :checked="selected.includes(opt.value)"
      @update:checked="(checked: boolean) => toggle(opt.value, checked)"
    />
  </div>
</template>