<script setup>
import EditCarLayout from '../Edit/EditCarLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Panel from '@/Components/Panel.vue';
import FieldRowOutput from '@/Components/FieldRowOutput.vue';
import Badge from '@/Components/Badge.vue';
import ErrorList from '@/Components/ErrorList.vue';
import BlankSlate from '@/Components/BlankSlate.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { useToast } from '@/Components/shadcn/ui/toast/use-toast';
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { List, Image } from 'lucide-vue-next';

const props = defineProps({
    car: Object,
    validation: Object
})

const errors = ref([]);
const loading = ref(false);
const state = ref(null);
const { toast } = useToast();

const car = ref(props.car);

const groupedFeatures = computed(() => {
    if (!car.value.features || car.value.features.length === 0) {
        return {};
    }
    
    return car.value.features.reduce((groups, feature) => {
        const category = feature.category;
        if (!groups[category]) {
            groups[category] = [];
        }
        groups[category].push(feature);
        return groups;
    }, {});
});

const stepErrorMap = computed(() => {
    const steps = [
        {
            key: 'information',
            title: 'Step 1: Basic Information',
            errors: Object.values(props.validation.information || {}).flat(),
            route: {
                name: 'Basic Information',
                href: `/cars/${car.value.id}/information`,
            }
        },
        {
            key: 'availability',
            title: 'Step 2: Availability',
            errors: Object.values(props.validation.availability || {}).flat(),
            route: {
                name: 'Availability',
                href: `/cars/${car.value.id}/availability`,
            }
        },
        {
            key: 'pricing',
            title: 'Step 3: Pricing',
            errors: Object.values(props.validation.pricing || {}).flat(),
            route: {
                name: 'Pricing',
                href: `/cars/${car.value.id}/pricing`,
            }
        },
        {
            key: 'features',
            title: 'Step 4: Features',
            errors: Object.values(props.validation.features || {}).flat(),
            route: {
                name: 'Features',
                href: `/cars/${car.value.id}/features`,
            }
        },
        {
            key: 'media',
            title: 'Step 5: Images',
            errors: Object.values(props.validation.media || {}).flat(),
            route: {
                name: 'Images',
                href: `/cars/${car.value.id}/images`,
            }
        }
    ];

    return steps.filter(step => step.errors.length > 0);
});

const submit = () => {
    errors.value = [];
    loading.value = true;
    
    return axios.put(`/api/cars/${props.car.id}/submission`).then(({data}) => {
        loading.value = false;
        toast({
            title: 'Car Submitted',
            description: 'The car has been submitted for review successfully',
            variant: 'success',
        });

        state.value = null;
    }).catch((error) => {
        loading.value = false;
        state.value = null;
        if (error.response.status === 422) {
            errors.value = error.response.data.errors;
        }
    });
};
</script>

