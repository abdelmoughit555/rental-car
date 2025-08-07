<script setup lang="ts">
import { cn } from '@/lib/utils';
import { ref, watch, defineEmits } from 'vue'
import { Slider } from '@/Components/shadcn/ui/slider'

const props = defineProps({
    modelValue: {
        type: Array,
        default: [50],
    },
})

const emit = defineEmits(['update:modelValue'])
const value = ref(props.modelValue)

watch(value, (newVal) => {
    emit('update:modelValue', newVal);
});

watch(
    () => props.modelValue,
    (newVal) => {
        value.value = newVal;
    }
);
</script>

<template>
  <Slider
    v-model="value"
    :max="100"
    :step="1"
    :class="cn('w-3/5', $attrs.class ?? '')"
  />
</template>