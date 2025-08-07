<script setup>
import Plyr from 'plyr';
import 'plyr/dist/plyr.css';
import { onMounted, ref, watch } from 'vue';

const player = ref(null);

const props = defineProps({
    src: {
        type: String,
        required: true
    },
    autoplay: {
        type: Boolean,
        default: false
    },
    name: {
        type: String,
        default: 'video-player'
    }
});

const emits = defineEmits(['timeupdate']);

onMounted(() => {
    player.value = new Plyr(`#${props.name}`, {
        autoplay: props.autoplay,
        controls: ['play', 'progress', 'current-time', 'mute', 'fullscreen'],
        muted: true,
        loop: { active: true }
    });

    player.value.on('timeupdate', (event) => {
        emits('timeupdate', event.detail.plyr.currentTime);
    });

    // Load initial source
    player.value.source = {
        type: 'video',
        sources: [{ src: props.src, type: 'video/mp4' }]
    };
});

watch(() => props.src, (newSrc) => {
    if (player.value) {
        player.value.source = {
            type: 'video',
            sources: [{ src: newSrc, type: 'video/mp4' }]
        };
    }
});
</script>

<template>
    <div>
        <video :id="name" playsinline></video>
    </div>
</template>
