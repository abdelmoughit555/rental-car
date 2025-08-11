<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { Phone, Mail } from 'lucide-vue-next';
</script>

<template>
    <footer class="bg-muted border-t">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company info -->
                <div class="space-y-4">
                    <ApplicationLogo class="h-8 w-auto" />
                    <p class="text-sm text-muted-foreground">
                        Like Airbnb, but for cars. Rent from trusted agencies across Europe.
                    </p>
                </div>

                <!-- Quick links -->
                <div class="space-y-4">
                    <h3 class="font-semibold">Quick Links</h3>
                    <div class="space-y-2">
                        <Link href="/rent" class="block text-sm text-muted-foreground hover:text-foreground">
                            Rent
                        </Link>
                        <Link href="/agencies" class="block text-sm text-muted-foreground hover:text-foreground">
                            Agencies
                        </Link>
                        <Link href="/how-it-works" class="block text-sm text-muted-foreground hover:text-foreground">
                            How it works
                        </Link>
                        <Link href="/reviews" class="block text-sm text-muted-foreground hover:text-foreground">
                            Reviews
                        </Link>
                    </div>
                </div>

                <!-- Services -->
                <div class="space-y-4">
                    <h3 class="font-semibold">Services</h3>
                    <div class="space-y-2">
                        <Link href="/services/insurance" class="block text-sm text-muted-foreground hover:text-foreground">
                            Insurance
                        </Link>
                        <Link href="/services/delivery" class="block text-sm text-muted-foreground hover:text-foreground">
                            Delivery
                        </Link>
                        <Link href="/services/support" class="block text-sm text-muted-foreground hover:text-foreground">
                            24/7 Support
                        </Link>
                        <Link href="/services/verification" class="block text-sm text-muted-foreground hover:text-foreground">
                            Agency Verification
                        </Link>
                    </div>
                </div>

                <!-- Contact -->
                <div class="space-y-4">
                    <h3 class="font-semibold">Contact</h3>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2 text-sm text-muted-foreground">
                            <Phone class="h-4 w-4" />
                            <span>+420 246 034 700</span>
                        </div>
                        <div class="flex items-center space-x-2 text-sm text-muted-foreground">
                            <Mail class="h-4 w-4" />
                            <span>sales@autorockin.com</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t mt-8 pt-8 text-center text-sm text-muted-foreground">
                <p>&copy; 2025 Autorockin. All rights reserved.</p>
            </div>
        </div>
    </footer>
</template>
