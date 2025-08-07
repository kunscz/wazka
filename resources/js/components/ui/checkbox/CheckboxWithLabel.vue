<script setup lang="ts">
import {
  CheckboxRoot,
  CheckboxIndicator,
  useForwardPropsEmits,
  type CheckboxRootProps,
  type CheckboxRootEmits,
} from 'reka-ui'
import { Check } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import { computed, useId, type HTMLAttributes } from 'vue'

const props = defineProps<
  CheckboxRootProps & {
    label?: string
    labelClass?: HTMLAttributes['class']
    class?: HTMLAttributes['class']
  }
>()

const emits = defineEmits<CheckboxRootEmits>()

const delegatedProps = computed(() => {
  const { label, labelClass, class: _, ...rest } = props
  return rest
})

const forwarded = useForwardPropsEmits(delegatedProps, emits)
const labelId = useId()
</script>

<template>
  <label class="flex items-center gap-2 cursor-pointer select-none">
    <CheckboxRoot
      data-slot="checkbox"
      v-bind="forwarded"
      :aria-labelledby="props.label ? labelId : undefined"
      :class="
        cn(
          'peer border-input data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground data-[state=checked]:border-primary focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive size-4 shrink-0 rounded-[4px] border shadow-xs transition-shadow outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50',
          props.class
        )
      "
    >
      <CheckboxIndicator
        data-slot="checkbox-indicator"
        class="flex items-center justify-center text-current transition-none"
      >
        <slot>
          <Check class="size-3.5" />
        </slot>
      </CheckboxIndicator>
    </CheckboxRoot>

    <span
      v-if="props.label"
      :id="labelId"
      :class="props.labelClass ?? 'text-sm text-foreground'"
    >
      {{ props.label }}
    </span>
  </label>
</template>