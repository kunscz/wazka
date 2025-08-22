<script setup lang="ts">
import { ref, computed, watch, reactive, markRaw, onMounted } from 'vue'
import type { LucideIcon } from 'lucide-vue-next'
import { resolveIcon } from '@/utils/icons'
import { Settings } from 'lucide-vue-next'

const props = defineProps<{
   modelValue: string
   size?: number
   tooltip?: string
}>()

const emit = defineEmits<{
   (e: 'update:modelValue', value: string): void
}>()

const iconList = ref<string[]>(
   [
      // 🧩 Core Navigation & Structure
      'LayoutDashboard',
      'Menu',
      'ListTree',
      'ChevronRight',
      'ChevronDown',

      // 🔐 Access Control & Permissions
      'ShieldCheck',
      'Lock',
      'Unlock',
      'Key',
      'UserCog',

      // ⚙️ Settings & Tools
      'Settings',
      'SlidersHorizontal',
      'Tool',
      'Bug',

      // 📁 Content & Structure
      'Folder',
      'FolderCog',
      'FileText',
      'ClipboardList',

      // 👤 User & Role Management
      'User',
      'Users',
      'UserPlus',
      'UserMinus',
      'UserCheck',

      // 🧠 UX Feedback & Clarity
      'Info',
      'AlertCircle',
      'CheckCircle',
      'Eye',
      'EyeOff',

      // 🎨 Visual Enhancers
      'Sparkles',
      'Stars',
      'Activity'
   ])
const search = ref(props.modelValue || '')
const selected = ref(props.modelValue)
// const resolvedIcons = ref<Record<string, LucideIcon | null>>({})
const resolvedIcons = reactive<Record<string, LucideIcon | null>>({}) // use shallowRef for better performance  
const loadingIcons = ref<Record<string, boolean>>({})

const filteredIcons = computed(() =>
   iconList.value.filter(name =>
      name.toLowerCase().includes(search.value.toLowerCase())
   )
)

const visibleIcons = computed(() => filteredIcons.value.slice(0, 100)
) // limit for performance

const selectIcon = async (name: string) => {
   selected.value = name
   emit('update:modelValue', name)

   if (!resolvedIcons[name]) {
       loadingIcons.value[name] = true
       const icon = await resolveIcon(name)
       resolvedIcons[name] = markRaw(icon)
       loadingIcons.value[name] = false
   }
}

watch(visibleIcons, async (list) => {
   for (const name of list) {
      if (!resolvedIcons[name]) {
      loadingIcons.value[name] = true
      resolveIcon(name).then((icon) => {
         resolvedIcons[name] = icon
         loadingIcons.value[name] = false
      })
      }
   }
}, {immediate: true})

watch(() => props.modelValue, val => search.value = val)

// const isLoading = computed(() =>
//   visibleIcons.value.some(name => !resolvedIcons.value[name])
// )

// resolvedIcons.value['Settings'] = markRaw(Settings)

const sizeClass = computed(() => {
   const px = props.size ?? 5
   return `w-${px} h-${px}`
})

// await new Promise(resolve => setTimeout(resolve, 100)) // 100ms delay
// const getResolvedIcon = (name: string) => computed(() => resolvedIcons.value[name])

onMounted(() => {
   visibleIcons.value.forEach(async (name) => {
      if (!resolvedIcons[name]) {
         const icon = await resolveIcon(name)
         resolvedIcons[name] = markRaw(icon)
      }
   })
})
</script>

<template>
  <div class="space-y-2" :title="tooltip">
    <label class="text-sm font-medium">Choose Icon</label>
    <input
      type="text"
      v-model="search"
      placeholder="Search icons..."
      class="w-full border rounded px-2 py-1 text-sm"
    />

    <div class="grid grid-cols-6 gap-3 max-h-64 overflow-auto border rounded p-3 bg-popover">
      <div
         v-for="name in visibleIcons"
         :key="name"
         class="flex items-center justify-center cursor-pointer rounded"
         :class="{ 'ring ring-blue-500': name === selected }"
         @click="selectIcon(name)"
         :title="name"
         >
         <component
            v-if="resolvedIcons[name]"
            :is="resolvedIcons[name]"
            :class="`${sizeClass} text-gray-500 hover:text-blue-600`"
         />
         <div v-else
            class="animate-spin border-2 border-gray-300 border-t-transparent rounded-full"
            :class="sizeClass"
         ></div>
         <!-- <component :is="resolvedIcons['Settings']" /> -->
      </div>
    </div>
  </div>
</template>