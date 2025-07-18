<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import Input from '@/components/ui/input/Input.vue'
import Card from '@/components/ui/card/Card.vue'

const props = defineProps<{
  modelValue: string | number | null
  options: string[]
  placeholder?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | number | null): void
}>()

const isFocused = ref(false)
const inputValue = ref(String(props.modelValue ?? ''))

watch(() => props.modelValue, (val) => {
  inputValue.value = String(val ?? '')
})

watch(inputValue, (val) => {
  emit('update:modelValue', val)
})

const filteredOptions = computed(() => {
  const input = inputValue.value.trim().toLowerCase()
  if (!isFocused.value) return []
  const filtered = props.options.filter(option =>
    option.toLowerCase().includes(input)
  )
  return [...new Set(filtered)]
})

const selectOption = (option: string) => {
  inputValue.value = option
  isFocused.value = false
}

function handleBlur() {
  setTimeout(() => {
    isFocused.value = false
  }, 100)
}
</script>

<template>
  <div class="relative">
    <Input
      v-model="inputValue"
      :placeholder="placeholder"
      @focus="isFocused = true"
      @blur="handleBlur"
    />

    <Card
      v-if="isFocused && filteredOptions.length"
      class="absolute z-10 mt-1 w-full max-h-60 overflow-auto border rounded shadow bg-popover"
    >
      <ul class="divide-y divide-muted">
        <li
          v-for="option in filteredOptions"
          :key="option"
          @mousedown.prevent="selectOption(option)"
          class="px-3 py-2 text-sm cursor-pointer hover:bg-muted transition"
        >
          {{ option }}
        </li>
      </ul>
    </Card>
  </div>
</template>
