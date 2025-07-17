<script setup lang="ts">
import Card from '@/components/ui/card/Card.vue'
import Button from '@/components/ui/button/Button.vue'
import Separator from '@/components/ui/separator/Separator.vue'
import Label from '@/components/ui/label/Label.vue'
import ChevronIcon from '@/components/ui/icon/ChevronIcon.vue'

const props = defineProps<{ menus: any[] }>()
const emit = defineEmits<{ (e: 'select', menu: any): void }>()

function renderNode(menu: any, depth: number = 0) {
  return (
    `<li key={menu.id} class="pl-[calc(1rem_*_depth)]">
      <Button
        variant="ghost"
        size="sm"
        class="w-full justify-start px-2 py-1 text-left"
        @click={() => emit('select', menu)}
      >
        <div class="flex items-center gap-2">
          <ChevronIcon :open="false" class="opacity-30" />
          <span class="truncate text-sm">{{ menu.label }}</span>
        </div>
      </Button>

      <ul v-if="menu.children?.length" class="mt-1">
        {menu.children.map(child => renderNode(child, depth + 1))}
      </ul>
    </li>`
  )
}
</script>

<template>
  <Card class="p-4 space-y-4">
    <Label class="text-lg font-semibold">Menu Tree</Label>
    <Separator />

    <ul class="space-y-1">
      <li v-for="menu in menus" :key="menu.id">
        <Button
          variant="ghost"
          size="sm"
          class="w-full justify-start px-2 py-1 text-left"
          @click="$emit('select', menu)"
        >
          <div class="flex items-center gap-2">
            <ChevronIcon :open="false" class="opacity-30" />
            <span class="truncate text-sm">{{ menu.label }}</span>
          </div>
        </Button>

        <ul v-if="menu.children?.length" class="ml-4 mt-1 space-y-1">
          <li
            v-for="child in menu.children"
            :key="child.id"
            @click="$emit('select', child)"
          >
            <Button
              variant="ghost"
              size="sm"
              class="w-full justify-start px-2 py-1 text-left"
            >
              <div class="flex items-center gap-2">
                <ChevronIcon :open="false" class="opacity-30" />
                <span class="truncate text-sm">{{ child.label }}</span>
              </div>
            </Button>
          </li>
        </ul>
      </li>
    </ul>
  </Card>
</template>