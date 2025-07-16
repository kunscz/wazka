import type { LucideIcon } from 'lucide-vue-next'

export interface NavBase {
  title: string
  href: string | null
  iconName: string
  icon?: LucideIcon
  isActive?: boolean
}

export function isGroup(item: NavItem): item is NavGroup {
  return 'children' in item && Array.isArray(item.children)
}


export interface NavGroup extends NavBase {
  children: NavItem[]
}

export interface NavLink extends NavBase {
  children?: undefined // Enforce absence
}

export type NavItem = NavGroup | NavLink