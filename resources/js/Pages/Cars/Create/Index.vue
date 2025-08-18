<script setup>
import { ref } from 'vue';
import EditCarLayout from '../Edit/EditCarLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import axios from 'axios';
import Panel from '@/Components/Panel.vue';
import { router } from '@inertiajs/vue3';
import { useToast } from '@/Components/shadcn/ui/toast/use-toast'

const { toast } = useToast()

const loading = ref(false);
const errors = ref([]);
const form = ref({
    title: null,
    description: null
})

const create = () => {
    console.log('Form data:', form.value);
    loading.value = true;
    errors.value = []; // Clear previous errors
    
    axios.post('/api/cars', form.value)
        .then(({data}) => {
            console.log('Success response:', data);
            router.visit(`/cars/${data.car}/information`);
        })
        .catch(error => {
            errors.value = error.response.data.errors;
            toast({
                title: 'Oops, something went wrong',
                description: error.response.data.message,
                variant: 'destructive'
            })
        })
        .finally(() => {
            loading.value = false;
        });
}
</script>

<template>
    <EditCarLayout :title="`Create Car`" :currentStep="1">
        <template #content>
            <Panel>
                <template #title>Create Car</template>
                <template #description>
                    Set up a title, your car and the car's model so we can get started. You can change this later.
                </template>
                <template #content>
                    <form class="space-y-4 h-full overflow-y-auto" @submit.prevent="create">
                        <TextInput :hasError="errors.title && errors.title.length != 0" :errors="errors.title" v-model="form.title" name="title" id="title" label="Title of the Car"
                            :placeholder="`Enter title of the car...`"
                            @input="errors.title = null"
                        >
                            <template #label>Title</template>
                        </TextInput>

                        <Textarea v-model="form.description" placeholder="Enter description..." name="description" id="description" :errors="errors.description" :hasError="errors.description != null">
                            <template #label>
                                Description
                            </template>
                        </Textarea>

                        <div class="hidden">
                            <button type="submit" />
                        </div>
                    </form>
                </template>
            </Panel>
        </template>
        <template #footer>
            <PrimaryButton @click="create" :loading="loading">
                Create Car
            </PrimaryButton>
        </template>
    </EditCarLayout>
</template>
