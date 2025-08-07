<script setup>
import { ref, watch, defineEmits, useSlots, computed, defineProps } from 'vue';
import { Tag } from '@/Components/shadcn/ui/tag'
import InputLabel from './InputLabel.vue'
import InputError from './InputError.vue'
import InputHelp from './InputHelp.vue'
const slots = useSlots()

const props = defineProps({
    modelValue: {
        required: false,
        default: ''
    },
    name: {
        type: String,
        required: true
    },
    id: {
        type: String,
        required: true
    },
    disabled: {
        type: Boolean,
        default: false
    },
    hasError: {
        type: Boolean,
        default: false
    },
    errors: {
        type: Array,
        required: false
    },
    placeholder: {
        type: String,
        required: false
    },
    type: {
        type: String,
        default: 'text'
    }
});

const value = ref(props.modelValue ?? '')

const emit = defineEmits(['update:modelValue']);

watch(value, (newVal) => {
    emit('update:modelValue', newVal);
});

watch(
    () => props.modelValue,
    (newVal) => {
        value.value = newVal;
    }
);

const hasLabel = computed(() => !!slots.label)
const hasHelp = computed(() => !!slots.help)
</script>

<template>
    <div>
        <InputLabel :for="name" v-if="hasLabel">
            <slot name="label" />
        </InputLabel>
        <InputHelp v-if="hasHelp">
            <slot name="help" />
        </InputHelp>

        <div :class="{ 'mt-2': hasLabel }">
            <Tag
                ref="input"
                v-model="value"
                :id="id"
                :name="name"
                :disabled="disabled"
                :placeholder="placeholder"
                :type="type"
                :class="[
                    {
                        'text-destructive placeholder-destructive focus:border-destructive focus:outline-none focus:ring-destructive border-destructive': hasError,
                        'disabled:cursor-not-allowed disabled:opacity-50': disabled,
                    }
                ]"
            />
        </div>

        <div class="mt-2" v-if="errors && errors.length">
            <InputError v-for="error in errors" :key="error">{{ error }}</InputError>
        </div>
    </div>
</template>
