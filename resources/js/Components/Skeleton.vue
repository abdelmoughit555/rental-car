<script setup>
    import { Skeleton as SkeletonComponent } from '@/Components/shadcn/ui/skeleton'

    const props = defineProps({
        class: {
            type: String,
        },
    });
</script>

<template>
    <SkeletonComponent :class="props.class" />
</template>