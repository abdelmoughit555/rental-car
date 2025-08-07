<script setup>
import { computed, useSlots } from 'vue';

const slots = useSlots();
const hasDescription = computed(() => !!slots.description)
</script>
<template>
    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between space-y-2 pt-4 gap-2">
        <div class="min-w-0">
            <h2 class="text-3xl font-bold tracking-tight max-w-4xl truncate">
                <slot name="title" />
            </h2>
            <p class="mt-2 text-sm text-muted-foreground" v-if="hasDescription">
                <slot name="description" />
            </p>
        </div>

        <div class="flex items-start md:items-center flex-col md:flex-row justify-start lg:justify-end flex-1 w-full gap-2">
            <slot name="aside" />
        </div>
    </div>
</template>