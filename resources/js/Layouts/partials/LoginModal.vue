<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { Eye, EyeOff } from 'lucide-vue-next';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);
const loading = ref(false);

const togglePasswordVisibility = () => {
    showPassword.value = !showPassword.value;
};

const submit = () => {
    loading.value = true;
    
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post('/login', {
        onSuccess: () => {
            closeModal();
            form.reset();
        },
        onFinish: () => {
            loading.value = false;
            form.reset('password');
        },
    });
};

const handleGoogleLogin = () => {
    console.log('Google login clicked');
};

const closeModal = () => {
    emit('close');
};
</script>

<template>
    <Modal :show="show" @close="closeModal" max-width="sm">
        <div class="p-6">
            <!-- Header -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-foreground mb-2">
                    Welcome back
                </h2>
                <p class="text-muted-foreground">
                    Enter your credentials to access your account
                </p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <InputLabel for="email" value="Email" />
                    <div class="relative mt-1">
                        <TextInput
                            id="email"
                            name="email"
                            type="email"
                            v-model="form.email"
                            placeholder="Enter your email"
                            :errors="form.errors.email ? [form.errors.email] : []"
                            :hasError="!!form.errors.email"
                            required
                        />
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between">
                        <InputLabel for="password" value="Password" />
                        <a href="#" class="text-sm text-primary hover:underline">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative mt-1">
                        <TextInput
                            id="password"
                            name="password"
                            :type="showPassword ? 'text' : 'password'"
                            v-model="form.password"
                            placeholder="Enter your password"
                            :errors="form.errors.password ? [form.errors.password] : []"
                            :hasError="!!form.errors.password"
                            required
                        />
                        <button
                            type="button"
                            @click="togglePasswordVisibility"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <Eye v-if="!showPassword" class="h-5 w-5 text-muted-foreground hover:text-foreground" />
                            <EyeOff v-else class="h-5 w-5 text-muted-foreground hover:text-foreground" />
                        </button>
                    </div>
                </div>

                <div class="block">
                    <InputLabel class="flex items-center">
                        <Checkbox v-model:checked="form.remember" name="remember" />
                        <span class="ms-2 text-sm text-muted-foreground">Remember me</span>
                    </InputLabel>
                </div>

                <!-- Login Button -->
                <PrimaryButton
                    type="submit"
                    class="w-full justify-center"
                    :disabled="loading"
                >
                    <span v-if="loading">Signing in...</span>
                    <span v-else>Sign in</span>
                </PrimaryButton>

                <!-- Google Login Button -->
                <SecondaryButton
                    type="button"
                    class="w-full justify-center"
                    @click="handleGoogleLogin"
                >
                    <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </SecondaryButton>
            </form>

            <!-- Sign Up Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-muted-foreground">
                    Don't have an account?
                    <a href="#" class="text-primary hover:underline font-medium">
                        Contact us
                    </a>
                </p>
            </div>
        </div>
    </Modal>
</template>
