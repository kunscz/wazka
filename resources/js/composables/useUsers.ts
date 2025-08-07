import { useApi } from '@/composables/useApi'
import type { Role, User, Permission } from '@/types'

const endpoint = '/core/users'
export interface UserResponse {
   users: User[]
   roles: Role[]
   permissions: Permission[]
}

export function useUsers() {
  const api = useApi()

  const fetchUsers = async (): Promise<UserResponse> => {
    const { data } = await api.get(endpoint)
    console.log('Fetched users:', data);
    return data
  }

  const createUser = async (payload: {
    name: string
    email: string
    roleIds: number[]
    permissionIds?: number[]
  }): Promise<User> => {
    const { data } = await api.post(endpoint, payload)
    return data
  }

  const updateUser = async (
    id: number,
    payload: {
      name?: string
      email?: string
      password?: string
      password_confirmation?: string
      permissionIds?: number[]
      roleIds?: number[]
    }
  ): Promise<User> => {
    const { data } = await api.put(`${endpoint}/${id}`, payload)
    return data.user
  }

  const deleteUser = async (id: number): Promise<void> => {
    await api.delete(`${endpoint}/${id}`)
  }

  return {
    fetchUsers,
    createUser,
    updateUser,
    deleteUser,
  }
}