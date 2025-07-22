export interface MenuNode {
  id: number
  label: string
  icon?: string
  route_name?: string | null
  url?: string | null
  is_active: boolean
  is_manual: boolean
  sort_order?: number
  parent_id?: number | null
  parent?: {label: string, id: number | null } | null
  permissions?: string[]
  children?: MenuNode[]
}