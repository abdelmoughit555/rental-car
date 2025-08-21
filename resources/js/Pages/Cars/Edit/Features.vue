<script setup>
import EditCarLayout from '../Edit/EditCarLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { useToast } from '@/Components/shadcn/ui/toast/use-toast';
import { computed, ref } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import Panel from '@/Components/Panel.vue';
import Alert from '@/Components/Alert.vue';
import ValidationInfo from '@/Components/ValidationInfo.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    car: Object,
    validation: Object
})

const errors = ref([]);

const loading = ref(false)
const { toast } = useToast()

const featureCategories = usePage().props.featureCategories;

const car = ref({
    ...props.car,
    features: props.car.features || []
});

// Helper function to get errors for a specific category
const getCategoryErrors = (categoryId) => {
  if (!props.validation?.features) return []
  return props.validation.features[categoryId] || []
}

// Helper function to check if a category has errors
const hasCategoryErrors = (categoryId) => {
  return getCategoryErrors(categoryId).length > 0
}

// Check if there are any validation errors across all categories
const hasAnyValidationErrors = computed(() => {
  return featureCategories.some(category => hasCategoryErrors(category.id))
})

// Computed property to show selected features count
const selectedFeaturesCount = computed(() => car.value.features.length)

// Helper function to check if a feature is selected
const isFeatureSelected = (featureId) => {
    return car.value.features.includes(featureId);
}

// Helper function to toggle feature selection
const toggleFeature = (featureId) => {
    const index = car.value.features.indexOf(featureId);
    if (index > -1) {
        // Remove feature if already selected
        car.value.features.splice(index, 1);
        console.log('Removed feature:', featureId, 'New features:', car.value.features);
    } else {
        // Add feature if not selected
        car.value.features.push(featureId);
        console.log('Added feature:', featureId, 'New features:', car.value.features);
    }
}

const save = () => {
    errors.value = []
    loading.value = true
    
    return axios.put(`/api/cars/${props.car.id}`, {
        features: car.value.features
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
    <EditCarLayout :title="`Car Features - ${car.title}`" :currentStep="5">
        <template #content>
            <div class="space-y-4">
                <!-- Debug info -->
                <Alert type="info" show>
                    <template #title>Selected Features</template>
                    <template #message>
                        You have selected {{ selectedFeaturesCount }} feature(s)
                    </template>
                </Alert>
                
                <Panel 
                    v-for="category in featureCategories" 
                    :key="category.id"
                >
                    <template #title>
                        {{ category.name }}
                    </template>
                    <template #description>
                        {{ category.description }}
                    </template>
                    <template #aside>
                        <ValidationInfo v-if="hasCategoryErrors(category.id)" :errors="getCategoryErrors(category.id)"/>
                    </template>
                    <template #content>
                        <div class="grid grid-cols-3 gap-4">
                            <div v-for="feature in category.features" :key="feature.id">
                                <InputLabel :for="feature.id">
                                    <Checkbox 
                                        :id="feature.id" 
                                        :checked="isFeatureSelected(feature.id)"
                                        @change="toggleFeature(feature.id)"
                                    />
                                    <span class="ml-4">
                                        {{ feature.name }}
                                    </span>
                                </InputLabel>
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