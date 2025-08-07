<script setup>
import { cn } from '@/lib/utils';

const props = defineProps({
  class: { type: null, required: false },
  firstColumn: { type: Boolean, required: false, default: false }
});
</script>

<template>
  <th
    :class="
      cn(
        'h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0',
        props.class,
        {'pl-6': firstColumn}
      )
    "
  >
    <slot />
  </th>
</template>
