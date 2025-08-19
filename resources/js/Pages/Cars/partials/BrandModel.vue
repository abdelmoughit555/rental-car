<script setup>
import Panel from '@/Components/Panel.vue';
import SelectInput from '@/Components/SelectInput.vue';
import ValidationInfo from '@/Components/ValidationInfo.vue';
import { usePage } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    car: Object,
    errors: Object,
    validationErrors: Object
});

const brands = usePage().props.makes;

const carModels = ref([]);

watch(() => props.car.brand_id, (value) => {
    if (value) {
        carModels.value = usePage().props.carModels.filter(model => model.make_id === value)
    } else {
        carModels.value = []
        props.car.car_model_id = null;
    }
});

const watchedFields = ['brand_id', 'car_model_id']

const sectionErrors = computed(() => {
  if (!props.validationErrors) return []
  return watchedFields.flatMap(field => props.validationErrors[field] || [])
})

const hasSectionError = computed(() => sectionErrors.value.length > 0)
</script>
<template>
    <Panel>
        <template #title>Brand & Model</template>
        <template #description>
            Give your car a brand and model to help others find it easily.
        </template>
        <template #aside>
            <ValidationInfo v-if="hasSectionError" :errors="sectionErrors"/>
        </template>
        <template #content>
            <div class="space-y-4">
                <div>
                    <SelectInput class="w-full lg:w-auto" name="type" id="label" v-model="car.brand_id"
                    :items="[{
                        id: null,
                        name: 'Select a Brand',
                        disabled: true
                    }, ...brands]" placeholder="Brand" :errors="errors.brand_id" :hasError="errors.brand_id != null"
                    >
                        <template #label>
                            <p>Brand</p>
                        </template>
                    </SelectInput>
                </div>
                <div>
                    <SelectInput class="w-full lg:w-auto" name="type" id="label" v-model="car.car_model_id"
                    :items="[{
                        id: null,
                        name: 'Select a Model',
                        disabled: true
                    }, ...carModels]" placeholder="Model" :errors="errors.car_model_id" :hasError="errors.car_model_id != null"
                    >
                        <template #label>
                            <p>Model</p>
                        </template>
                    </SelectInput>
                </div>
            </div>
        </template>
    </Panel>
</template>