<script setup lang="ts">
import { ref, watch, computed, onMounted } from 'vue'
import { resolveIcon } from '@/utils/icons'
import type { LucideIcon } from 'lucide-vue-next'

const props = defineProps<{
  modelValue: string
  placeholder?: string
  tooltip?: string
}>()

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void
}>()

const input = ref(props.modelValue)
const icon = ref<LucideIcon | null>(null)
const iconList = ref<string[]>([])

watch(() => props.modelValue, val => input.value = val)
watch(input, async (val) => {
  emit('update:modelValue', val)
  icon.value = await resolveIcon(val)
})

onMounted(async () => {
  const mod = await import('lucide-vue-next')
  iconList.value = Object.keys(mod).sort()
  icon.value = await resolveIcon(input.value)
})

const filteredIcons = computed(() =>
  iconList.value.filter(name =>
    name.toLowerCase().includes(input.value.toLowerCase())
  )
)
</script>

<template>
  <div class="space-y-2" :title="tooltip">
    <label class="text-sm font-medium">Icon</label>
    <div class="flex items-center gap-2">
      <input
        type="text"
        v-model="input"
        :placeholder="placeholder ?? 'Lucide icon name'"
        class="w-full border rounded px-2 py-1 text-sm"
      />
      <component :is="icon" class="w-5 h-5 text-gray-500" />
    </div>

    <div v-if="filteredIcons.length" class="border rounded bg-popover p-2 max-h-40 overflow-auto text-sm">
      <div
        v-for="name in filteredIcons"
        :key="name"
        @mousedown.prevent="input = name"
        class="cursor-pointer px-2 py-1 hover:bg-muted rounded"
      >
        {{ name }}
      </div>
    </div>
  </div>
</template>