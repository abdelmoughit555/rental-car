<script setup>
    import WaveSurfer from 'wavesurfer.js'
    import { Button } from '@/Components/shadcn/ui/button'
    import { onMounted, defineEmits, defineProps, ref, computed, watch } from 'vue'
    import { Play, Pause } from 'lucide-vue-next'
    import { useTheme } from '@/Composables/useTheme'

    const props = defineProps({
        url: {
            type: String,
            required: false
        },

        blob: {
            type: Blob,
            required: false
        },

        containerId: {
            type: String,
            required: false,
            default: 'waveform'
        },

        autoPlay: {
            type: Boolean,
            default: false
        }
    })

    const { isDark } = useTheme()
    const wavesurfer = ref(null)
    const duration = ref(null)
    const loading = ref(false)
    const currentTime = ref(0)
    const currentProgress = ref(0)
    const isPlaying = ref(false)
    const emit = defineEmits(['close', 'play', 'paused'])

    onMounted(() => {
        wavesurfer.value = WaveSurfer.create({
            container: `#${props.containerId}`,
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
            autoplay: props.autoPlay,
            interact: true,
            dragToSeek: false,
            hideScrollbar: false,
            audioRate: 1,
            autoScroll: true,
            autoCenter: true,
        })

        if (props.blob) {
            wavesurfer.value.loadBlob(props.blob)
        } else {
            wavesurfer.value.load(props.url)
        }

        wavesurfer.value.on("ready", () => {
            duration.value = Math.round(wavesurfer.value.getDuration());
            loading.value = false;
        });

        wavesurfer.value.on("audioprocess", (time) => {
            currentTime.value = Math.round(time);
            currentProgress.value = currentTime.value;
        });

        wavesurfer.value.on('play', () => {
            emit("play")
            isPlaying.value = true;
        });

        wavesurfer.value.on('pause', () => {
            emit("paused")
            isPlaying.value = false;
        });

        wavesurfer.value.on('finish', () => {
            emit("paused")
            isPlaying.value = false
        });
    })

    const playPause = () => {
        wavesurfer.value.playPause()
    }

    const pause = () => {
        wavesurfer.value.pause()
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

    // watch(isDark, () => {
    //     if (wavesurfer.value) {
    //         wavesurfer.value.setOptions({
    //             waveColor: `hsl(var(--muted-foreground))`,
    //             progressColor: `hsl(var(--accent))`,
    //             cursorColor: `hsl(var(--accent-foreground))`
    //         })
    //     }
    // })
</script>

<template>
    <div class="border border-border rounded-lg p-4 flex items-center flex-row gap-2">
        <div>
            <Button variant="outline" @click="playPause" :disabled="loading">
                <Pause class="size-6 text-muted-foreground" v-if="isPlaying" />
                <Play class="size-6 text-muted-foreground" v-else />
            </Button>
        </div>

        <div class="flex-1 px-8">
            <div :id="containerId"></div>
        </div>

        <div>
            <p class="text-sm text-muted-foreground">
                {{ formattedCurrentTime }} / {{ formattedDuration }}
            </p>
        </div>
    </div>
</template>