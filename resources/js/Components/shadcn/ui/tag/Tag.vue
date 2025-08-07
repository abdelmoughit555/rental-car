<script setup>
import {
  TagsInputRoot,
  TagsInputInput,
  TagsInputItem,
  TagsInputItemDelete,
  TagsInputItemText,
} from 'radix-vue'
import { X } from 'lucide-vue-next';
import { useVModel } from '@vueuse/core'
import { cn } from '@/lib/utils'

const props = defineProps({
  modelValue: Array,
  class: { type: null, required: false },
})

const emit = defineEmits(['update:modelValue'])

const modelValue = useVModel(props, 'modelValue', emit)
</script>

<template>
  <TagsInputRoot
    v-model="modelValue"
    :class="
      cn(
        'flex h-auto w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-0 disabled:cursor-not-allowed disabled:opacity-50',
        'gap-1 flex-wrap min-h-[2.5rem]',
        props.class,
      )
    "
  >
    <TagsInputItem
      v-for="item in modelValue"
      :key="item"
      :value="item"
      class="text-foreground flex items-center gap-2 bg-muted rounded px-2 py-1"
    >
      <TagsInputItemText class="text-sm" />
      <TagsInputItemDelete>
        <X class="w-4 h-4" />
      </TagsInputItemDelete>
    </TagsInputItem>

    <TagsInputInput
      placeholder="Enter tags..."
      class="flex-1 bg-transparent focus:outline-none text-sm placeholder:text-muted-foreground border-gray-300"
    />
  </TagsInputRoot>
</template>
