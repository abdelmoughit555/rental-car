<script setup lang="ts">
import { Progress } from '@/Components/shadcn/ui/progress'
import { ref, watch, defineEmits } from 'vue'

const props = defineProps({
    modelValue: {
        type: Number,
        default: 1,
    },
})

const progress = ref(props.modelValue)

const emit = defineEmits(['update:modelValue'])

watch(progress, (newValue) => {
    emit('update:modelValue', newValue)
})

watch(
    () => props.modelValue,
    (newVal) => {
        progress.value = newVal;
    }
);
</script>

<template>
  <Progress v-model="progress" />
</template>