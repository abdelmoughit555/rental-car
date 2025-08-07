<script setup>
import { cn } from '@/lib/utils';
import { Table, TableHeader, TableBody, TableRow } from '@/Components/shadcn/ui/table'

const props = defineProps({
    class: {
        type: String,
    },

    headerClass: {
        type: String,
        default: 'bg-accent rounded-t-lg',
    },
});
</script>

<template>
    <div>
        <Table :class="props.class">
            <TableHeader :class="cn(props.headerClass)">
                <TableRow class="bg-white dark:bg-background" v-show="$slots.actions">
                    <slot name="actions" />
                </TableRow>
                <TableRow>
                    <slot name="headers" />
                </TableRow>
            </TableHeader>
            <TableBody>
                <slot name="rows" />
            </TableBody>
        </Table>

        <slot name="pagination" />
    </div>
</template>