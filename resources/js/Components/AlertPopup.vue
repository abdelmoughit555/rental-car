<script setup>
import { computed, defineProps, defineEmits } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ExclamationTriangleIcon, CheckCircleIcon, XCircleIcon, InformationCircleIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
    type: {
        type: String,
        validation: ['success', 'error', 'warning', 'info']
    },

    show: {
        type: Boolean,
        default: true
    },

    maxWidth: {
        type: String,
        default: 'sm'
    },

    buttonText: {
        type: String,
        default: 'Close'
    }
})

const emit = defineEmits(['close'])

const close = () => {
    emit('close');
}

const backgroundClass = computed(() => {
    return {
        'success': 'bg-green-50',
        'error': 'bg-red-50',
        'warning': 'bg-yellow-50',
        'info': 'bg-primary-foreground rounded',
        'muted': 'bg-muted rounded'
    }[props.type];
});

const iconClass = computed(() => {
    return {
        'success': 'text-green-500',
        'error': 'text-red-500',
        'warning': 'text-yellow-500',
        'info': 'text-primary',
        'muted': 'text-muted-foreground'
    }[props.type];
});
</script>

<template>
      <Modal
        :show="show"
        :max-width="maxWidth"
        @close="close"
    >
        <div class="bg-background px-6 pt-6 pb-2">
            <div class="">
                <div class="mx-auto  flex items-center justify-center size-12 rounded-full" :class="backgroundClass">
                    <ExclamationTriangleIcon v-if="type === 'warning'" class="size-6" :class="iconClass" aria-hidden="true" />
                    <CheckCircleIcon v-else-if="type === 'success'" class="size-6" :class="iconClass" aria-hidden="true" />
                    <XCircleIcon v-else-if="type === 'error'" class="size-6" :class="iconClass" aria-hidden="true" />
                    <InformationCircleIcon v-else-if="type === 'info'" class="size-6" :class="iconClass" aria-hidden="true" />
                    <InformationCircleIcon v-else-if="type === 'muted'" class="size-6" :class="iconClass" aria-hidden="true" />
                </div>

                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-center">
                        <slot name="title" />
                    </h3>

                    <div class="mt-1 text-sm text-muted-foreground">
                        <slot name="description" />
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-background px-6 pb-4 pt-3">
            <PrimaryButton class="w-full" @click="close">
                {{ buttonText }}
            </PrimaryButton>
        </div>
    </Modal>
</template>
