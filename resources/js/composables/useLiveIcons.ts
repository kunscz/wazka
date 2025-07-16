import { watchEffect, isRef } from 'vue'
import { resolveIcon } from '@/utils/icons'
import type { NavItem } from '@/types'

export function useLiveIcons(items: NavItem[]) {
  items.forEach(item => {
    // Watch for reactive iconName changes
    watchEffect(async () => {
      const name = isRef(item.iconName) ? item.iconName.value : item.iconName
      item.icon = await resolveIcon(name)
    })

    // Also hydrate children if they're a NavGroup
    if ('children' in item && Array.isArray(item.children)) {
      useLiveIcons(item.children)
    }
  })
}