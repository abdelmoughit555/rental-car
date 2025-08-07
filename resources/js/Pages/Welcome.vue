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
    { name: 'SUV', count: '2,847', icon: Car, color: 'bg-blue-500' },
    { name: 'Family car', count: '1,923', icon: Car, color: 'bg-green-500' },
    { name: 'Estate', count: '1,456', icon: Car, color: 'bg-purple-500' },
    { name: 'City', count: '892', icon: Car, color: 'bg-orange-500' },
    { name: 'Luxury', count: '654', icon: Car, color: 'bg-red-500' },
    { name: 'Electric', count: '1,234', icon: Car, color: 'bg-indigo-500' },
    { name: 'Sport', count: '567', icon: Car, color: 'bg-pink-500' }
];

// Services
const services = [
    {
        title: 'Flexible Booking',
        description: 'Book for hours, days, or weeks',
        subtitle: 'No long-term commitments required',
        icon: CalendarDays,
        gradient: 'from-blue-500 to-blue-600'
    },
    {
        title: 'Verified Agencies',
        description: 'All agencies are verified and insured',
        subtitle: 'Trusted partners across Europe',
        icon: Building2,
        gradient: 'from-green-500 to-green-600'
    },
    {
        title: 'Instant Pickup',
        description: 'Pick up your car in minutes',
        subtitle: 'Digital keys and contactless pickup',
        icon: Key,
        gradient: 'from-purple-500 to-purple-600'
    },
    {
        title: '24/7 Support',
        description: 'Round-the-clock customer support',
        subtitle: 'Help whenever you need it',
        icon: Shield,
        gradient: 'from-orange-500 to-orange-600'
    }
];

// How it works steps
const steps = [
    {
        number: '01',
        title: 'Search & Choose',
        description: 'Browse thousands of cars from verified agencies. Filter by make, model, location, and availability.',
        image: 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=400&h=300&fit=crop',
        gradient: 'from-blue-500 to-blue-600'
    },
    {
        number: '02',
        title: 'Book & Pay',
        description: 'Select your dates, review the car details, and book instantly with secure payment.',
        image: 'https://images.unsplash.com/photo-1563720223185-11003d516935?w=400&h=300&fit=crop',
        gradient: 'from-green-500 to-green-600'
    },
    {
        number: '03',
        title: 'Pickup & Drive',
        description: 'Pick up your car at the agency location or get it delivered to your doorstep.',
        image: 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=400&h=300&fit=crop',
        gradient: 'from-purple-500 to-purple-600'
    }
];

