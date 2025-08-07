<script setup>
const props = defineProps({
    class: {
        type: String,
        default: 'size-8',
    },
});
</script>

<template>
    <svg xmlns="http://www.w3.org/2000/svg" :class="[props.class, 'animate-spin text-muted-foreground']" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.75v1.5m5.126.624L16 8m3.25 4h-1.5m-.624 5.126-1.768-1.768M12 16.75v2.5m-3.36-3.891-1.768 1.768M7.25 12h-2.5m3.891-3.358L6.874 6.874"></path>
    </svg>
</template>