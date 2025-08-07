<script setup>
import { ref, watch, onMounted } from 'vue'
import { Switch } from '@/Components/shadcn/ui/switch'
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    id: {
        type: String,
        required: true
    },
    modelValue: {
        type: Boolean,
        default: false
    },
    disabled: {
        type: Boolean,
        default: false
    }
})

const emit = defineEmits(['update:modelValue'])

const enabled = ref(false)

watch(enabled, (newValue) => {
    emit('update:modelValue', newValue)
})

watch(() => props.modelValue, (newValue) => {
    enabled.value = newValue
})

onMounted(() => {
    enabled.value = props.modelValue
})
</script>

<template>
    <div class="flex items-center justify-between">
        <span class="flex flex-grow flex-col mr-2">
            <InputLabel :for="id">
                <slot name="label" />
            </InputLabel>

            <span class="text-sm text-muted-foreground mt-1" :id="id + '-description'">
                <slot name="description" />
            </span>
        </span>

        <Switch 
            :checked="enabled"
            @update:checked="enabled = $event"
            :disabled="disabled"
        />
    </div>
</template>