<script setup>
    import { Badge as BadgeComponent } from '@/Components/shadcn/ui/badge'

    const props = defineProps({
        variant: {
            type: String,
            default: 'default',
        },
        class: {
            type: String,
            default: 'py-2 px-4 inline-flex items-center',
        },
    })
</script>

<template>
    <BadgeComponent :class="props.class" :variant="variant">
        <slot />
    </BadgeComponent>
</template>