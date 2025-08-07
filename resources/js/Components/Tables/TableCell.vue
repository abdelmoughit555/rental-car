<script setup>
import { TableCell } from '@/Components/shadcn/ui/table'

defineProps({
    class: {
        type: String,
    },
});
</script>

<template>
    <TableCell :class="class">
        <slot />
    </TableCell>
</template>