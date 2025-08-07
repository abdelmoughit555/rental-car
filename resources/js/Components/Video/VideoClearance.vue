
<script setup>
    import { computed } from 'vue';
    import Badge from '@/Components/Badge.vue';
    import { useVideos } from '@/stores/videos';

    const props = defineProps({
        clearance: Number,
    });

    const clearanceType = computed(() => {
        return useVideos().getClearance(props.clearance);
    });
</script>

<template>
    <Badge :variant="clearanceType.variant" class="py-1.5 px-3 rounded-md gap-2">
        {{ clearanceType.name }}
    </Badge>
</template>