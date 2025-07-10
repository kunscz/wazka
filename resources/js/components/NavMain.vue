	<script setup lang="ts">
	import type { NavItem } from '@/types';
	import {
	SidebarGroup,
	SidebarGroupLabel,
	SidebarMenu,
	SidebarMenuItem,
	SidebarMenuButton,
	} from '@/components/ui/sidebar';
	import { Link, usePage } from '@inertiajs/vue3';
	import { ref } from 'vue';

	defineProps<{ items: NavItem[] }>();
	const page = usePage();
	</script>

	<template>
	<SidebarGroup class="px-2 py-0">
		<SidebarGroupLabel>Platform</SidebarGroupLabel>
		<SidebarMenu>
			<template v-for="item in items" :key="item.title">
			<SidebarMenuItem v-if="item.children?.length">
				<details>
					<summary class="flex items-center gap-2 cursor-pointer px-4 py-2">
					<component :is="item.icon" />
					<span>{{ item.title }}</span>
					</summary>
					<div class="pl-6">
					<SidebarMenu>
						<SidebarMenuItem v-for="child in item.children" :key="child.title">
							<SidebarMenuButton as-child :is-active="child.href === page.url" :tooltip="child.title">
							<Link :href="child.href!">
								<component :is="child.icon" />
								<span>{{ child.title }}</span>
							</Link>
							</SidebarMenuButton>
						</SidebarMenuItem>
					</SidebarMenu>
					</div>
				</details>
			</SidebarMenuItem>

			<SidebarMenuItem v-else>
				<SidebarMenuButton as-child :is-active="item.href === page.url" :tooltip="item.title">
					<Link :href="item.href!">
					<component :is="item.icon" />
					<span>{{ item.title }}</span>
					</Link>
				</SidebarMenuButton>
			</SidebarMenuItem>
			</template>
		</SidebarMenu>
	</SidebarGroup>
	</template>