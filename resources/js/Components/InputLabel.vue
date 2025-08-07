<script setup>
import { Label } from '@/Components/shadcn/ui/label'

defineProps({
    value: String,
    for: {
        type: [String, Boolean],
        required: false
    },
});
</script>

<template>
    <Label>
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </Label>
</template>