// Trust features
const trustFeatures = [
    {
        icon: Shield,
        title: 'Fully Insured',
        subtitle: 'All rentals include comprehensive insurance',
        description: 'Every booking comes with full insurance coverage. You\'re protected against accidents, theft, and damage. No hidden fees or surprises.',
        gradient: 'from-green-500 to-green-600'
    },
    {
        icon: CheckCircle,
        title: 'Verified Agencies',
        subtitle: 'Only trusted partners',
        description: 'All agencies on our platform are thoroughly verified. We check their licenses, insurance, and customer reviews to ensure quality service.',
        gradient: 'from-blue-500 to-blue-600'
    },
    {
        icon: Award,
        title: 'Best Price Guarantee',
        subtitle: 'We match any competitor price',
        description: 'Found a better price elsewhere? We\'ll match it! Our price guarantee ensures you always get the best deal on car rentals.',
        gradient: 'from-purple-500 to-purple-600'
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
        <section class="relative min-h-[70vh] flex items-center bg-gradient-to-br from-gray-900 via-blue-900 to-indigo-900">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
                <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-2000"></div>
                <div class="absolute bottom-0 left-0 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse animation-delay-4000"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-6xl mx-auto">
                    <!-- Main Content -->
                    <div class="text-center text-white mb-12">
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                            Rent cars from trusted agencies.<br>
                            <span class="text-yellow-400">Like Airbnb, but for cars.</span>
                        </h1>
                        
                        <p class="text-lg md:text-xl mb-8 text-gray-300 max-w-3xl mx-auto">
                            Choose from 15,000+ cars across 500+ verified agencies in Europe. 
                            Book with confidence - full insurance included.
                        </p>

                        <!-- Stats -->
                        <div class="flex flex-wrap justify-center gap-8 mb-8">
                            <div class="flex items-center space-x-2">
                                <CheckCircle class="h-5 w-5 text-green-400" />
                                <span class="text-sm font-medium">15,000+ cars available</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <CheckCircle class="h-5 w-5 text-green-400" />
                                <span class="text-sm font-medium">500+ verified agencies</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <CheckCircle class="h-5 w-5 text-green-400" />
                                <span class="text-sm font-medium">Full insurance included</span>
                            </div>
                        </div>
                    </div>

                    <!-- Search Form -->
                    <div class="max-w-4xl mx-auto">
                        <div class="bg-white rounded-2xl p-6 md:p-8 shadow-2xl">
                            <div class="text-center mb-6">
                                <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">Find Your Perfect Car</h2>
                                <p class="text-gray-600">Search from thousands of cars across Europe</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Location -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <MapPin class="h-4 w-4 inline mr-1" />
                                        Location
                                    </label>
                                    <TextInput 
                                        placeholder="Where do you need the car?" 
                                        class="w-full"
                                    />
                                </div>

                                <!-- Pickup Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <Calendar class="h-4 w-4 inline mr-1" />
                                        Pickup
                                    </label>
                                    <DatePicker 
                                        placeholder="When do you need it?" 
                                        class="w-full"
                                    />
                                </div>

                                <!-- Return Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <Calendar class="h-4 w-4 inline mr-1" />
                                        Return
                                    </label>
                                    <DatePicker 
                                        placeholder="When do you return it?" 
                                        class="w-full"
                                    />
                                </div>

                                <!-- Search Button -->
                                <div class="flex items-end">
                                    <PrimaryButton class="w-full py-3">
                                        <Search class="mr-2 h-4 w-4" />
                                        Search Cars
                                    </PrimaryButton>
                                </div>
                            </div>

                            <!-- Quick Stats -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>15,000+ cars available</span>
                                    <span>500+ agencies</span>
                                    <span>Full insurance</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Trust Features Section -->
        <section class="py-16 bg-gradient-to-br from-gray-50 to-white">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="feature in trustFeatures" :key="feature.title" class="text-center group">
                        <div class="flex justify-center mb-4">
                            <div class="p-4 bg-gradient-to-r rounded-full shadow-lg group-hover:scale-110 transition-transform duration-300" :class="feature.gradient">
                                <component :is="feature.icon" class="h-8 w-8 text-white" />
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold mb-2">{{ feature.title }}</h3>
                        <p class="text-gray-600">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Cars -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Featured Cars</h2>
                    <p class="text-gray-600">Handpicked vehicles from top agencies</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="car in featuredCars" :key="car.name" 
                         class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden group">
                        <div class="relative">
                            <img :src="car.image" :alt="car.name" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" />
                            <Badge class="absolute top-4 left-4" :variant="car.badge === 'New' ? 'default' : car.badge === 'Popular' ? 'secondary' : 'outline'">
                                {{ car.badge }}
                            </Badge>
                            <div class="absolute top-4 right-4 flex items-center bg-white/90 backdrop-blur-sm rounded-full px-2 py-1">
                                <Star class="h-4 w-4 text-yellow-500 fill-current" />
                                <span class="text-sm font-medium ml-1">{{ car.rating }}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-semibold text-lg mb-2">{{ car.name }}</h3>
                            <p class="text-sm text-gray-600 mb-1">{{ car.agency }}</p>
                            <p class="text-sm text-gray-500 mb-3 flex items-center">
                                <MapPin class="h-4 w-4 mr-1" />
                                {{ car.location }}
                            </p>
                            <div class="flex items-center justify-between mb-4">
                                <p class="text-2xl font-bold text-blue-600">{{ car.price }}</p>
                                <p class="text-sm text-gray-500">{{ car.reviews }} reviews</p>
                            </div>
                            <PrimaryButton class="w-full">
                                Book Now
                                <ArrowRight class="ml-2 h-4 w-4" />
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Popular Agencies -->
        <section class="py-16 bg-gradient-to-br from-blue-50 to-indigo-50">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Top Agencies</h2>
                    <p class="text-gray-600">Trusted partners across Europe</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div v-for="agency in popularAgencies" :key="agency.name" 
                         class="bg-white rounded-xl p-6 hover:shadow-lg transition-shadow duration-300 group">
                        <div class="flex items-center mb-4">
                            <img :src="agency.image" :alt="agency.name" class="w-16 h-16 rounded-lg object-cover mr-4" />
                            <div>
                                <h3 class="font-semibold text-lg">{{ agency.name }}</h3>
                                <p class="text-sm text-gray-600">{{ agency.location }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <Star class="h-4 w-4 text-yellow-500 fill-current mr-1" />
                                <span class="text-sm font-medium">{{ agency.rating }}</span>
                            </div>
                            <span class="text-sm text-gray-600">{{ agency.cars }} cars</span>
                        </div>
                        <SecondaryButton class="w-full">
                            View Cars
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </section>

        <!-- Popular Categories -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Browse by Category</h2>
                    <p class="text-gray-600">Find the perfect car for your needs</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                    <div v-for="category in categories" :key="category.name" 
                         class="bg-white rounded-xl p-4 text-center hover:shadow-lg transition-all duration-300 cursor-pointer group hover:-translate-y-1 border">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300" :class="category.color">
                            <component :is="category.icon" class="h-6 w-6 text-white" />
                        </div>
                        <h3 class="font-semibold mb-1">{{ category.name }}</h3>
                        <p class="text-sm text-gray-600">{{ category.count }} cars</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="py-16 bg-gradient-to-br from-gray-50 to-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">How it works</h2>
                    <p class="text-gray-600 max-w-2xl mx-auto">Rent a car in 3 simple steps</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="step in steps" :key="step.number" class="text-center group">
                        <div class="relative mb-6">
                            <img :src="step.image" :alt="step.title" class="w-full h-48 object-cover rounded-xl shadow-lg group-hover:scale-105 transition-transform duration-300" />
                            <div class="absolute top-4 left-4 w-12 h-12 bg-gradient-to-r rounded-full flex items-center justify-center text-white font-bold text-lg" :class="step.gradient">
                                {{ step.number }}
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">{{ step.title }}</h3>
                        <p class="text-gray-600">{{ step.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Why choose Autorockin?</h2>
                    <p class="text-lg text-gray-600">Everything you need for a perfect car rental experience</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div v-for="service in services" :key="service.title" 
                         class="bg-white rounded-xl p-6 hover:shadow-xl transition-all duration-300 group hover:-translate-y-1 border">
                        <div class="w-12 h-12 bg-gradient-to-r rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300" :class="service.gradient">
                            <component :is="service.icon" class="h-6 w-6 text-white" />
                        </div>
                        <h3 class="font-semibold mb-2">{{ service.title }}</h3>
                        <p class="text-sm text-gray-600 mb-1">{{ service.description }}</p>
                        <p class="text-xs text-gray-500">{{ service.subtitle }}</p>
                    </div>
                </div>

                <!-- Additional services -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="bg-white rounded-xl p-6 shadow-lg border">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3">
                                <Shield class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="font-semibold">Full Insurance</h3>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <CheckCircle class="h-4 w-4 text-green-500 mr-2" />
                                Comprehensive coverage included
                            </li>
                            <li class="flex items-center">
                                <CheckCircle class="h-4 w-4 text-green-500 mr-2" />
                                No hidden fees or charges
                            </li>
                        </ul>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-lg border">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                                <Clock class="h-5 w-5 text-white" />
                            </div>
                            <h3 class="font-semibold">24/7 Support</h3>
                        </div>
                        <ul class="space-y-2 text-sm text-gray-600">
                            <li class="flex items-center">
                                <CheckCircle class="h-4 w-4 text-green-500 mr-2" />
                                Round-the-clock assistance
                            </li>
                            <li class="flex items-center">
                                <CheckCircle class="h-4 w-4 text-green-500 mr-2" />
                                Emergency roadside support
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 text-white relative overflow-hidden">
            <!-- Background decoration -->
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-white/10 rounded-full translate-x-1/2 translate-y-1/2"></div>
            
            <div class="container mx-auto px-4 text-center relative z-10">
                <h2 class="text-3xl font-bold mb-4">Ready to rent your perfect car?</h2>
                <p class="text-xl mb-8 text-yellow-300">15,000+ cars from 500+ agencies</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <PrimaryButton variant="secondary" size="lg" class="bg-white text-blue-600 hover:bg-gray-100">
                        Browse All Cars
                        <ArrowRight class="ml-2 h-4 w-4" />
                    </PrimaryButton>
                    <SecondaryButton size="lg" class="border-white text-white hover:bg-white hover:text-blue-600">
                        List Your Car
                    </SecondaryButton>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="text-center md:text-left">
                        <h3 class="text-2xl font-bold mb-4">Join thousands of happy renters</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <CheckCircle class="h-5 w-5 text-white" />
                                </div>
                                <span class="font-medium">Verified agencies only</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <Shield class="h-5 w-5 text-white" />
                                </div>
                                <span class="font-medium">Full insurance included</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <Award class="h-5 w-5 text-white" />
                                </div>
                                <span class="font-medium">Best price guarantee</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-6">
                        <h4 class="font-semibold mb-4 text-lg">Need help?</h4>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                    <Phone class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <p class="font-medium">Call us</p>
                                    <p class="text-sm text-gray-600">+420 246 034 700</p>
                                    <p class="text-xs text-gray-500">24/7 support</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg flex items-center justify-center">
                                    <Mail class="h-5 w-5 text-white" />
                                </div>
                                <div>
                                    <p class="font-medium">Email</p>
                                    <p class="text-sm text-gray-600">support@autorockin.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
