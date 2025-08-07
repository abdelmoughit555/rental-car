<script setup lang="ts">
    import { Play, Pause } from 'lucide-vue-next';
    import { Button } from '@/Components/shadcn/ui/button';
    import { useAudioPlayer } from '@/stores/audioPlayer';
    import { storeToRefs } from 'pinia';
    
    const audioPlayer = useAudioPlayer();
    const { playerTrack, disabled, playing } = storeToRefs(audioPlayer);

    const props = defineProps({
        id: [String, Number],
        url: String,
        blob: Blob,
        title: String,
        artist: String,
        thumbnail: String,
    })

    const play = () => {
        audioPlayer.play(
            {
                id: props.id,
                url: props.url,
                blob: props.blob,
                title: props.title,
                artist: props.artist,
                thumbnail: props.thumbnail,
            }
        );
    };

    const pause = () => {
        audioPlayer.pause();
    };
</script>

<template>
    <Button variant="ghost" class="p-2" @click="pause" :disabled="disabled" v-if="playing && playerTrack.id === id">
        <Pause />
    </Button>
    <Button variant="ghost" class="p-2" @click="play" :disabled="disabled" v-else>
        <Play />
    </Button>
</template>