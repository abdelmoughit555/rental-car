<script setup>
import EditCarLayout from '../Edit/EditCarLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useToast } from '@/Components/shadcn/ui/toast/use-toast';
import { computed, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import Panel from '@/Components/Panel.vue';
import Alert from '@/Components/Alert.vue';
import ValidationInfo from '@/Components/ValidationInfo.vue';

const props = defineProps({
    car: Object,
    validation: Object
})

const errors = ref([]);

const loading = ref(false)
const { toast } = useToast()

const car = ref(props.car);

const watchedFields = ['price_per_day']

const sectionErrors = computed(() => {
  if (!props.validation) return []
  return watchedFields.flatMap(field => props.validation?.price[field] || []) || []
})

const hasSectionError = computed(() => sectionErrors.value.length > 0)

const save = () => {
    errors.value = []
    loading.value = true
    
    return axios.put(`/api/cars/${props.car.id}`, {
        price_per_day: car.value.price_per_day,
    }).then(({data}) => {
        loading.value = false
        toast({
            title: 'Car updated',
            description: 'The car has been updated successfully',
            variant: 'success',
        })

        router.visit(`/cars/${car.value.id}/features`)
    }).catch((error) => {
        loading.value = false
        if (error.response.status === 422) {
            errors.value = error.response.data.errors
        }
    })
}
</script>

<template>
    <EditCarLayout :title="`Car Price - ${car.title}`" :currentStep="4">
        <template #content>
            <div class="space-y-4">
                <Panel>
                    <template #title>
                        Set your price
                    </template>
                    <template #description>
                        This is the price you want to charge for your car.
                    </template>
                    <template #aside>
                        <ValidationInfo v-if="hasSectionError" :errors="sectionErrors"/>
                    </template>
                    <template #content>
                        <div class="space-y-4">
                            <Alert type="info" show>
                                <template #title>Price Guidelines</template>
                                <template #message>
                                    <ul class="list-disc">
                                        <li>
                                            Price per day: Set the price you want to charge for your car.
                                        </li>
                                        <li>
                                            Price per day must be a number.
                                        </li>
                                        <li>
                                            Price per day must be greater than 0.
                                        </li>
                                    </ul>
                                </template>
                            </Alert>
                            <div>
                                <InputLabel for="price_per_day">Price per day (MAD)</InputLabel>
                                <TextInput type="number" step="0.01" min="0.01" :hasError="errors.price_per_day && errors.price_per_day.length != 0" :errors="errors.price_per_day" v-model="car.price_per_day" class="mt-2" name="price_per_day" id="price_per_day" label="Price for the Car (MAD)" placeholder="Enter price per day for the car (MAD)..."/>
                            </div>
                        </div>
                    </template>
                </Panel>
            </div>
        </template>
        <template #footer>
            <PrimaryButton :loading="loading" @click="save">
                Set up Features
            </PrimaryButton>
        </template>
    </EditCarLayout>
</template>