<script setup>
import { ref, reactive, nextTick } from 'vue';
import DialogModal from './DialogModal.vue';
import InputError from './InputError.vue';
import PrimaryButton from './PrimaryButton.vue';
import SecondaryButton from './SecondaryButton.vue';
import TextInput from './TextInput.vue';

const emit = defineEmits(['confirmed']);

defineProps({
    title: {
        type: String,
        default: 'Confirm Password',
    },
    content: {
        type: String,
        default: 'For your security, please confirm your password to continue.',
    },
    button: {
        type: String,
        default: 'Confirm',
    },
});

const confirmingPassword = ref(false);

const form = reactive({
    password: '',
    error: '',
    processing: false,
});

const passwordInput = ref(null);

const startConfirmingPassword = () => {
    axios.get(route('password.confirmation')).then(response => {
        if (response.data.confirmed) {
            emit('confirmed');
        } else {
            confirmingPassword.value = true;

            setTimeout(() => {
                if (passwordInput.value) {
                    passwordInput.value.focus();
                }
            }, 250);
        }
    });
};

const confirmPassword = () => {
    form.processing = true;

    axios.post(route('password.confirm'), {
        password: form.password,
    }).then(() => {
        form.processing = false;

        closeModal();
        nextTick().then(() => emit('confirmed'));

    }).catch(error => {
        form.processing = false;
        form.error = error.response.data.errors.password[0];
        if (passwordInput.value) {
            passwordInput.value.focus();
        }
    });
};

const closeModal = () => {
    confirmingPassword.value = false;
    form.password = '';
    form.error = '';
};
</script>

<template>
    <span>
        <span @click="startConfirmingPassword">
            <slot />
        </span>

        <DialogModal :show="confirmingPassword" @close="closeModal" maxWidth="lg">
            <template #title>
                {{ title }}
            </template>

            <template #description>
                {{ content }}
            </template>

            <template #content>
                <div>
                    <TextInput
                        ref="passwordInput"
                        id="password"
                        name="password"
                        v-model="form.password"
                        type="password"
                        class="block w-full"
                        placeholder="Password"
                        autocomplete="current-password"
                        @keyup.enter="confirmPassword"
                    />

                    <InputError v-if="form.error" class="mt-2">
                        {{ form.error }}
                    </InputError>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="closeModal">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="confirmPassword"
                >
                    {{ button }}
                </PrimaryButton>
            </template>
        </DialogModal>
    </span>
</template>
