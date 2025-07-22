<script setup lang="ts">
   import { computed } from 'vue';
   import {
      ComboboxRoot, ComboboxAnchor, ComboboxInput,
      ComboboxTrigger, ComboboxContent, ComboboxViewport,
      ComboboxItem, ComboboxItemIndicator, ComboboxEmpty,
   } from 'reka-ui'
   import ChevronIcon from '@/components/ui/icon/ChevronIcon.vue'

   const props = defineProps<{
      modelValue: { label: string; id: string | number | null } | null
      options: Array<{ label: string; id: string | number | null }>
      placeholder?: string
   }>()

   const value = computed({
      get: () => props.modelValue,
      set: val => emit('update:modelValue', val)
   })

   const emit = defineEmits<{
      (e: 'update:modelValue', parent: { label: string; id: string | number | null } | null): void
   }>()
</script>

<template>
   <ComboboxRoot
      v-model="value"
      @update:modelValue="emit('update:modelValue', $event)"
      class="relative w-full"
   >
      <ComboboxAnchor
         class="inline-flex items-center justify-between rounded border px-3 py-2 h-[38px] w-full bg-card hover:bg-muted shadow-sm text-sm"
      >
         <ComboboxInput :display-value="(v) => v.label"
         placeholder="Select option"
         class="w-full bg-transparent outline-none selection:bg-muted"
         />
         <ComboboxTrigger>
         <ChevronIcon :open="false" />
         </ComboboxTrigger>
      </ComboboxAnchor>

      <ComboboxContent
         class="absolute z-10 w-full mt-1 max-h-60 overflow-hidden rounded-lg shadow-sm border bg-popover"
      >
         <ComboboxViewport class="p-1">
         <template v-if="options.length">
            <ComboboxItem
               v-for="opt in options"
               :key="opt.id ?? '__null'"
               :value="opt"
               class="text-sm px-3 py-2 rounded cursor-pointer flex items-center justify-between hover:bg-muted"
            >
               {{ opt.label }}
               <ComboboxItemIndicator v-if="opt.id === modelValue" class="text-muted">
               ✓
               </ComboboxItemIndicator>
            </ComboboxItem>
         </template>

         <ComboboxEmpty class="text-sm text-muted px-3 py-2 text-center">
            No matches found
         </ComboboxEmpty>
         </ComboboxViewport>
      </ComboboxContent>
   </ComboboxRoot>
</template>