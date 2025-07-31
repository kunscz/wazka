<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import Dialog from '@/components/ui/dialog/Dialog.vue' // Optional: if using existing dialog primitive
import Button from '@/components/ui/button/Button.vue'

const props = defineProps<{
  title?: string
}>()

const emit = defineEmits<{
  (e: 'close'): void
}>()

const modalRef = ref<HTMLElement | null>(null)

const trapFocus = () => {
  const focusables = modalRef.value?.querySelectorAll<HTMLElement>(
    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
  )
  focusables?.[0]?.focus()
}

onMounted(() => {
  trapFocus()
  document.body.style.overflow = 'hidden'
})

onUnmounted(() => {
  document.body.style.overflow = ''
})
</script>

<template>
  <div
    ref="modalRef"
    role="dialog"
    aria-modal="true"
    aria-label="Modal Dialog"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
    @keydown.esc="emit('close')"
  >
    <div
      class="bg-white dark:bg-neutral-900 rounded-lg shadow-lg w-full max-w-2xl overflow-hidden"
    >
      <header class="px-6 py-4 border-b border-muted flex items-center justify-between">
        <h2 class="text-lg font-semibold">{{ title || 'Modal' }}</h2>
        <Button icon appearance="ghost" @click="emit('close')" aria-label="Close Modal">
          ✕
        </Button>
      </header>

      <section class="px-6 py-4">
        <slot />
      </section>
    </div>
  </div>
</template>