<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});

import { Button } from '@/Components/shadcn/ui/button'
</script>

<template>
    <Button :type="type" variant="default">
        <slot />
    </Button>
</template>
