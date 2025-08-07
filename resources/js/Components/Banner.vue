<script setup>
import { ref } from 'vue';

const props = defineProps({
    type: {
        type: String,
        default: 'success',
        validator: (value) => ['success', 'danger', 'warning', 'info'].includes(value)
    },
    message: {
        type: String,
        default: ''
    },
    show: {
        type: Boolean,
        default: true
    }
});

const emit = defineEmits(['close']);
const isVisible = ref(props.show);

const close = () => {
    isVisible.value = false;
    emit('close');
};

const styleClasses = {
    success: 'bg-green-500',
    danger: 'bg-red-500',
    warning: 'bg-yellow-500',
    info: 'bg-blue-500'
};

const iconClasses = {
    success: 'bg-green-600',
    danger: 'bg-red-600',
    warning: 'bg-yellow-600',
    info: 'bg-blue-600'
};

const hoverClasses = {
    success: 'hover:bg-green-600 focus:bg-green-600',
    danger: 'hover:bg-red-600 focus:bg-red-600',
    warning: 'hover:bg-yellow-600 focus:bg-yellow-600',
    info: 'hover:bg-blue-600 focus:bg-blue-600'
};
</script>

<template>
    <div>
        <div v-if="isVisible && message" :class="styleClasses[type]">
            <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center min-w-0">
                        <span class="flex p-2 rounded-lg" :class="iconClasses[type]">
                            <svg v-if="type == 'success'" class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>

                            <svg v-if="type == 'danger'" class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>

                            <svg v-if="type == 'warning'" class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>

                            <svg v-if="type == 'info'" class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </span>

                        <p class="ms-3 font-medium text-sm text-white truncate">
                            {{ message }}
                        </p>
                    </div>

                    <div class="shrink-0 sm:ms-3">
                        <button
                            type="button"
                            class="-me-1 flex p-2 rounded-md focus:outline-none sm:-me-2 transition"
                            :class="hoverClasses[type]"
                            aria-label="Dismiss"
                            @click.prevent="close"
                        >
                            <svg class="size-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
