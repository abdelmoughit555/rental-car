<script setup>
import { Head } from '@inertiajs/vue3';
import Header from './partials/Header.vue';
import Footer from './partials/Footer.vue';

const props = defineProps({
    title: String,
});
</script>

<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-background">
            <!-- Header -->
            <Header />

            <!-- Main content -->
            <main>
                <slot />
            </main>

            <!-- Footer -->
            <Footer />
        </div>
    </div>
</template>
