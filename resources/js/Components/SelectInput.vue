<template>
    <div>
        <InputLabel :for="name" v-if="hasLabel">
            <slot name="label" />
        </InputLabel>
        <div :class="cn('mt-0', descriptionClass)" v-if="description">
            <InputHelp>
                {{ description }}
            </InputHelp>
        </div>

        <div :class="{ 'mt-2': hasLabel }">
            <select :id="id" :name="name" :autocomplete="autoComplete" :disabled="disabled" v-model="value" :class="[
                'flex h-10 w-full rounded-md border bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-0',
                {
                    'text-destructive placeholder-destructive focus:border-destructive focus:outline-none focus:ring-destructive border border-destructive': hasError,
                    'disabled:cursor-not-allowed disabled:opacity-50': disabled,
                    'border-input': !hasError,
                }
            ]">
                <option v-for="item in items" :key="item.id" :value="item.id" :disabled="item.disabled">{{ item.name }}
                </option>
            </select>
        </div>

        <div class="mt-2" v-if="errors && errors.length">
            <InputError v-for="error in errors" :key="error">{{ error }}</InputError>
        </div>
    </div>
</template>

<script setup>
import { cn } from '@/lib/utils';
import { ref, computed, watch, onMounted, useSlots } from 'vue'
import InputLabel from './InputLabel.vue'
import InputError from './InputError.vue'
import InputHelp from './InputHelp.vue'
const slots = useSlots()

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    id: {
        type: String,
        required: true
    },
    autoComplete: {
        type: String,
        default: null
    },
    disabled: {
        type: Boolean,
        default: false
    },
    items: {
        type: Array,
        required: true
    },
    modelValue: {
        required: false
    },
    hasError: {
        type: Boolean,
        default: false
    },
    errors: {
        type: Array,
        required: false
    },
    description: {
        type: String,
        required: false
    },
    descriptionClass: {
        type: String,
        required: false
    }
})

const emit = defineEmits(['update:modelValue', 'input'])

const value = ref(null)

const hasLabel = computed(() => !!slots.label)

watch(value, () => {
    emit('update:modelValue', value.value)
    emit('input', value.value)
})

watch(() => props.modelValue, () => {
    if (props.modelValue !== undefined) {
        value.value = props.modelValue
    }
})

onMounted(() => {
    if (props.modelValue != null && props.modelValue != undefined) {
        value.value = props.modelValue
    }
})
</script>