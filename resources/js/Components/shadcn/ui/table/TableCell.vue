<script setup>
import { cn } from '@/lib/utils';

const props = defineProps({
  class: { type: null, required: false },
  firstColumn: { type: Boolean, required: false, default: false }
});
</script>

<template>
  <td
    :class="cn('p-4 align-middle [&:has([role=checkbox])]:pr-0', props.class, {'pl-6': firstColumn})"
  >
    <slot />
  </td>
</template>
