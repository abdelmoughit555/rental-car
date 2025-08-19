<script setup>
import Panel from '@/Components/Panel.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import ValidationInfo from '@/Components/ValidationInfo.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    car: Object,
    errors: Object,
    validationErrors: Object
});

const gearboxes = usePage().props.gearboxes;
const fuelTypes = usePage().props.fuelTypes;

const watchedFields = ['registration_number', 'year', 'engine_cc', 'power_hp', 'doors', 'seats', 'mileage_km', 'fuel_type_id', 'gearbox_id']

const sectionErrors = computed(() => {
  if (!props.validationErrors) return []
  return watchedFields.flatMap(field => props.validationErrors[field] || [])
})

const hasSectionError = computed(() => sectionErrors.value.length > 0)
</script>

<template>
    <Panel>
        <template #title>
            Registration & Specs
        </template>
        <template #description>
            Give your car a registration number and specs to help others find it easily.
        </template>
        <template #aside>
            <ValidationInfo v-if="hasSectionError" :errors="sectionErrors"/>
        </template>
        <template #content>
            <div class="space-y-4">
                <div>
                    <InputLabel for="registration_number">Registration Number</InputLabel>
                    <TextInput :hasError="errors.registration_number && errors.registration_number.length != 0" :errors="errors.registration_number" v-model="car.registration_number" class="mt-2" name="registration_number" id="registration_number" label="Registration Number of the Car" placeholder="Enter registration number of the car..."/>
                </div>
                <div>
                    <InputLabel for="year">Year</InputLabel>
                    <TextInput :hasError="errors.year && errors.year.length != 0" :errors="errors.year" v-model="car.year" class="mt-2" name="year" id="year" label="Year of the Car" placeholder="Enter year of the car..."/>
                </div>
                <div>
                    <InputLabel for="engine_cc">Engine CC</InputLabel>
                    <TextInput :hasError="errors.engine_cc && errors.engine_cc.length != 0" :errors="errors.engine_cc" v-model="car.engine_cc" class="mt-2" name="engine_cc" id="engine_cc" label="Engine CC of the Car" placeholder="Enter engine CC of the car..."/>
                </div>
                <div>
                    <InputLabel for="power_hp">Power (HP)</InputLabel>
                    <TextInput :hasError="errors.power_hp && errors.power_hp.length != 0" :errors="errors.power_hp" v-model="car.power_hp" class="mt-2" name="power_hp" id="power_hp" label="Power (HP) of the Car" placeholder="Enter power (HP) of the car..."/>
                </div>
                <div>
                    <InputLabel for="doors">Doors</InputLabel>
                    <TextInput :hasError="errors.doors && errors.doors.length != 0" :errors="errors.doors" v-model="car.doors" class="mt-2" name="doors" id="doors" label="Doors of the Car" placeholder="Enter doors of the car..."/>
                </div>
                <div>
                    <InputLabel for="seats">Seats</InputLabel>
                    <TextInput :hasError="errors.seats && errors.seats.length != 0" :errors="errors.seats" v-model="car.seats" class="mt-2" name="seats" id="seats" label="Seats of the Car" placeholder="Enter seats of the car..."/>
                </div>
                <div>
                    <InputLabel for="mileage_km">Mileage (KM)</InputLabel>
                    <TextInput :hasError="errors.mileage_km && errors.mileage_km.length != 0" :errors="errors.mileage_km" v-model="car.mileage_km" class="mt-2" name="mileage_km" id="mileage_km" label="Mileage (KM) of the Car" placeholder="Enter mileage (KM) of the car..."/>
                </div>
                <div>
                    <SelectInput :hasError="errors.fuel_type_id && errors.fuel_type_id.length != 0" :errors="errors.fuel_type_id" v-model="car.fuel_type_id" class="mt-2" name="fuel_type_id" id="fuel_type_id" label="Fuel Type of the Car" placeholder="Select fuel type of the car..."
                    :items="[{
                            id: null,
                            name: 'Select a Fuel Type',
                            disabled: true
                        }, ...fuelTypes]"
                    >
                        <template #label>
                            <p>Fuel Type</p>
                        </template>
                    </SelectInput>
                </div>
                <div>
                    <SelectInput :hasError="errors.gearbox_id && errors.gearbox_id.length != 0" :errors="errors.gearbox_id" v-model="car.gearbox_id" class="mt-2" name="gearbox_id" id="gearbox_id" label="Gearbox of the Car" placeholder="Select gearbox of the car..."
                    :items="[{
                        id: null,
                        name: 'Select a Gearbox',
                        disabled: true
                    }, ...gearboxes]"
                    >
                        <template #label>
                            <p>Gearbox</p>
                        </template>
                    </SelectInput>
                </div>
            </div>
        </template>
    </Panel>
</template>