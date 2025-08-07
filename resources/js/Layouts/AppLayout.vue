<script setup>
import { Head } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import { Link } from '@inertiajs/vue3';
import { 
    User, 
    Heart, 
    Search, 
    ShoppingCart, 
    Phone, 
    Mail,
    ChevronDown
} from 'lucide-vue-next';

const props = defineProps({
    title: String,
});
</script>

<template>
    <div>
        <Head :title="title" />

        <div class="min-h-screen bg-background">
            <!-- Header -->
            <header class="border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60 sticky top-0 z-50">
                <div class="container mx-auto px-4">
                    <div class="flex h-16 items-center justify-between">
                        <!-- Logo -->
                        <Link href="/" class="flex items-center">
                            <ApplicationLogo class="h-8 w-auto" />
                        </Link>

                        <!-- Navigation -->
                        <nav class="hidden md:flex items-center space-x-8">
                            <Link href="/rent" class="text-sm font-medium hover:text-primary transition-colors">
                                Rent
                            </Link>
                            <Link href="/agencies" class="text-sm font-medium hover:text-primary transition-colors">
                                Agencies
                            </Link>
                            <Link href="/how-it-works" class="text-sm font-medium hover:text-primary transition-colors">
                                How it works
                            </Link>
                            <Link href="/reviews" class="text-sm font-medium hover:text-primary transition-colors">
                                Reviews
                            </Link>
                            
                            <!-- Services Dropdown -->
                            <div class="relative group">
                                <button class="flex items-center text-sm font-medium hover:text-primary transition-colors">
                                    Services
                                    <ChevronDown class="ml-1 h-4 w-4" />
                                </button>
                                <div class="absolute top-full left-0 mt-2 w-48 bg-background border rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                    <div class="py-2">
                                        <Link href="/services/insurance" class="block px-4 py-2 text-sm hover:bg-accent">
                                            Insurance
                                        </Link>
                                        <Link href="/services/delivery" class="block px-4 py-2 text-sm hover:bg-accent">
                                            Delivery
                                        </Link>
                                        <Link href="/services/support" class="block px-4 py-2 text-sm hover:bg-accent">
                                            24/7 Support
                                        </Link>
                                        <Link href="/services/verification" class="block px-4 py-2 text-sm hover:bg-accent">
                                            Agency Verification
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </nav>

                        <!-- Right side actions -->
                        <div class="flex items-center space-x-4">
                            <ThemeSwitcher />
                            
                            <!-- User actions -->
                            <div class="flex items-center space-x-2">
                                <button class="p-2 hover:bg-accent rounded-lg transition-colors">
                                    <Heart class="h-5 w-5" />
                                </button>
                                <button class="p-2 hover:bg-accent rounded-lg transition-colors">
                                    <Search class="h-5 w-5" />
                                </button>
                                <button class="p-2 hover:bg-accent rounded-lg transition-colors">
                                    <ShoppingCart class="h-5 w-5" />
                                </button>
                                <Link href="/login" class="flex items-center space-x-2 px-4 py-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors">
                                    <User class="h-4 w-4" />
                                    <span class="text-sm font-medium">Login</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main content -->
            <main>
                <slot />
            </main>

            <!-- Footer -->
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
        </div>
    </div>
</template>
