<script setup>
import EditCarLayout from '../Edit/EditCarLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DatePicker from '@/Components/DatePicker.vue';
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

const watchedFields = ['available_from', 'available_to']

const sectionErrors = computed(() => {
  if (!props.validation) return []
  return watchedFields.flatMap(field => props.validation?.availability[field] || []) || []
})

const hasSectionError = computed(() => sectionErrors.value.length > 0)

const save = () => {
    errors.value = []
    loading.value = true
    
    return axios.put(`/api/cars/${props.car.id}`, {
        available_from: car.value.available_from,
        available_to: car.value.available_to,
    }).then(() => {
        loading.value = false
        toast({
            title: 'Car updated',
            description: 'The car has been updated successfully',
            variant: 'success',
        })

        router.visit(`/cars/${car.value.id}/pricing`)
    }).catch((error) => {
        loading.value = false
        if (error.response.status === 422) {
            errors.value = error.response.data.errors
        }
    })
}
</script>

<template>
    <EditCarLayout :title="`Car Availability - ${car.title}`" :currentStep="3">
        <template #content>
            <div class="space-y-4">
                <Panel>
                    <template #title>
                        Set your availability
                    </template>
                    <template #description>
                        This is the date we will make your car available for bookings. We recommend you choose a date at least 2 weeks in the future to give you time to pitch your car to customers.
                    </template>
                    <template #aside>
                        <ValidationInfo v-if="hasSectionError" :errors="sectionErrors"/>
                    </template>
                    <template #content>
                        <div class="space-y-4">
                            <Alert type="info" show>
                                <template #title>Availability Guidelines</template>
                                <template #message>
                                    <ul class="list-disc">
                                        <li>
                                            Available From: Select the date you want to make your car available.
                                        </li>
                                        <li>
                                            Available To: Select the date you want to make your car unavailable.
                                        </li>
                                        <li>
                                            Available From must be before Available To.
                                        </li>
                                        <li>
                                            Available From and Available To must be at least 2 weeks in the future.
                                        </li>
                                    </ul>
                                </template>
                            </Alert>
                            <DatePicker class="w-full" v-model="car.available_from" placeholder="Select when you want to make your car available"
                            :errors="errors.available_from"
                            :hasError="errors.available_from != null">
                                <template #label>
                                    Available From
                                </template>
                            </DatePicker>
                            <DatePicker class="w-full" v-model="car.available_to" placeholder="Select when you want to make your car unavailable"
                            :errors="errors.available_to"
                            :hasError="errors.available_to != null">
                                <template #label>
                                    Available To
                                </template>
                            </DatePicker>
                        </div>
                    </template>
                </Panel>
            </div>
        </template>
        <template #footer>
            <PrimaryButton :loading="loading" @click="save">
                Set up Price
            </PrimaryButton>
        </template>
    </EditCarLayout>
</template>