<script setup>
    import { Link } from '@inertiajs/vue3';
    import { ArrowRight } from 'lucide-vue-next';
    import Artwork from '@/Components/Artwork.vue';
    defineProps({
        href: String,
        avatar: String,
        name: String,
        subtext: String,
        withoutArrow: {
            type: Boolean,
            default: false,
        },
        hideAvatar: {
            type: Boolean,
            default: false,
        },
        avatarSize: {
            type: String,
            default: 'w-12 h-12 rounded-full',
        }
    });
</script>

<template>
    <Link :href="href" class="flex items-center justify-between pr-4 hover:bg-accent transition-colors text-primary"
        :class="{ 'rounded-md': !avatar, 'rounded-full': avatar }"
    >
        <div class="flex items-center gap-3">
            <Artwork :artworkUrl="avatar" :name="name" :size="avatarSize" shape="circle" 
                v-if="!hideAvatar"
            />
            <div :class="{ 'px-4 py-1': !avatar }">
                <p class="font-medium">{{ name }}</p>
                <p class="text-xs text-muted-foreground" v-if="subtext">{{ subtext }}</p>
            </div>
        </div>
        <ArrowRight class="size-4" v-if="!withoutArrow" />
    </Link>
</template>