<template>
    <EditCarLayout :title="`Review & Submit - ${car.title}`" :currentStep="6">
        <template #content>
            <div class="space-y-4">
                <Panel v-if="!validation.valid">
                    <template #title>
                        Car Submission
                    </template>
                    <template #description>
                        Review the car information and submit it for review. If you need to make any changes, please go back to the previous steps.
                    </template>
                    <template #content>
                        <div class="space-y-1">
                            <ErrorList v-for="step in stepErrorMap" :key="step.key" :title="step.title" :errors="step.errors" :route="step.route" />
                        </div>
                    </template>
                </Panel>
                
                <Panel>
                    <template #title>
                        Basic Information
                    </template>
                    <template #description>
                        Review the basic car information before submitting.
                    </template>
                    <template #content>
                        <div class="grid grid-cols-2 gap-4">
                            <FieldRowOutput id="title" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Car Title</template>
                                <template #field>
                                    <p class="font-medium">{{ car.title }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="brand-model" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Brand & Model</template>
                                <template #field>
                                    <p>{{ car.brand?.name && car.car_model?.name ? `${car.brand.name} ${car.car_model.name}` : '--' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="year" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Year</template>
                                <template #field>
                                    <p>{{ car.year || '--' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="registration" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Registration Number</template>
                                <template #field>
                                    <p>{{ car.registration_number || '--' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="description" horizontal :hasPadding="false" class="border border-border rounded-md p-4 col-span-2">
                                <template #label>Description</template>
                                <template #field>
                                    <p class="text-sm text-gray-600">{{ car.description || '--' }}</p>
                                </template>
                            </FieldRowOutput>
                        </div>
                    </template>
                </Panel>

                <Panel>
                    <template #title>
                        Technical Specifications
                    </template>
                    <template #description>
                        Review the technical specifications of the car.
                    </template>
                    <template #content>
                        <div class="grid grid-cols-3 gap-4">
                            <FieldRowOutput id="engine" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Engine</template>
                                <template #field>
                                    <p>{{ car.engine_cc ? `${car.engine_cc}cc` : '--' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="power" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Power</template>
                                <template #field>
                                    <p>{{ car.power_hp ? `${car.power_hp} HP` : '--' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="doors" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Doors</template>
                                <template #field>
                                    <p>{{ car.doors || '--' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="seats" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Seats</template>
                                <template #field>
                                    <p>{{ car.seats || '--' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="fuel-type" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Fuel Type</template>
                                <template #field>
                                    <p>{{ car.fuel_type?.name || '--' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="gearbox" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Gearbox</template>
                                <template #field>
                                    <p>{{ car.gearbox?.name || '--' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="mileage" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Mileage</template>
                                <template #field>
                                    <p>{{ car.mileage_km ? `${car.mileage_km.toLocaleString()} km` : '--' }}</p>
                                </template>
                            </FieldRowOutput>
                        </div>
                    </template>
                </Panel>

                <Panel>
                    <template #title>
                        Availability & Pricing
                    </template>
                    <template #description>
                        Review the availability dates and pricing information.
                    </template>
                    <template #content>
                        <div class="grid grid-cols-2 gap-4">
                            <FieldRowOutput id="available-from" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Available From</template>
                                <template #field>
                                    <p>{{ car.available_from ? new Date(car.available_from).toLocaleDateString() : 'Not set' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="available-to" horizontal :hasPadding="false" class="border border-border rounded-md p-4">
                                <template #label>Available To</template>
                                <template #field>
                                    <p>{{ car.available_to ? new Date(car.available_to).toLocaleDateString() : 'Not set' }}</p>
                                </template>
                            </FieldRowOutput>

                            <FieldRowOutput id="price-per-day" horizontal :hasPadding="false" class="border border-border rounded-md p-4 col-span-2">
                                <template #label>Price Per Day</template>
                                <template #field>
                                    <p class="text-lg font-semibold text-green-600">
                                        {{ car.price_per_day ? `$${car.price_per_day}` : 'Not set' }}
                                    </p>
                                </template>
                            </FieldRowOutput>
                        </div>
                    </template>
                </Panel>

                <Panel>
                    <template #title>
                        Features
                    </template>
                    <template #description>
                        Review the selected features for this car.
                    </template>
                    <template #content>
                        <div v-if="!car.features || car.features.length === 0" class="py-8">
                            <BlankSlate>
                                <template #icon>
                                    <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <List class="w-6 h-6 text-gray-400" />
                                    </div>
                                </template>
                                <template #title>No features selected</template>
                                <template #description>Add features to highlight what makes your car special.</template>
                                <template #actions>
                                    <SecondaryButton @click="router.visit(`/cars/${car.id}/features`)">
                                        Go to Features Step
                                    </SecondaryButton>
                                </template>
                            </BlankSlate>
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="(features, category) in groupedFeatures" :key="category" class="border border-border rounded-md p-4">
                                <h4 class="font-medium mb-3 capitalize">{{ category || 'Other Features' }}</h4>
                                <div class="flex flex-wrap gap-2">
                                    <Badge v-for="feature in features" :key="feature.id" variant="blue" class="py-1 rounded-md">
                                        {{ feature.name }}
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </template>
                </Panel>

                <Panel>
                    <template #title>
                        Images
                    </template>
                    <template #description>
                        Review the uploaded images for this car.
                    </template>
                    <template #content>
                        <div v-if="!car.media || Object.keys(car.media).length === 0" class="py-8">
                            <BlankSlate>
                                <template #icon>
                                    <div class="mx-auto w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <Image class="w-6 h-6 text-gray-400" />
                                    </div>
                                </template>
                                <template #title>No images uploaded yet</template>
                                <template #description>Upload images to showcase your car from different angles.</template>
                                <template #actions>
                                    <SecondaryButton @click="router.visit(`/cars/${car.id}/images`)">
                                        Go to Images Step
                                    </SecondaryButton>
                                </template>
                            </BlankSlate>
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="(mediaGroup, section) in car.media" :key="section" class="border border-border rounded-md p-4">
                                <h4 class="font-medium mb-3 capitalize">{{ section.replace('car_images/', '').replace('_', ' ') }}</h4>
                                <div class="space-y-2">
                                    <div v-for="media in mediaGroup" :key="media.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                                <img
                                                    v-if="media.url"
                                                    :src="media.url"
                                                    :alt="`${media.name}.${media.extension}`"
                                                    class="w-full h-full object-cover"
                                                />
                                                <div v-else class="w-full h-full flex items-center justify-center">
                                                    <span class="text-gray-500 text-xs">
                                                        {{ media.extension?.toUpperCase() || 'FILE' }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    {{ media.name }}.{{ media.extension }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ media.size ? (media.size / 1000000).toFixed(2) : 'Unknown' }} MB
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </Panel>
            </div>
        </template>
        
        <template #footer>
            <div class="space-x-4">
                <SecondaryButton @click="router.visit(`/cars/${car.id}/images`)">
                    Back to Images
                </SecondaryButton>
                <PrimaryButton :disabled="!validation.valid" @click="state = 'confirm-submission'">
                    Submit Car for Review
                </PrimaryButton>
            </div>
            
            <ConfirmationModal maxWidth="xl" :show="state === 'confirm-submission'" type="warning" @close="state = null">
                <template #title>
                    Confirm Submission
                </template>
                <template #content>
                    Are you sure you want to submit this car for review? This will send it to our team for approval.
                </template>
                <template #footer>
                    <SecondaryButton @click="state = null">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton :loading="loading" @click="submit">
                        Submit Car
                    </PrimaryButton>
                </template>
            </ConfirmationModal>
        </template>
    </EditCarLayout>
</template>