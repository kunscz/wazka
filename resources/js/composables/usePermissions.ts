export function usePermissions() {
  const fetchPermissions = async (): Promise<string[]> => {
    const { data } = await window.axios.get('/api/permissions')
    return data
  }

  const attachPermissionToMenu = async (menuId: number, permissionName: string) => {
    return await window.axios.post(`/api/menus/${menuId}/attach-permission`, {
      permission: permissionName,
    })
  }

  return {
    fetchPermissions,
    attachPermissionToMenu,
  }
}