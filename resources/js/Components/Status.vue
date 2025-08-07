<template>
    <badge :variant="selectedStatus ? selectedStatus.variant : 'default'" :class="props.class">
        <slot />
        {{ selectedStatus ? selectedStatus.id : 'Error with Status' }}
    </badge>
</template>

<script setup>
import Badge from './Badge.vue'
import { computed, ref } from 'vue'

// Props definition
const props = defineProps({
    status: String,
    class: {
        type: String,
        default: 'py-1.5 px-3 rounded-lg'
    },
    variant: String,
})

// Data
const statuses = ref([
    { id: 'Enabled', variant: 'green' },
    { id: 'Disabled', variant: 'red' },
    { id: 'Pending', variant: 'yellow' },
    { id: 'Error', variant: 'red' },
])

// Computed properties
const selectedStatus = computed(() => {
    let status = statuses.value.filter(s => props.status === s.id)
    return status.length != 0 ? status[0] : null
})
</script>