<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import DatePicker from '@/Components/DatePicker.vue';
import Badge from '@/Components/Badge.vue';
import Statbox from '@/Components/Statbox.vue';
import { 
    Shield, 
    Truck, 
    CreditCard, 
    Wrench, 
    CheckCircle, 
    Star,
    MapPin,
    Search,
    Filter,
    Car,
    Euro,
    Calendar,
    Users,
    Award,
    Phone,
    Mail,
    ArrowRight,
    Play,
    Clock,
    Zap,
    Building2,
    CalendarDays,
    CarFront,
    Key
} from 'lucide-vue-next';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

// Popular categories
const categories = [
    { name: 'SUV', count: '2,847', icon: Car },
    { name: 'Family car', count: '1,923', icon: Car },
    { name: 'Estate', count: '1,456', icon: Car },
    { name: 'City', count: '892', icon: Car },
    { name: 'Luxury', count: '654', icon: Car },
    { name: 'Electric', count: '1,234', icon: Car },
    { name: 'Sport', count: '567', icon: Car }
];

// Services
const services = [
    {
        title: 'Flexible Booking',
        description: 'Book for hours, days, or weeks',
        subtitle: 'No long-term commitments required',
        icon: CalendarDays
    },
    {
        title: 'Verified Agencies',
        description: 'All agencies are verified and insured',
        subtitle: 'Trusted partners across Europe',
        icon: Building2
    },
    {
        title: 'Instant Pickup',
        description: 'Pick up your car in minutes',
        subtitle: 'Digital keys and contactless pickup',
        icon: Key
    },
    {
        title: '24/7 Support',
        description: 'Round-the-clock customer support',
        subtitle: 'Help whenever you need it',
        icon: Shield
    }
];

// How it works steps
const steps = [
    {
        number: '01',
        title: 'Search & Choose',
        description: 'Browse thousands of cars from verified agencies. Filter by make, model, location, and availability.',
        image: 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=400&h=300&fit=crop'
    },
    {
        number: '02',
        title: 'Book & Pay',
        description: 'Select your dates, review the car details, and book instantly with secure payment.',
        image: 'https://images.unsplash.com/photo-1563720223185-11003d516935?w=400&h=300&fit=crop'
    },
    {
        number: '03',
        title: 'Pickup & Drive',
        description: 'Pick up your car at the agency location or get it delivered to your doorstep.',
        image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=400&h=300&fit=crop'
    }
];

// Trust features
const trustFeatures = [
    {
        icon: Shield,
        title: 'Fully Insured',
        subtitle: 'All rentals include comprehensive insurance',
        description: 'Every booking comes with full insurance coverage. You\'re protected against accidents, theft, and damage. No hidden fees or surprises.'
    },
    {
        icon: CheckCircle,
        title: 'Verified Agencies',
        subtitle: 'Only trusted partners',
        description: 'All agencies on our platform are thoroughly verified. We check their licenses, insurance, and customer reviews to ensure quality service.'
    },
    {
        icon: Award,
        title: 'Best Price Guarantee',
        subtitle: 'We match any competitor price',
        description: 'Found a better price elsewhere? We\'ll match it! Our price guarantee ensures you always get the best deal on car rentals.'
    }
];

// Featured cars
const featuredCars = [
    {
        name: 'BMW X5 2023',
        price: '€85/day',
        agency: 'Premium Motors',
        location: 'Berlin, Germany',
        image: 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=300&h=200&fit=crop',
        badge: 'Popular',
        rating: 4.8,
        reviews: 127
    },
    {
        name: 'Mercedes C-Class 2022',
        price: '€65/day',
        agency: 'City Car Rentals',
        location: 'Paris, France',
        image: 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=300&h=200&fit=crop',
        badge: 'New',
        rating: 4.9,
        reviews: 89
    },
    {
        name: 'Audi A4 2023',
        price: '€75/day',
        agency: 'Luxury Auto',
        location: 'Amsterdam, Netherlands',
        image: 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=300&h=200&fit=crop',
        badge: 'Featured',
        rating: 4.7,
        reviews: 156
    }
];

// Popular agencies
const popularAgencies = [
    {
        name: 'Premium Motors',
        location: 'Berlin, Germany',
        cars: 45,
        rating: 4.8,
        image: 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=200&h=150&fit=crop'
    },
    {
        name: 'City Car Rentals',
        location: 'Paris, France',
        cars: 32,
        rating: 4.7,
        image: 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=200&h=150&fit=crop'
    },
    {
        name: 'Luxury Auto',
        location: 'Amsterdam, Netherlands',
        cars: 28,
        rating: 4.9,
        image: 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=200&h=150&fit=crop'
    }
];
</script>

