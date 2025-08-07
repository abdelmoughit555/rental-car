
<script setup>
    import Alert from '@/Components/Alert.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import { Link } from '@inertiajs/vue3';

    const props = defineProps({
        title: String,
        errors: Array,
        route: Object,
    })
</script>

<template>
    <Alert type="error">
        <template #title>
            {{ title }}
        </template>
        <template #message>
            <ul class="list-disc list-inside">
                <li v-for="error in errors" :key="error">
                    {{ error }}
                </li>
            </ul>
        </template>
        <template #route>
            <Link :href="route.href" v-if="route">
                <SecondaryButton>
                    {{ route.name }}
                </SecondaryButton>
            </Link>
        </template>
    </Alert>
</template>
