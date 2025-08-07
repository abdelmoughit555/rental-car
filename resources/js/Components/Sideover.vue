<template>
    <Teleport to="body">
        <div :class="`relative ${props.zIndex}`" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
                    <div class="absolute inset-0 bg-gray-950 opacity-40" />
                </div>
            </transition>

            <transition name="slide">
                <div class="fixed inset-0 overflow-hidden" v-if="show">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="pointer-events-none fixed inset-y-0 right-0 flex w-full lg:w-3/4 xl:w-3/5 2xl:w-2/5 md:m-4 md:pl-0 sm:pl-16 bg-background rounded-xl border">
                            <div class="pointer-events-auto w-full">
                                <div class="flex h-full flex-col">
                                    <div ref="scrollableContent" class="flex-1 overflow-y-auto">
                                        <div :class="`sticky top-0 px-4 pt-6 sm:px-6 bg-background rounded-t-xl ${props.zIndex}`">
                                            <div class="flex items-start justify-between space-x-3">
                                                <div class="space-y-1">
                                                    <h2 class="text-base font-semibold leading-6" id="slide-over-title">
                                                        <slot name="title" />
                                                    </h2>
                                                    <p class="text-sm text-muted-foreground">
                                                        <slot name="description" />
                                                    </p>
                                                </div>
                                                <div class="flex h-7 items-center">
                                                    <button type="button" class="relative text-gray-400 hover:text-gray-500" @click="close">
                                                        <span class="absolute -inset-2.5"></span>
                                                        <span class="sr-only">Close panel</span>
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="px-6 py-4">
                                            <slot name="content" />
                                        </div>
                                    </div>

                                    <!-- Action buttons -->
                                    <div class="flex-shrink-0 px-4 py-5 sm:px-6 flex gap-x-2 justify-end">
                                        <slot name="footer" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue'

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
        default: false
    },
    
    closeable: {
        type: Boolean,
        required: false,
        default: true
    },

    shouldScrollToTop: {
        type: Number,
    },

    zIndex: {
        type: String,
        default: 'z-50'
    }
})

const emit = defineEmits(['close'])

const scrollableContent = ref(null);

// Methods
const close = () => {
    if (props.closeable) {
        emit('close')
    }
}

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close()
    }
}

// Watchers
watch(() => props.show, (show) => {
    if (show) {
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = null
    }
}, { immediate: true })

// Lifecycle hooks
onMounted(() => {
    document.addEventListener('keydown', closeOnEscape)
})

onBeforeUnmount(() => {
    document.removeEventListener('keydown', closeOnEscape)
    document.body.style.overflow = null
})

// Function to scroll to top
const scrollToTop = () => {
    nextTick(() => {
        if (scrollableContent.value) {
            scrollableContent.value.scrollTop = 0;
        }
    });
}

defineExpose({
    scrollToTop
})
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}

.slide-enter-active, .slide-leave-active {
    transition: all .5s ease;
}
.slide-enter-from, .slide-leave-to {
    transform: translateX(100%);
}
.slide-enter-to, .slide-leave-from {
    transform: translateX(0);
}
</style>