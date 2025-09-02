<script setup lang="ts">
	import Card from '@/components/ui/card/Card.vue'
	import Button from '@/components/ui/button/Button.vue'
	import Separator from '@/components/ui/separator/Separator.vue'
	import Label from '@/components/ui/label/Label.vue'
	import ChevronIcon from '@/components/ui/icon/ChevronIcon.vue'
	import Collapsible from '@/components/ui/collapsible/Collapsible.vue'
	import CollapsibleTrigger from '@/components/ui/collapsible/CollapsibleTrigger.vue'
	import CollapsibleContent from '@/components/ui/collapsible/CollapsibleContent.vue'
	import { MenuNode } from '@/types/MenuNode'
	import ToggleSwitch from './ui/toggle/ToggleSwitch.vue'
	import ToggleIcon from './ui/icon/ToggleIcon.vue'
	import { computed } from 'vue'

	const props = defineProps<{
		menus: MenuNode[]
		showInactive: boolean
	}>()

	const tooltipText = computed(() => {
		return props.showInactive ? 'Hide inactive menus' : 'Show inactive menus'
	})

	const handleToggle = (event: Event) => {
		const target = event.target as HTMLInputElement
		emit('toggle-inactive', target.checked)
	}

	const emit = defineEmits<{
		(e: 'select', menu: any | null): void
		(e: 'toggle-inactive', value: boolean): void
	}>()
</script>

<template>
	<Card class="p-4 space-y-4">
		<div class="flex items-center justify-between">
			<Label class="text-lg font-semibold">Menu Tree</Label>
			<div class="flex items-center gap-4">
				<div class="flex items-center gap-2">
					<!-- <Label>Show Inactive</Label> -->
					<!-- <input
						type="checkbox"
						id="showInactive"
						class="form-checkbox"
						:checked="showInactive"
						@change="handleToggle"
					/> -->
					<!--
						:model-value="props.showInactive"
						@update:modelValue="emit('toggle-inactive', $event)"
						@change="$emit('toggle-inactive', $event.target.checked)"
					-->
					<!-- <ToggleSwitch
						:model-value="props.showInactive"
						label="Show Inactive"
						id="showInactive"
						@update:modelValue="emit('toggle-inactive', $event)"
					/> -->
					<ToggleIcon
						:model-value="props.showInactive"
						iconOn="Eye"
						iconOff="EyeOff"
						:size="4"
						color="currentColor"
						class="cursor-pointer"
						:tooltip="tooltipText"
						@update:modelValue="emit('toggle-inactive', $event)"
					/>
				</div>
				<Button size="sm" variant="outline" @click="$emit('select', null)" class="cursor-pointer">
					+ Create New
				</Button>
			</div>
		</div>

		<Separator />

		<ul class="space-y-1">
			<li v-for="menu in menus" :key="menu.id">
			<Collapsible v-slot="{ open }">
				<CollapsibleTrigger>
					<Button
					variant="ghost"
					size="sm"
					class="w-full justify-start px-2 py-1 text-left cursor-pointer"
					@click="$emit('select', menu)"
					>
					<div class="flex items-center gap-2">
						<ChevronIcon :open="open" />
						<span
							class="truncate text-sm"
							:class="{'opacity-50 italic': !menu.is_active}"
						>
							{{ menu.label }}
						</span>
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
							class="w-full justify-start px-2 py-1 text-left cursor-pointer"
						>
							<div class="flex items-center gap-2">
								<ChevronIcon :open="false" class="opacity-30" />
								<span class="truncate text-sm"
									:class="{'opacity-50 italic': !child.is_active}"
								>
									{{ child.label }}
								</span>
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