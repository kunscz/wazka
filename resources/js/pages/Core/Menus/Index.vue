<script setup lang="ts">
import type { Menu } from '@/types/menu'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage<{ menus: Menu[] }>()
</script>

<template>
  <ul>
    <li v-for="menu in props.menus" :key="menu.id">
      <strong>{{ menu.label }}</strong> <small>(Requires: {{ menu.permissions.map(p => p.name).join(', ') }})</small>
      <ul v-if="menu.children.length">
        <li v-for="child in menu.children" :key="child.id">{{ child.label }}</li>
      </ul>
    </li>
  </ul>
</template>