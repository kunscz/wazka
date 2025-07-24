export function usePermissions() {
	const fetchPermissions = async (): Promise<
		{ id: number; name: string; label: string }[]
	> => {
		const { data } = await window.axios.get('/api/core/permissions')
		return data
	}

	const attachPermissionToMenu = async (menuId: number, permissionName: string) => {
		return await window.axios.post(`/api/core/menus/${menuId}/attach-permission`, {
			permission: permissionName,
		})
	}

	return {
		fetchPermissions,
		attachPermissionToMenu,
	}
}