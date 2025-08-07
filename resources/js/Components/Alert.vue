<script setup>
import { ExclamationTriangleIcon, CheckCircleIcon, XCircleIcon, InformationCircleIcon } from '@heroicons/vue/20/solid'
import { computed, defineProps } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    type: {
        type: String,
        validation: ['success', 'error', 'warning', 'info', 'muted']
    },

    show: {
        type: Boolean,
        default: true
    }
})

const backgroundClass = computed(() => {
    return {
        'success': 'bg-green-600',
        'error': 'bg-red-600',
        'warning': 'bg-yellow-600',
        'info': 'bg-primary rounded',
        'muted': 'bg-accent rounded'
    }[props.type];
});

const titleClass = computed(() => {
    return {
        'success': 'text-white',
        'error': 'text-white',
        'warning': 'text-white',
        'info': 'text-white',
        'muted': 'text-muted-foreground'
    }[props.type];
});

const messageClass = computed(() => {
    return {
        'success': 'text-green-50',
        'error': 'text-red-50',
        'warning': 'text-yellow-50',
        'info': 'text-white',
        'muted': 'text-muted-foreground'
    }[props.type];
});

const iconClass = computed(() => {
    return {
        'success': 'text-green-50',
        'error': 'text-red-50',
        'warning': 'text-yellow-50',
        'info': 'text-white',
        'muted': 'text-muted-foreground'
    }[props.type];
});
</script>

<template>
    <div class="rounded-md p-4" v-show="show" :class="backgroundClass">
        <div class="flex justify-between items-start">
            <div class="flex-1 flex">
            <div class="shrink-0">
                <ExclamationTriangleIcon v-if="type === 'warning'" class="size-5" :class="iconClass" aria-hidden="true" />
                <CheckCircleIcon v-else-if="type === 'success'" class="size-5" :class="iconClass" aria-hidden="true" />
                <XCircleIcon v-else-if="type === 'error'" class="size-5" :class="iconClass" aria-hidden="true" />
                <InformationCircleIcon v-else-if="type === 'info'" class="size-5" :class="iconClass" aria-hidden="true" />
                <InformationCircleIcon v-else-if="type === 'muted'" class="size-5" :class="iconClass" aria-hidden="true" />
            </div>
            <div class="ml-3 flex-1">
                <h3 class="text-sm font-medium" :class="titleClass">
                    <slot name="title" />
                </h3>
                <div class="mt-2 text-sm" :class="messageClass" v-if="$slots.message">
                    <slot name="message" />
                </div>
                <div class="mt-2 text-sm" v-if="$slots.actions">
                    <slot name="actions" />
                </div>
            </div>
        </div>
        <div>
            <slot name="route" />
        </div>
        </div>
    </div>
</template>
