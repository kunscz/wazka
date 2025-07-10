<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
  modelValue: string
  options: string[]
  placeholder?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const input = ref(props.modelValue)
const filtered = ref<string[]>([])

watch(() => input.value, (val) => {
  filtered.value = props.options.filter(opt =>
    opt.toLowerCase().includes(val.toLowerCase())
  )
  emit('update:modelValue', val)
}, { immediate: true })

const select = (val: string) => {
  input.value = val
  filtered.value = []
  emit('update:modelValue', val)
}
</script>

<template>
  <div class="relative">
    <input
      v-model="input"
      :placeholder="placeholder"
      class="input"
    />
    <ul v-if="filtered.length" class="absolute z-10 bg-white border mt-1 w-full max-h-48 overflow-y-auto">
      <li
        v-for="opt in filtered"
        :key="opt"
        @click="select(opt)"
        class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm"
      >
        {{ opt }}
      </li>
    </ul>
  </div>
</template>

<style scoped>
.input {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #d1d5db;
  border-radius: 4px;
}
</style>