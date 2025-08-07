
<script setup>
    import { computed } from 'vue';
    import Badge from '@/Components/Badge.vue';
    import { useActivityLog } from '@/stores/activityLog';

    const props = defineProps({
        event: String,
    });

    const eventType = computed(() => {
        return useActivityLog().getEvent(props.event);
    });
</script>

<template>
    <Badge :variant="eventType.variant" class="p-3 rounded-full">
        <Component :is="eventType.icon" class="size-4" />
    </Badge>
</template>