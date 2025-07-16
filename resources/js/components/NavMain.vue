	<script setup lang="ts">
	import type { NavItem, NavGroup } from '@/types';
	import {
		SidebarGroup, SidebarGroupLabel, SidebarMenu,
		SidebarMenuItem, SidebarMenuButton,
	} from '@/components/ui/sidebar';
	import { Collapsible, CollapsibleTrigger, CollapsibleContent } from '@/components/ui/collapsible';
	import { Link, usePage } from '@inertiajs/vue3';
	import ChevronIcon from '@/components/ui/icon/ChevronIcon.vue';
	import SidebarLink from './ui/sidebar/SidebarLink.vue';
	import { ref, watch, onMounted } from 'vue';
	import { resolveIcon } from '@/utils/icons';
	import type { LucideIcon } from 'lucide-vue-next';
	import AsyncIcon from './ui/icon/AsyncIcon.vue';
	import { useLiveIcons } from '@/composables/useLiveIcons';

	const props = defineProps<{ items: NavItem[] }>();
	// console.log('props', props.items[0].iconName)
	// const icon = ref<LucideIcon>()
	// const Icons = await Promise.all(
	// 	props.items.map(item => resolveIcon(item.iconName))
	// )

	const isOpen = ref(false);

	function handleToggle() {
		isOpen.value = !isOpen.value;
	}

	// auto-expand if any child is active
	watch(
		() => props.items.some(item => item.children?.some(child => child.isActive)),
		(active) => {
			if (active) isOpen.value = true
		},
		{ immediate: true }
	)

	onMounted(() => {
		useLiveIcons(props.items)
	// 	for (const item of props.items) {
	// 		item.icon = await resolveIcon(item.iconName)
	// 	}
	});
	
	const page = usePage();
	</script>

	<template>
	<SidebarGroup class="px-2 py-0">
		<SidebarGroupLabel>Platform</SidebarGroupLabel>
		<SidebarMenu>
			<template v-for="item in items" :key="item.title">
			<SidebarMenuItem v-if="item.children?.length">
				<Collapsible v-slot="{ open }" class="group/collapsible">
					<CollapsibleTrigger as-child>
						<!-- <div class="flex items-center justify-between gap-2 px-2 py-2 cursor-pointer rounded hover:bg-sidebar-hover transition-colors"> -->
						<SidebarMenuButton  class="hover:cursor-pointer flex items-center gap-2 px-w py-2"
                        as-child :is-active="item.href === page.url"
                        :tooltip="item.title"
                    >
						<div class="flex items-center gap-2">
							<Suspense>
								<template #default>
									<div><AsyncIcon :name="item.iconName" /></div>
								</template>
								<template #fallback>
									<div><component :is="item.icon" class="w-4 h-4" /></div>
								</template>
							</Suspense>
							<span class="text-sm font-medium w-full">{{ item.title }}</span>
							<ChevronIcon :open="open" />
						</div>
						</SidebarMenuButton>
						<!-- </div> -->
					</CollapsibleTrigger>

					<CollapsibleContent>
						<div class="pl-6 pt-2 flex flex-col gap-1 overflow-hidden">
						<SidebarLink
							v-for="child in item.children"
							:key="child.title"
							:item="child"
						/>
						</div>
					</CollapsibleContent>
				</Collapsible>
			</SidebarMenuItem>

			<SidebarMenuItem v-else>
				<SidebarMenuButton as-child :is-active="item.href === page.url" :tooltip="item.title">
					<Link :href="item.href!">
					<Suspense>
						<template #default>
							<div><AsyncIcon :name="item.iconName" /></div>
						</template>
						<template #fallback>
							<div><component :is="item.icon" class="w-4 h-4" /></div>
						</template>
					</Suspense>
					<span class="text-sm font-medium">{{ item.title }}</span>
					</Link>
				</SidebarMenuButton>
			</SidebarMenuItem>
			</template>
		</SidebarMenu>
	</SidebarGroup>
	</template>


<style scoped>
	.slide-enter-active,
	.slide-leave-active {
		transition: max-height 0.3s ease;
	}
	.slide-enter-from,
	.slide-leave-to {
  		max-height: 0;
	}
	.slide-enter-to,
	.slide-leave-from {
  		max-height: 500px;
	}
</style>
