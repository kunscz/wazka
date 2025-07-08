<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'
import type { Role, AppPageProps } from '@/types'

const { props } = usePage<AppPageProps<{ roles: Role[] }>>()
</script>

<template>
  <div>
    <h1 class="text-xl font-bold mb-4">Roles</h1>
    <ul>
      <li v-for="role in props.roles" :key="role.id">
        {{ role.name }} — Permissions: {{ role.permissions.map(p => p.name).join(', ') }}
      </li>
    </ul>
  </div>
</template>