<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import { resolveIcon } from '@/utils/icons';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import type { NavItem } from '@/types';
import type { LucideIcon } from 'lucide-vue-next';

const props = defineProps<{ item: NavItem }>()
const icon = ref<LucideIcon>()

onMounted(async () => {
	icon.value = await resolveIcon(props.item.iconName)
});

watch(
	() => props.item.iconName,
	async (newName) => {
		icon.value = await resolveIcon(newName)
	},
	{ immediate: true}
)

const page = usePage()
</script>

<template>
	<SidebarMenu>
		<SidebarMenuItem>
			<SidebarMenuButton
			 as-child
			 :is-active="props.item.isActive"
			 :tooltip="props.item.title"
			>
				<Link :href="props.item.href!" class="flex items-center gap-2">
					<component v-if="icon" :is="icon" class="w-4 h-4 self-center" />
					<span class="text-sm font-medium leading-tight">{{ props.item.title }}</span>
				</Link>
			</SidebarMenuButton>
		</SidebarMenuItem>
	</SidebarMenu>
</template>