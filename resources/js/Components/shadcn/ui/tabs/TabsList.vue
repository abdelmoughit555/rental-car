<script setup>
import { cn } from '@/lib/utils';
import { TabsList } from 'radix-vue';
import { computed } from 'vue';

const props = defineProps({
  loop: { type: Boolean, required: false },
  asChild: { type: Boolean, required: false },
  as: { type: null, required: false },
  class: { type: null, required: false },
  orientation: { type: String, required: false },
});

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;

  return delegated;
});
</script>

<template>
  <TabsList
    v-bind="delegatedProps"
    :class="
      cn(
        'inline-flex items-center text-muted-foreground',
        props.orientation === 'horizontal' ? 'bg-accent rounded-lg py-1.5 px-2' : 'rounded-md justify-center space-y-2',
        props.class,
      )
    "
  >
    <slot />
  </TabsList>
</template>
