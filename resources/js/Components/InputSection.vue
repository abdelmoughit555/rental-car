
<script setup>
    import { useSlots } from 'vue'
    const slots = useSlots()
    const props = defineProps({
        hasError: Boolean,
    })
</script>

<template>
    <div class="col-span-2 border rounded-md p-4"
        :class="{
            'border-destructive': hasError,
            'border-border': !hasError,
        }"
    >
        <div class="border-b border-border pb-4 flex">
            <div>
                <h3 class="font-medium text-primary">
                    <slot name="title" />
                </h3>
                <p class="text-sm text-muted-foreground">
                    <slot name="description" />
                </p>
            </div>
            <div class="flex-1 items-center justify-end flex flex-row" v-if="slots.aside">
                <slot name="aside" />
            </div>
        </div>

        <div class="mt-5 space-y-4">
            <slot name="content" />
        </div>
    </div>
</template>