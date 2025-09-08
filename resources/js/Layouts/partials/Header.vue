<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';
import Button from '@/Components/shadcn/ui/button/Button.vue';
import DropdownMenu from '@/Components/shadcn/ui/dropdown-menu/DropdownMenu.vue';
import DropdownMenuContent from '@/Components/shadcn/ui/dropdown-menu/DropdownMenuContent.vue';
import DropdownMenuGroup from '@/Components/shadcn/ui/dropdown-menu/DropdownMenuGroup.vue';
import DropdownMenuItem from '@/Components/shadcn/ui/dropdown-menu/DropdownMenuItem.vue';
import DropdownMenuLabel from '@/Components/shadcn/ui/dropdown-menu/DropdownMenuLabel.vue';
import DropdownMenuSeparator from '@/Components/shadcn/ui/dropdown-menu/DropdownMenuSeparator.vue';
import { DropdownMenuTrigger } from 'radix-vue';
import Artwork from '@/Components/Artwork.vue';
import LoginModal from './LoginModal.vue';
import { Link, router } from '@inertiajs/vue3';
import { 
    User, 
    Heart, 
    Search, 
    ShoppingCart, 
    ChevronDown,
    LogOut,
    Settings
} from 'lucide-vue-next';
import CreateNew from './CreateNew.vue';

const status = ref(false);

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
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
                        
                        <div>
                            <CreateNew />
                        </div>
                        <!-- Conditional User Section -->
                        <div v-if="$page.props.auth.user">
                            <!-- Authenticated User Dropdown -->
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <Button variant="ghost" class="h-10 w-10 p-0">
                                        <Artwork 
                                            :artworkUrl="$page.props.auth.user.profile_photo_url"
                                            :name="$page.props.auth.user.name"
                                            height="h-8"
                                            width="w-8"
                                        />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent class="w-64" align="end">
                                    <DropdownMenuLabel class="font-normal flex">
                                        <div class="flex flex-col space-y-1 py-2">
                                            <p class="font-medium leading-none">
                                                {{ $page.props.auth.user.name }}
                                            </p>
                                            <p class="text-sm leading-none text-muted-foreground">
                                                {{ $page.props.auth.user.email }}
                                            </p>
                                        </div>
                                    </DropdownMenuLabel>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuGroup>
                                        <Link :href="route('profile.show')">
                                            <DropdownMenuItem class="cursor-pointer">
                                                <Settings class="h-4 w-4 mr-2" />
                                                Profile
                                            </DropdownMenuItem>
                                        </Link>
                                    </DropdownMenuGroup>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem @click="logout" class="cursor-pointer">
                                        <LogOut class="h-4 w-4 mr-2" />
                                        Log out
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                        
                        <Link v-else :href="route('login')" as="button" method="get" class="inline-flex items-center space-x-2 px-4 py-2 rounded-md bg-primary text-primary-foreground hover:opacity-90">
                            <User class="h-4 w-4" />
                            <span class="text-sm font-medium">Login</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <LoginModal v-if="!$page.props.auth.user" :show="status" @close="status = false" />
</template>
