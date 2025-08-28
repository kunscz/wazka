<script setup lang="ts">
import CheckboxWithLabel from '@/components/ui/checkbox/CheckboxWithLabel.vue'
import type { Permission } from '@/types'
import { computed, watch } from 'vue'

const props = defineProps<{
  options: { label: string; value: number }[]
  modelValue: number[]
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: number[]): void
}>()

const selected = computed(() => props.modelValue)

const toggle = (value: number, checked: boolean | 'indeterminate') => {
  if (checked === 'indeterminate') return
  const next = checked
    ? [...selected.value, value]
    : selected.value.filter(v => v !== value)

  emit('update:modelValue', next)
  // console.log('Emitting update:modelValue with:', next)
}

console.log('CheckboxGroup props:', props.options, 'selected:', selected.value)
console.log('modelValue:', props.modelValue)
watch(() => props.modelValue, (newVal) => {
  console.log('Updated modelValue:', newVal)
})

</script>

<template>
  <div class="grid grid-cols-2 gap-2">
    <CheckboxWithLabel
      v-for="opt in options"
      :key="opt.value"
      :label="opt.label"
      :model-value="props.modelValue.includes(opt.value)"
      @update:model-value="val => toggle(Number(opt.value), val)"
    />
  </div>
</template>