
<script setup>
import InputLabel from './InputLabel.vue'
import InputError from './InputError.vue'
import InputHelp from './InputHelp.vue'
import { ref, computed, watch, onMounted, useSlots } from 'vue'
import { RadioGroup, RadioGroupItem } from '@/Components/shadcn/ui/radio-group'

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    id: {
        type: String,
        required: true
    },
    items: {
        type: Array,
        required: true
    },
    modelValue: {
        required: false
    },
    errors: {
        type: Array,
        required: false
    },
    columns: {
        type: Number,
        required: false,
        default: 2
    }
})

const emit = defineEmits(['update:modelValue'])
const slots = useSlots()
const selected = ref('')

const hasLabel = computed(() => {
    return !!slots.label
})

const hasHelp = computed(() => {
    return !!slots.help
})

const columnLayout = computed(() => {
    return {
        1: 'lg:grid-cols-1',
        2: 'lg:grid-cols-2',
        3: 'lg:grid-cols-3',
        4: 'md:grid-cols-2 lg:grid-cols-4',
    }[props.columns]
})

watch(selected, () => {
    emit('update:modelValue', selected.value)
})

watch(() => props.modelValue, () => {
    selected.value = props.modelValue
})

onMounted(() => {
    if (props.modelValue !== null) {
        selected.value = props.modelValue
    }
})
</script>

<template>
    <div>
        <InputLabel v-if="hasLabel" :for="name">
            <slot name="label" />
        </InputLabel>

        <InputHelp v-if="hasHelp">
            <slot name="help" />
        </InputHelp>

        <RadioGroup v-model="selected" :name="name" :id="id"
            class="grid grid-cols-1 gap-4"
            :class="[columnLayout, { 'mt-2': hasLabel }]"
        >
            <div v-for="item in items" :key="item.id" @click="selected = item.id"
                class="relative flex cursor-pointer rounded-lg border p-4 shadow-sm focus:outline-none"
                :class="[
                    selected === item.id
                        ? 'border-input ring-1 ring-offset-background focus:ring-1 focus:ring-offset-background focus:ring-ring'
                        : 'border'
                ]"
            >
                <InputLabel :for="item.id" class="flex flex-1 cursor-pointer">
                    <RadioGroupItem
                        :value="item.id"
                        :id="item.id"
                        class="sr-only"
                    />
                    <span class="flex flex-col">
                        <component
                            v-if="item.icon"
                            :is="item.icon"
                            class="mb-3 size-6 text-muted-foreground"
                        />
                        <span class="block text-sm font-medium text-foreground">{{ item.name }}</span>
                        <span class="mt-1 flex items-center text-sm text-muted-foreground" v-if="item.description">
                            {{ item.description }}
                        </span>
                    </span>
                </InputLabel>

                <svg
                    class="size-5 text-primary"
                    :class="{ 'invisible': selected !== item.id }"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    aria-hidden="true"
                >
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" />
                </svg>

                <span
                    class="pointer-events-none absolute -inset-px rounded-lg"
                    :class="[
                        selected === item.id ? 'border border-primary' : 'border-2 border-transparent'
                    ]"
                    aria-hidden="true"
                ></span>
            </div>
        </RadioGroup>

        <div class="mt-2" v-if="errors && errors.length">
            <input-error v-for="error in errors" :key="error">{{ error }}</input-error>
        </div>
    </div>
</template>
