// src/composables/useRoles.ts
import axios from 'axios'
import type { Role, RolePayload, Permission } from '@/types'

export function useRoles() {
  const fetchRoles = async (): Promise<Role[]> => {
    const { data } = await axios.get('/api/core/roles')
    return data
  }

  const fetchPermissions = async (): Promise<Permission[]> => {
    const { data } = await axios.get('/api/core/permissions')
    return data
  }

  const createRole = async (payload: RolePayload & { guard_name: string }) => {
    console.log('Creating role with payload:', payload);
    const { data } = await axios.post('/api/core/roles', payload)
    return data
  }

  const updateRole = async (
    id: number,
    payload: RolePayload & { guard_name: string }
  ) => {
    await axios.put(`/api/core/roles/${id}`, payload)
  }

  const syncRolePermissions = async (id: number, permissionIds: number[]) => {
    await axios.post(`/api/core/roles/${id}/sync-permissions`, {
      permissions: permissionIds
    })
    // await axios.post(`/api/core/roles/${id}/permissions`, {
    //   permissions: permissionIds
    // })
  }

  const deleteRole = async (id: number) => {
    await axios.delete(`/api/core/roles/${id}`)
  }

  return {
    fetchRoles,
    fetchPermissions,
    createRole,
    updateRole,
    syncRolePermissions,
    deleteRole
  }
}