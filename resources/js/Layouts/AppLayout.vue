<script setup>
import { Head } from '@inertiajs/vue3';
import Header from './partials/Header.vue';
import Footer from './partials/Footer.vue';
import Toaster from '@/Components/shadcn/ui/toast/Toaster.vue';

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
            <main class="p-4 md:p-8 pt-8">
                <slot />
            </main>

            <!-- Footer -->
            <Footer />
        </div>
        
        <!-- Toast notifications -->
        <Toaster />
    </div>
</template>
