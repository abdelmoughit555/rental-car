<script setup>
import { ref, computed, watch, onMounted, defineEmits, useSlots } from 'vue';
import moment from 'moment';
import InputLabel from './InputLabel.vue';
import InputError from './InputError.vue';
import InputHelp from './InputHelp.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import { useTheme } from '@/Composables/useTheme';

import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const { isDark } = useTheme()

const slots = useSlots()

const props = defineProps({
    modelValue: String,
    name: String,
    id: String,
    format: { type: String, default: 'YYYY-MM-DD' },
    placeholder: { type: String, default: 'Select a date...' },
    disabled: { type: Boolean, default: false },
    minDate: null,
    maxDate: null,
    clearable: { type: Boolean, default: false },
    hasError: { type: Boolean, default: false },
    errors: Array,
    helpText: String,
    timePicker: { type: Boolean, default: false },
    autoApply: { type: Boolean, default: true }
});

const emit = defineEmits(['update:modelValue']);

const currentValue = ref(null);

const hasLabel = computed(() => !!slots.label);

const containerClass = computed(() => {
    let className = "flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-0 disabled:cursor-not-allowed disabled:opacity-50";

    if (props.disabled) {
        className += " disabled:cursor-not-allowed disabled:opacity-50";
    }

    if (props.hasError) {
        className += " text-destructive placeholder-destructive placeholder:text-destructive focus:border-destructive focus:outline-none focus:ring-destructive border-destructive";
    } else {
        className += " border-input focus:border-ring";
    }

    return className;
});

const customDateFormatter = (date) => {
    const dateOutput = props.timePicker ? moment(date) : moment(date).startOf('day');
    return dateOutput.format(props.format);
};

const formattedValue = computed(() => {
    if (currentValue.value == null) {
        return null;
    }
    return customDateFormatter(currentValue.value);
});

watch(() => props.modelValue, (newValue) => {
    if (newValue) {
        currentValue.value = props.timePicker ? moment(newValue, props.format) : moment(newValue, props.format).startOf('day');
    } else {
        currentValue.value = null;
    }
});

watch(currentValue, () => {
    emit('update:modelValue', currentValue.value ? customDateFormatter(currentValue.value) : null);
});

onMounted(() => {
    if (props.modelValue) {
        currentValue.value = props.timePicker ? moment(props.modelValue) : moment(props.modelValue).startOf('day');
    }
});
</script>

<template>
    <div>
        <InputLabel :for="id" v-if="hasLabel">
            <slot name="label" />
        </InputLabel>

        <div :class="{ 'mt-2': hasLabel, 'relative': hasError }">
            <VueDatePicker :enable-time-picker="timePicker"
                v-model="currentValue"
                six-weeks="center"
                hide-input-icon
                :dark="isDark"
                :range="false"
                :clearable="clearable"
                :auto-apply="autoApply"
                :id="id"
                :name="name"
                :minDate="minDate"
                :maxDate="maxDate"
                :teleport="true"
                :week-start="0"
                :readonly="disabled"
            >
                <template #dp-input>
                    <input type="text" :value="formattedValue" :class="containerClass" :placeholder="placeholder" :id="id" :name="name"
                        :disabled="disabled"
                    />
                </template>
                <template #action-row="{ closePicker, selectDate }">
                    <div class="w-full flex items-center justify-end space-x-1">
                        <SecondaryButton paddingY="py-1" @clicked="closePicker">Cancel</SecondaryButton>
                        <PrimaryButton @clicked="selectDate" paddingY="py-1">Apply</PrimaryButton>
                    </div>
                </template>
            </VueDatePicker>

            <!-- <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3" v-if="hasError">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                        clip-rule="evenodd" />
                </svg>
            </div> -->
        </div>

        <div class="mt-2" v-if="errors && errors.length">
            <InputError v-for="error in errors" :key="error">{{ error }}</InputError>
        </div>

        <div v-if="helpText != null || clearable" class="mt-2 flex items-center justify-between w-full">
            <InputHelp v-if="helpText">
                {{ helpText }}
            </InputHelp>
        </div>
    </div>
</template>

<style>
/* Global styles to override the dark theme background */
.dp__theme_dark {
    background-color: hsl(var(--background)) !important;
}
</style>
