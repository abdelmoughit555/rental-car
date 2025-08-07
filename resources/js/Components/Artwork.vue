<script setup lang="ts">
    import { cn } from '@/lib/utils';
    import {
        Avatar,
        AvatarFallback,
        AvatarImage
    } from '@/Components/shadcn/ui/avatar'
    import { defineProps } from 'vue'
    import { useInitials } from '@/Composables/useInitials'
    const { getInitials } = useInitials()

    const props = defineProps({
        artworkUrl: {
            type: [String, null],
            required: false,
        },

        name: {
            type: [String, null],
            required: false,
        },

        height: {
            type: String,
            default: 'h-8',
        },

        width: {
            type: String,
            default: 'w-8',
        },

        size: {
            type: [String, Number],
            default: 'sm',
        },
        animation : {
            type: Boolean,
            required: false
        }
    })

</script>

<template>
    <Avatar :class="height + ' ' + width" :size="size">
        <AvatarImage
            v-if="artworkUrl"
            :src="artworkUrl"
            :alt="name"
            :class="cn([animation ? 'transition-all hover:scale-105 object-cover' : ''])"
        />
        <AvatarFallback>{{ getInitials(name) }}</AvatarFallback>
    </Avatar>
</template>
