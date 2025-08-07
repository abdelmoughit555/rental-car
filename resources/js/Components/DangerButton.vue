<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});

import { Button } from '@/Components/shadcn/ui/button'
</script>

<template>
    <Button :type="type" variant="destructive">
        <slot />
    </Button>
</template>
