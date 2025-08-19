import type { Menu } from '@/types'

export function buildTree(flatMenus: Menu[]): Menu[] {
	const menuMap = new Map<number, Menu>()
	const tree: Menu[] = []

	// Clone and index all menus
	flatMenus.forEach(menu => {
		menuMap.set(menu.id, { ...menu, children: [] })
	})

	// Link children to parents
	menuMap.forEach(menu => {
      const parentId = menu.parent_id
      if (typeof parentId === 'number' && menuMap.has(parentId)) {
			const parent = menuMap.get(parentId)!
			parent.children!.push(menuMap.get(menu.id)!)
		} else {
			tree.push(menuMap.get(menu.id)!)
		}
	})

	// Sort children recursively
	const sortTree = (nodes: Menu[]) => {
		nodes.sort((a, b) => (a.sort_order ?? 99) - (b.sort_order ?? 99))
		nodes.forEach(node => {
			if (node.children?.length) {
				sortTree(node.children)
			}
		})
	}

	sortTree(tree)

	return tree
}