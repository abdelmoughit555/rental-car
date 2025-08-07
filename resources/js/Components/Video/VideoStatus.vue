
<script setup>
    import { computed } from 'vue';
    import Badge from '@/Components/Badge.vue';
    import { useVideos } from '@/stores/videos';

    const props = defineProps({
        status: Number,
    });

    const statusType = computed(() => {
        return useVideos().getStatus(props.status);
    });
</script>

<template>
    <Badge :variant="statusType.variant" class="py-1.5 px-3 rounded-md gap-2">
        <Component :is="statusType.icon" class="size-4" :class="statusType.name == 'Running' ? 'animate-spin' : ''" />
        {{ statusType.name }}
    </Badge>
</template>