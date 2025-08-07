<script setup lang="ts">
import { cn } from '@/lib/utils';
import { cva } from 'class-variance-authority';

const dataVariants = cva(
    '',
    {
        variants: {
            variant: {
                new: 'border px-3 border-green-400 text-green-600 font-medium',
                old: 'border px-3 border-red-400 text-red-600 font-medium',
                default: 'text-sm font-medium text-muted-foreground italic',
            },
        },
        defaultVariants: {
            variant: 'default',
        },
    },
);

const props = defineProps<{
    variant?: 'new' | 'old';
    class?: string;
}>();
</script>

<template>
    <div :class="cn(dataVariants({ variant: props.variant }), props.class)">
        <p>
            <slot />
        </p>
    </div>
</template>
