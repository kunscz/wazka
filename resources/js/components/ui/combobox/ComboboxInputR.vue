<script setup lang="ts">
import { ComboboxContent, ComboboxInput, ComboboxItem, ComboboxPortal, ComboboxRoot } from 'reka-ui'
import { ref } from 'vue'

const people = [
  { id: 1, name: 'Durward Reynolds', unavailable: false },
  { id: 2, name: 'Kenton Towne' },
  { id: 3, name: 'Therese Wunsch' },
  { id: 4, name: 'Benedict Kessler' },
  { id: 5, name: 'Katelyn Rohan' },
]
const selectedPeople = ref(people[0])
</script>

<template>
  <ComboboxRoot v-model="selectedPeople" class="relative">
    <ComboboxInput :display-value="(v) => v.name" class="!bg-transparent outline-none text-grass11 h-full selection:bg-grass5 placeholder-stone-400" />
    <ComboboxPortal>
      <ComboboxContent class="absolute z-10 w-full mt-1 min-w-[160px] overflow-hidden rounded-lg shadow-sm border will-change-[opacity,transform] data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade">
        <ComboboxItem
          v-for="person in people"
          :key="person.id"
          :value="person"
          :disabled="person.unavailable"
        >
          {{ person.name }}
        </ComboboxItem>
      </ComboboxContent>
    </ComboboxPortal>
  </ComboboxRoot>
</template>