<script setup>
import Modal from './Modal.vue';
import { computed, defineProps } from 'vue';
import { ExclamationTriangleIcon, CheckCircleIcon, XCircleIcon, InformationCircleIcon } from '@heroicons/vue/20/solid'

const emit = defineEmits(['close']);

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    type: {
        type: String,
        default: 'error',
        validation: ['error', 'warning', 'info', 'success'],
    }
});

const iconClass = computed(() => {
    return {
        'success': 'text-green-50',
        'error': 'text-red-50',
        'warning': 'text-yellow-50',
        'info': 'text-white'
    }[props.type];
});

const iconBackgroundClass = computed(() => {
    return {
        'success': 'bg-green-600',
        'error': 'bg-red-600',
        'warning': 'bg-yellow-600',
        'info': 'bg-primary rounded'
    }[props.type];
});

const close = () => {
    emit('close');
};
</script>

<template>
    <Modal
        :show="show"
        :max-width="maxWidth"
        :closeable="closeable"
        @close="close"
    >
        <div class="bg-background px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
                <div class="mx-auto shrink-0 flex items-center justify-center size-12 rounded-full" :class="iconBackgroundClass">
                    <ExclamationTriangleIcon v-if="type === 'warning'" class="size-6" :class="iconClass" aria-hidden="true" />
                    <CheckCircleIcon v-else-if="type === 'success'" class="size-6" :class="iconClass" aria-hidden="true" />
                    <XCircleIcon v-else-if="type === 'error'" class="size-6" :class="iconClass" aria-hidden="true" />
                    <InformationCircleIcon v-else-if="type === 'info'" class="size-6" :class="iconClass" aria-hidden="true" />
                </div>

                <div class="flex-1 mt-3 text-center sm:mt-0 sm:ms-4 sm:text-start">
                    <h3 class="text-lg font-medium">
                        <slot name="title" />
                    </h3>

                    <div class="mt-1 text-sm text-muted-foreground">
                        <slot name="content" />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-row justify-end space-x-2 px-6 py-4 bg-background text-end">
            <slot name="footer" />
        </div>
    </Modal>
</template>
