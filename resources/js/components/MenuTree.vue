<script setup lang="ts">
	import Card from '@/components/ui/card/Card.vue'
	import Button from '@/components/ui/button/Button.vue'
	import Separator from '@/components/ui/separator/Separator.vue'
	import Label from '@/components/ui/label/Label.vue'
	import ChevronIcon from '@/components/ui/icon/ChevronIcon.vue'
	import Collapsible from '@/components/ui/collapsible/Collapsible.vue'
	import CollapsibleTrigger from '@/components/ui/collapsible/CollapsibleTrigger.vue'
	import CollapsibleContent from '@/components/ui/collapsible/CollapsibleContent.vue'

	const props = defineProps<{ menus: any[] }>()
	const emit = defineEmits<{ (e: 'select', menu: any | null): void }>()
</script>

<template>
	<Card class="p-4 space-y-4">
		<div class="flex items-center justify-between">
			<Label class="text-lg font-semibold">Menu Tree</Label>
			<Button size="sm" variant="outline" @click="$emit('select', null)">
			+ Create New
			</Button>
		</div>

		<Separator />

		<ul class="space-y-1">
			<li v-for="menu in menus" :key="menu.id">
			<Collapsible v-slot="{ open }">
				<CollapsibleTrigger>
					<Button
					variant="ghost"
					size="sm"
					class="w-full justify-start px-2 py-1 text-left"
					@click="$emit('select', menu)"
					>
					<div class="flex items-center gap-2">
						<ChevronIcon :open="open" />
						<span class="truncate text-sm">{{ menu.label }}</span>
					</div>
					</Button>
				</CollapsibleTrigger>

				<CollapsibleContent>
					<ul v-if="menu.children?.length" class="ml-4 mt-1 space-y-1">
					<li
						v-for="child in menu.children"
						:key="child.id"
						@click="$emit('select', child)"
					>
						<Button
							variant="ghost"
							size="sm"
							class="w-full justify-start px-2 py-1 text-left"
						>
							<div class="flex items-center gap-2">
							<ChevronIcon :open="false" class="opacity-30" />
							<span class="truncate text-sm">{{ child.label }}</span>
							</div>
						</Button>
					</li>
					</ul>
				</CollapsibleContent>
			</Collapsible>
			</li>
		</ul>
	</Card>
</template>