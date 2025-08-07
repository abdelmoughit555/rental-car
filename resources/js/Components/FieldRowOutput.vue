<script setup>
    import InputHelp from '@/Components/InputHelp.vue';
    import InputLabel from '@/Components/InputLabel.vue';

    defineProps({
        id: {
            type: String,
            required: true,
        },
        hasPadding: {
            type: Boolean,
            default: true,
        },

        horizontal: {
            type: Boolean,
            default: false,
        },
    });
</script>

<template>
    <div class="flex justify-between gap-4 flex-col items-start"
        :class="{ 
            'p-6': hasPadding,
            'lg:flex-row lg:gap-8' : !horizontal,
            'lg:flex-col lg:gap-1' : horizontal,
        }"
    >
        <div>
            <InputLabel :for="id">
                <slot name="label" />
            </InputLabel>
            <InputHelp class="mt-1">
                <slot name="description" />
            </InputHelp>
        </div>
        <div class="w-full self-stretch"
            :class="{
                'lg:w-full' : horizontal,
                'lg:w-1/3' : !horizontal,
            }"
        >
            <slot name="field" />
        </div>
    </div>
</template>