<template>
    <AppLayout title="Autorockin - Rent Cars from Trusted Agencies">
        <!-- Hero Section -->
        <section class="relative overflow-hidden bg-background py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-foreground sm:text-6xl">
                        Rent cars from trusted agencies.<br>
                        <span class="text-primary">Like Airbnb, but for cars.</span>
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-muted-foreground">
                        Choose from 15,000+ cars across 500+ verified agencies in Europe. 
                        Book with confidence - full insurance included.
                    </p>
                    
                    <!-- Search Form -->
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <div class="bg-card border rounded-xl p-6 shadow-lg max-w-2xl w-full">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-2">Location</label>
                                    <TextInput placeholder="Where do you need the car?" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Pickup</label>
                                    <DatePicker placeholder="When do you need it?" />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-2">Return</label>
                                    <DatePicker placeholder="When do you return it?" />
                                </div>
                                <div class="flex items-end">
                                    <PrimaryButton class="w-full">
                                        <Search class="mr-2 h-4 w-4" />
                                        Search Cars
                                    </PrimaryButton>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <p class="text-lg font-semibold text-primary">
                            15,000+ Cars Available
                        </p>
                        <p class="text-sm text-muted-foreground">From 500+ verified agencies across Europe</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust Features Section -->
        <section class="py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:max-w-none">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-12 lg:grid-cols-3">
                        <div v-for="feature in trustFeatures" :key="feature.title" class="flex flex-col">
                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary">
                                <component :is="feature.icon" class="h-6 w-6 text-primary-foreground" />
                            </div>
                            <div class="mt-6">
                                <h3 class="text-lg font-semibold text-foreground">{{ feature.title }}</h3>
                                <p class="mt-2 text-base text-muted-foreground">{{ feature.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Cars -->
        <section class="py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">Featured Cars</h2>
                    <p class="mt-4 text-lg leading-8 text-muted-foreground">
                        Handpicked vehicles from top agencies
                    </p>
                </div>
                <div class="mx-auto mt-12 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-12 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    <article v-for="car in featuredCars" :key="car.name" class="flex flex-col items-start">
                        <div class="relative w-full">
                            <img :src="car.image" :alt="car.name" class="aspect-[16/9] w-full rounded-2xl bg-muted object-cover sm:aspect-[2/1] lg:aspect-[3/2]" />
                            <Badge class="absolute top-4 left-4" :variant="car.badge === 'New' ? 'default' : car.badge === 'Popular' ? 'secondary' : 'outline'">
                                {{ car.badge }}
                            </Badge>
                            <div class="absolute top-4 right-4 flex items-center bg-background/90 backdrop-blur-sm rounded-full px-2 py-1">
                                <Star class="h-4 w-4 text-yellow-500 fill-current" />
                                <span class="text-sm font-medium ml-1">{{ car.rating }}</span>
                            </div>
                        </div>
                        <div class="max-w-xl">
                            <div class="mt-6 flex items-center gap-x-4 text-xs">
                                <time class="text-muted-foreground">{{ car.agency }}</time>
                                <span class="relative z-10 rounded-full bg-muted px-3 py-1.5 font-medium text-muted-foreground hover:bg-muted/80">
                                    {{ car.location }}
                                </span>
                            </div>
                            <div class="group relative">
                                <h3 class="mt-3 text-lg font-semibold leading-6 text-foreground group-hover:text-primary">
                                    <span class="absolute inset-0"></span>
                                    {{ car.name }}
                                </h3>
                                <p class="mt-5 line-clamp-3 text-sm leading-6 text-muted-foreground">{{ car.price }}</p>
                            </div>
                            <div class="relative mt-6 flex items-center gap-x-4">
                                <PrimaryButton class="w-full">
                                    Book Now
                                    <ArrowRight class="ml-2 h-4 w-4" />
                                </PrimaryButton>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <!-- Popular Agencies -->
        <section class="py-16 sm:py-20 bg-muted/30">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">Top Agencies</h2>
                    <p class="mt-4 text-lg leading-8 text-muted-foreground">
                        Trusted partners across Europe
                    </p>
                </div>
                <div class="mx-auto mt-12 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-12 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    <div v-for="agency in popularAgencies" :key="agency.name" class="flex flex-col items-start">
                        <div class="relative w-full">
                            <img :src="agency.image" :alt="agency.name" class="aspect-[16/9] w-full rounded-2xl bg-muted object-cover sm:aspect-[2/1] lg:aspect-[3/2]" />
                        </div>
                        <div class="max-w-xl">
                            <div class="mt-6 flex items-center gap-x-4 text-xs">
                                <span class="relative z-10 rounded-full bg-muted px-3 py-1.5 font-medium text-muted-foreground hover:bg-muted/80">
                                    {{ agency.location }}
                                </span>
                            </div>
                            <div class="group relative">
                                <h3 class="mt-3 text-lg font-semibold leading-6 text-foreground group-hover:text-primary">
                                    <span class="absolute inset-0"></span>
                                    {{ agency.name }}
                                </h3>
                                <p class="mt-5 line-clamp-3 text-sm leading-6 text-muted-foreground">{{ agency.cars }} cars available</p>
                            </div>
                            <div class="relative mt-6 flex items-center gap-x-4">
                                <SecondaryButton class="w-full">
                                    View Cars
                                </SecondaryButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Popular Categories -->
        <section class="py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">Browse by Category</h2>
                    <p class="mt-4 text-lg leading-8 text-muted-foreground">
                        Find the perfect car for your needs
                    </p>
                </div>
                <div class="mx-auto mt-12 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-12 lg:mx-0 lg:max-w-none lg:grid-cols-7">
                    <div v-for="category in categories" :key="category.name" 
                         class="flex flex-col items-center text-center group cursor-pointer">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 group-hover:bg-primary/20 transition-colors">
                            <component :is="category.icon" class="h-6 w-6 text-primary" />
                        </div>
                        <h3 class="mt-4 text-sm font-semibold text-foreground group-hover:text-primary transition-colors">{{ category.name }}</h3>
                        <p class="mt-2 text-xs text-muted-foreground">{{ category.count }} cars</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="py-16 sm:py-20 bg-muted/30">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">How it works</h2>
                    <p class="mt-4 text-lg leading-8 text-muted-foreground">
                        Rent a car in 3 simple steps
                    </p>
                </div>
                <div class="mx-auto mt-12 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-12 lg:mx-0 lg:max-w-none lg:grid-cols-3">
                    <div v-for="step in steps" :key="step.number" class="flex flex-col items-start">
                        <div class="relative w-full">
                            <img :src="step.image" :alt="step.title" class="aspect-[16/9] w-full rounded-2xl bg-muted object-cover sm:aspect-[2/1] lg:aspect-[3/2]" />
                            <div class="absolute top-4 left-4 w-12 h-12 bg-primary rounded-full flex items-center justify-center text-primary-foreground font-bold text-lg">
                                {{ step.number }}
                            </div>
                        </div>
                        <div class="max-w-xl">
                            <div class="group relative">
                                <h3 class="mt-3 text-lg font-semibold leading-6 text-foreground group-hover:text-primary">
                                    <span class="absolute inset-0"></span>
                                    {{ step.title }}
                                </h3>
                                <p class="mt-5 line-clamp-3 text-sm leading-6 text-muted-foreground">{{ step.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">Why choose Autorockin?</h2>
                    <p class="mt-4 text-lg leading-8 text-muted-foreground">
                        Everything you need for a perfect car rental experience
                    </p>
                </div>
                <div class="mx-auto mt-12 grid max-w-2xl grid-cols-1 gap-x-8 gap-y-12 lg:mx-0 lg:max-w-none lg:grid-cols-4">
                    <div v-for="service in services" :key="service.title" class="flex flex-col">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                            <component :is="service.icon" class="h-6 w-6 text-primary" />
                        </div>
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-foreground">{{ service.title }}</h3>
                            <p class="mt-2 text-base text-muted-foreground">{{ service.description }}</p>
                            <p class="mt-2 text-sm text-muted-foreground">{{ service.subtitle }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 sm:py-20 bg-primary">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-primary-foreground sm:text-4xl">Ready to rent your perfect car?</h2>
                    <p class="mt-4 text-lg leading-8 text-primary-foreground/80">
                        15,000+ cars from 500+ agencies
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        <PrimaryButton variant="secondary" size="lg">
                            Browse All Cars
                            <ArrowRight class="ml-2 h-4 w-4" />
                        </PrimaryButton>
                        <SecondaryButton size="lg" variant="outline">
                            List Your Car
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-16 sm:py-20">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl lg:max-w-none">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-12 lg:grid-cols-2">
                        <div class="flex flex-col">
                            <h3 class="text-2xl font-bold tracking-tight text-foreground">Join thousands of happy renters</h3>
                            <div class="mt-6 space-y-4">
                                <div class="flex items-center space-x-3">
                                    <CheckCircle class="h-5 w-5 text-green-500" />
                                    <span class="font-medium text-foreground">Verified agencies only</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Shield class="h-5 w-5 text-blue-500" />
                                    <span class="font-medium text-foreground">Full insurance included</span>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <Award class="h-5 w-5 text-purple-500" />
                                    <span class="font-medium text-foreground">Best price guarantee</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <h4 class="text-lg font-semibold text-foreground mb-6">Need help?</h4>
                            <div class="space-y-6">
                                <div class="flex items-center space-x-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                        <Phone class="h-5 w-5 text-primary" />
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground">Call us</p>
                                        <p class="text-sm text-muted-foreground">+420 246 034 700</p>
                                        <p class="text-xs text-muted-foreground">24/7 support</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10">
                                        <Mail class="h-5 w-5 text-primary" />
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground">Email</p>
                                        <p class="text-sm text-muted-foreground">support@autorockin.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
