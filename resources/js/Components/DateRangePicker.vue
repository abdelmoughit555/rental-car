<script setup lang="ts">
import { ref, computed, watch, onMounted, useSlots } from 'vue'
import moment from 'moment'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import { Button } from '@/Components/shadcn/ui/button'
import { Input } from '@/Components/shadcn/ui/input'
import { useTheme } from '@/Composables/useTheme'

const { isDark } = useTheme()

interface Props {
    modelValue?: {
        startDate?: string | null
        endDate?: string | null
    }
    name?: string
    opens?: 'left' | 'right' | 'center'
    disabled?: boolean
    hasError?: boolean
    placeholder?: string
    multiCalendars?: boolean
    presetDates?: Array<{ label: string, value: [Date, Date] }>
    autoApply?: boolean
    clearable?: boolean,
    inline?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: () => ({ startDate: null, endDate: null }),
    opens: 'right',
    disabled: false,
    hasError: false,
    placeholder: 'Select a date range...',
    multiCalendars: true,
    presetDates: () => [],
    autoApply: false,
    clearable: true,
    inline: false
})

const emit = defineEmits<{
    (e: 'update:modelValue', value: { startDate: Date | null, endDate: Date | null }): void
}>()

const slots = useSlots()
const hasLabel = computed(() => !!slots.label)

type DateRange = [Date, Date] | null
const currentValue = ref<DateRange>(null)

// Helper function to add ordinal suffix to day
const getOrdinalSuffix = (day: number): string => {
    if (day > 3 && day < 21) return 'th'
    switch (day % 10) {
        case 1: return 'st'
        case 2: return 'nd'
        case 3: return 'rd'
        default: return 'th'
    }
}

// Format date to readable format like "Feb 2nd, 2025"
const formatReadableDate = (date: Date): string => {
    const momentDate = moment(date)
    const day = momentDate.date()
    const month = momentDate.format('MMM')
    const year = momentDate.format('YYYY')
    return `${month} ${day}${getOrdinalSuffix(day)}, ${year}`
}

// Custom date formatter function for VueDatePicker
const customDateFormatter = (date: Date | Date[]): string => {
    if (!date) return props.placeholder
    
    if (Array.isArray(date) && date.length === 2) {
        const [startDate, endDate] = date
        if (startDate && endDate) {
            const formattedStart = formatReadableDate(startDate)
            const formattedEnd = formatReadableDate(endDate)
            return `${formattedStart} - ${formattedEnd}`
        }
    }
    
    return props.placeholder
}

watch(props.modelValue, (newValue) => {
    if (newValue?.startDate && newValue?.endDate) {
        currentValue.value = [
            new Date(newValue.startDate),
            new Date(newValue.endDate)
        ]
    } else {
        currentValue.value = null
    }
}, { immediate: true })

watch(currentValue, (newValue) => {
    if (newValue) {
        emit('update:modelValue', {
            startDate: newValue[0],
            endDate: newValue[1]
        })
    } else {
        emit('update:modelValue', {
            startDate: null,
            endDate: null
        })
    }
})

const handlePresetClick = (preset: { label: string, value: [Date, Date] }) => {
    currentValue.value = preset.value
}

onMounted(() => {
    if (props.modelValue?.startDate && props.modelValue?.endDate) {
        currentValue.value = [
            new Date(props.modelValue.startDate),
            new Date(props.modelValue.endDate)
        ]
    }
})
</script>

<template>
    <div class="flex flex-col gap-2">
        <div v-if="hasLabel" class="flex items-center justify-between">
            <slot name="label" />
        </div>

        <div class="flex flex-col gap-2">
            <div class="flex flex-wrap gap-2">
                <template v-for="preset in presetDates" :key="preset.label">
                    <Button
                        variant="outline"
                        size="sm"
                        @click="handlePresetClick(preset)"
                    >
                        {{ preset.label }}
                    </Button>
                </template>
            </div>

            <VueDatePicker
                v-model="currentValue"
                :name="name"
                :disabled="disabled"
                :placeholder="placeholder"
                :multi-calendars="multiCalendars"
                :auto-apply="autoApply"
                :clearable="clearable"
                :opens="opens"
                :dark="isDark"
                range
                :inline="inline"
                :teleport="true"
                :enable-time-picker="false"
                :format="customDateFormatter"
            >
                <template #dp-input="{ value, onInput, onEnter, onTab, onBlur, onKeypress, onPaste }">
                    <Input
                        :value="value"
                        :placeholder="placeholder"
                        :disabled="disabled"
                        :class="{ 'border-red-500': hasError }"
                        @input="onInput"
                        @keydown.enter="onEnter"
                        @keydown.tab="onTab"
                        @blur="onBlur"
                        @keypress="onKeypress"
                        @paste="onPaste"
                        readonly
                    />
                </template>
            </VueDatePicker>
        </div>
    </div>
</template>

<style>
/* Global styles to override the dark theme background */
.dp__theme_dark {
    background-color: hsl(var(--background)) !important;
}
</style>

