<script setup>
import EditCarLayout from '../Edit/EditCarLayout.vue';
import { useToast } from '@/Components/shadcn/ui/toast/use-toast';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Basic from '../partials/Basic.vue';
import BrandModel from '../partials/BrandModel.vue';
import Specs from '../partials/Specs.vue';

const props = defineProps({
    car: Object,
    validation: Object
})

const errors = ref([]);

const loading = ref(false)
const { toast } = useToast()

const car = ref(props.car);

const save = () => {
    errors.value = []
    loading.value = true
    
    return axios.put(`/api/cars/${props.car.id}`, {
        title: car.value.title,
        description: car.value.description,
        brand_id: car.value.brand_id,
        car_model_id: car.value.car_model_id,
        registration_number: car.value.registration_number,
        year: car.value.year,
        engine_cc: car.value.engine_cc,
        power_hp: car.value.power_hp,
        doors: car.value.doors,
        seats: car.value.seats,
        mileage_km: car.value.mileage_km,
        fuel_type_id: car.value.fuel_type_id,
        gearbox_id: car.value.gearbox_id,
    }).then(({data}) => {
        loading.value = false
        toast({
            title: 'Car updated',
            description: 'The car has been updated successfully',
            variant: 'success',
        })

        router.visit(`/cars/${car.value.id}/availability`)
    }).catch((error) => {
        loading.value = false
        if (error.response.status === 422) {
            errors.value = error.response.data.errors
        }
    })
}
</script>

<template>
    <EditCarLayout :title="`Car Information - ${car.title}`" :currentStep="2">
        <template #content>
            <div class="space-y-4">
                <Basic :car="car" :errors="errors" :validationErrors="validation.information" />
                <BrandModel :car="car" :errors="errors" :validationErrors="validation.information" />
                <Specs :car="car" :errors="errors" :validationErrors="validation.information" />
            </div>
        </template>
        <template #footer>
            <PrimaryButton :loading="loading" @click="save">
                Set up Availability
            </PrimaryButton>
        </template>
    </EditCarLayout>
</template>