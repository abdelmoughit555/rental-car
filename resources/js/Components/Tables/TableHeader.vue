<script setup>
import { TableHeader } from '@/Components/shadcn/ui/table'

const props = defineProps({
    firstColumn: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <TableHeader class="bg-muted rounded-t-lg" :class="{ 'pl-8': !firstColumn }">
        <slot />
    </TableHeader>
</template>