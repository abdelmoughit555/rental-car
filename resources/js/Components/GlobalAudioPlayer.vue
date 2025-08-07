<script setup>
    import WaveSurfer from 'wavesurfer.js'
    import { watch, onMounted, ref, computed } from 'vue';
    import { storeToRefs } from 'pinia';
    import { Button } from '@/Components/shadcn/ui/button';
    import Artwork from '@/Components/Artwork.vue';
    import Skeleton from '@/Components/Skeleton.vue';
    import { Play, Pause, X } from 'lucide-vue-next';
    import { useAudioPlayer } from '@/stores/audioPlayer';
    import { useTheme } from '@/Composables/useTheme';
    import { cn } from '@/lib/utils';

    const containerId = "global_audio_file";
    const audioPlayer = useAudioPlayer()
    const { playerTrack, playing, disabled, show } = storeToRefs(audioPlayer)

    const { isDark } = useTheme()
    const wavesurfer = ref(null)
    const duration = ref(null)
    const currentTime = ref(0)
    const currentProgress = ref(0)

    onMounted(() => {
        wavesurfer.value = WaveSurfer.create({
            container: `#${containerId}`,
            height: 25,
            splitChannels: false,
            normalize: true,
            backend: 'MediaElement',
            waveColor: isDark.value ? '#94a3b8' : '#333333',
            progressColor: isDark.value ? '#f8fafc' : '#3b82f6',
            cursorColor: isDark.value ? '#4F46E5' : '#4F46E5',
            cursorWidth: 2,
            barWidth: 2,
            barGap: 3,
            barRadius: 3,
            barHeight: null,
            barAlign: "",
            minPxPerSec: 1,
            fillParent: true,
            mediaControls: false,
            autoplay: false,
            interact: true,
            dragToSeek: false,
            hideScrollbar: false,
            audioRate: 1,
            autoScroll: true,
            autoCenter: true,
        });

        wavesurfer.value.on("ready", () => {
            duration.value = Math.round(wavesurfer.value.getDuration());
            audioPlayer.setDisabled(false);
            if (playing.value) {
                wavesurfer.value.play();
            }
        });

        wavesurfer.value.on("audioprocess", (time) => {
            currentTime.value = Math.round(time);
            currentProgress.value = currentTime.value;
        });

        wavesurfer.value.on('pause', () => {
            if (!disabled.value) {
                audioPlayer.setPlaying(false);
            }
        });

        wavesurfer.value.on('finish', () => {
            audioPlayer.setPlaying(false);
        });
    })

    watch(playerTrack, (newPlayerTrack) => {
        if (newPlayerTrack.url) {
            duration.value = null;
            currentTime.value = 0;
            if (newPlayerTrack.blob) {
                wavesurfer.value.loadBlob(newPlayerTrack.blob)
            } else {
                wavesurfer.value.load(newPlayerTrack.url)
            }
        }
    });
    
    watch(playing, () => {
        if (!disabled.value) {
            if (playing.value) {
                wavesurfer.value.play()
            } else {
                wavesurfer.value.pause()
            }
        }
    });

    const play = () => {
        audioPlayer.setPlaying(true);
    }

    const pause = () => {
        audioPlayer.setPlaying(false);
    }

    const close = () => {
        audioPlayer.setShow(false);
        audioPlayer.setDisabled(false);
        if (playing.value) {
            audioPlayer.setPlaying(false);
        }
    }

    const formattedDuration = computed(() => {
        let minutes = Math.floor(duration.value / 60);
        let seconds = Math.round(duration.value % 60);
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    })

    const formattedCurrentTime = computed(() => {
        let minutes = Math.floor(currentTime.value / 60);
        let seconds = Math.round(currentTime.value % 60);
        return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    })
</script>

<template>
    <div
        v-show="show"
        :class="cn([
            'fixed bottom-0 left-0 right-0 w-full z-50 ml-0 py-4 px-8',
            isDark ? 'bg-black' : 'bg-accent'
        ])"
    >
        <div class="container mx-auto w-[80%]">
            <div class="py-4 flex flex-wrap items-center flex-row gap-2">
                <div class="flex-auto max-w-max">
                    <Button class="p-2" variant="outline" @click="pause" v-if="playing" :disabled="disabled">
                        <Pause class="size-6 text-muted-foreground" />
                    </Button>
                    <Button class="p-2" variant="outline" @click="play" v-else>
                        <Play class="size-6 text-muted-foreground" />
                    </Button>
                </div>

                <div class="flex items-center space-x-2 border-l border-gray-800 pl-2 flex-1 max-w-auto lg:max-w-fit">
                    <Artwork :artworkUrl="playerTrack.thumbnail" name="" height="10" width="10" shape="square" v-if="playerTrack.thumbnail" />
                    <div class="text-sm font-medium">
                        <p>{{ playerTrack.title }}</p>
                        <p class="text-muted-foreground hidden md:block">{{ playerTrack.artist }}</p>
                    </div>
                </div>

                <div class="flex-none lg:flex-1 px-8 border border-border rounded-lg py-2 min-w-[auto] hidden lg:block">
                    <div :id="containerId" v-show="!disabled"></div>
                    <Skeleton v-if="disabled" class="w-full h-[25px]" />
                </div>

                <div class="flex-auto max-w-max">
                    <p class="text-sm text-muted-foreground">
                        {{ formattedCurrentTime }} / {{ formattedDuration }}
                    </p>
                </div>
                <div class="flex">
                    <Button class="p-2" variant="outline" @click="close">
                        <X class="size-6 text-muted-foreground"/>
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>