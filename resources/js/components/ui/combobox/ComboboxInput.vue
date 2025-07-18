<script setup lang="ts">
import {
  ComboboxRoot,
  ComboboxInput,
  ComboboxContent,
  ComboboxItem,
  ComboboxItemIndicator,
  ComboboxEmpty,
} from 'reka-ui'
import { ref, computed, watch } from 'vue'

const props = defineProps<{
  modelValue: string | number | null
  options: Array<string | number | { label: string; value: string | number }>
  placeholder?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number | null): void
}>()

const query = ref('')
const isOpen = ref(false)

const normalizedOptions = computed(() =>
  props.options.map(opt =>
    typeof opt === 'object'
      ? opt
      : { label: opt.toString(), value: opt }
  )
)

const filteredOptions = computed(() => {
  const q = query.value.toLowerCase()
  return normalizedOptions.value.filter(opt =>
    opt.label.toLowerCase().includes(q)
  )
})

const handleSelect = (value: string | number) => {
  emit('update:modelValue', value)
  query.value = normalizedOptions.value.find(opt => opt.value === value)?.label ?? ''
  isOpen.value = false
}

watch(() => props.modelValue, (val) => {
  const match = normalizedOptions.value.find(opt => opt.value === val)
  query.value = match?.label ?? ''
})

function handleBlur() {
  setTimeout(() => {
    isOpen.value = false
  }, 100)
}
</script>

<template>
  <ComboboxRoot :value="modelValue" @update:value="handleSelect" class="relative w-full">
    <ComboboxInput
      v-model="query"
      :placeholder="placeholder"
      @focus="isOpen = true"
      @blur="handleBlur"
      class="w-full"
    />

    <ComboboxContent
      v-if="isOpen"
      class="absolute z-10 mt-1 w-full max-h-60 overflow-auto rounded border bg-popover shadow"
    >
      <template v-if="filteredOptions.length">
        <ComboboxItem
          v-for="opt in filteredOptions"
          :key="opt.value"
          :value="opt.value"
          class="px-3 py-2 text-sm cursor-pointer hover:bg-muted flex justify-between items-center"
        >
          {{ opt.label }}
          <ComboboxItemIndicator class="text-muted">✓</ComboboxItemIndicator>
        </ComboboxItem>
      </template>

      <ComboboxEmpty class="px-3 py-2 text-sm text-muted">
        No matches found
      </ComboboxEmpty>
    </ComboboxContent>
  </ComboboxRoot>
</template>