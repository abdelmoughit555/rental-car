<script setup>
import EditCarLayout from '../Edit/EditCarLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CarImageSection from '@/Components/CarImageSection.vue';
import Alert from '@/Components/Alert.vue';
import ErrorList from '@/Components/ErrorList.vue';
import ValidationInfo from '@/Components/ValidationInfo.vue';
import { useToast } from '@/Components/shadcn/ui/toast/use-toast';
import { ref, computed, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

const props = defineProps({
    car: Object,
    validation: Object
})

const errors = ref([]);
const car = ref(props.car)

// Ensure all sections are initialized with empty arrays
const uploadedFiles = ref({
    front_view: [],
    interior_dashboard: [],
    main_seats: [],
    back_seats_trunk: []
});

// Helper function to convert server media to client format
const convertServerMediaToClientFormat = (media) => {
    return {
        id: media.id,
        original_file_name: `${media.name}.${media.extension}`,
        file_name: media.name,
        file_extension: media.extension,
        size: media.size,
        type: media.type,
        directory: media.directory,
        section: media.directory.split('/').pop(),
        url: media.url,
        created_at: media.created_at,
        isFromServer: true
    };
};

// Load existing images from server on component mount
onMounted(() => {
    if (props.car.media) {
        // Group media by directory/section
        const mediaBySection = {};
        
        Object.keys(props.car.media).forEach(directory => {
            const sectionKey = directory.split('/').pop();
            if (sectionKey && uploadedFiles.value.hasOwnProperty(sectionKey)) {
                mediaBySection[sectionKey] = props.car.media[directory].map(convertServerMediaToClientFormat);
            }
        });
        
        // Update uploadedFiles with existing media
        Object.keys(mediaBySection).forEach(section => {
            uploadedFiles.value[section] = mediaBySection[section];
        });
    }
});

// Helper function to get errors for a specific section
const getSectionErrors = (sectionKey) => {
    if (!errors.value || !errors.value[`images.${sectionKey}`]) return [];
    return errors.value[`images.${sectionKey}`];
};

// Helper function to check if a section has errors
const hasSectionErrors = (sectionKey) => {
    return getSectionErrors(sectionKey).length > 0;
};

// Check if there are any validation errors
const hasAnyValidationErrors = computed(() => {
    if (!errors.value) return false;
    return Object.keys(errors.value).some(key => key.startsWith('images.'));
});

// Get all validation errors as a flat array
const allValidationErrors = computed(() => {
    if (!errors.value) return [];
    const errorArray = [];
    Object.keys(errors.value).forEach(key => {
        if (key.startsWith('images.')) {
            errorArray.push(...errors.value[key]);
        }
    });
    return errorArray;
});

const loading = ref(false)
const { toast } = useToast()

// Handle files updated from child components
const handleFilesUpdated = (section, files) => {
    if (section && files) {
        uploadedFiles.value[section] = files;
    }
};

const save = () => {
    errors.value = []
    loading.value = true
    
    return axios.put(`/api/cars/${props.car.id}`, {
        images: uploadedFiles.value
    }).then(({data}) => {
        loading.value = false
        toast({
            title: 'Car updated',
            description: 'The car has been updated successfully',
            variant: 'success',
        })

        router.visit(`/cars/${props.car.id}/features`)
    }).catch((error) => {
        loading.value = false
        if (error.response.status === 422) {
            errors.value = error.response.data.errors
        }
    })
}
</script>

<template>
    <EditCarLayout :title="`Car Images - ${car.title}`" :currentStep="6">
        <template #content>
            <div class="space-y-4">
                <!-- Display validation errors -->
                <ErrorList 
                    v-if="hasAnyValidationErrors"
                    title="Please fix the following errors:"
                    :errors="allValidationErrors"
                />
                
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Car Images
                    </h3>
                    
                    <Alert type="info" class="mb-6">
                        <template #title>Minimum Required Photos (3-4 images) per section</template>
                        <template #description>
                            These essential photos ensure users won't abandon your listing - they get the key info quickly!
                        </template>
                    </Alert>
                    
                    <!-- Photo Sections -->
                    <div class="space-y-6">
                        <!-- Front ¾ View Section -->
                        <div class="relative">
                            <CarImageSection
                                :key="'front_view'"
                                title="Front ¾ View"
                                description="Shows front + side, looks attractive. This is often the first impression photo."
                                sectionKey="front_view"
                                :number="1"
                                :maxUploads="4"
                                directory="car_images/front_view"
                                scope="front_view"
                                :existingFiles="uploadedFiles.front_view"
                                @filesUpdated="handleFilesUpdated"
                            />
                            <div v-if="hasSectionErrors('front_view')" class="absolute top-2 right-2">
                                <ValidationInfo :errors="getSectionErrors('front_view')" />
                            </div>
                        </div>

                        <!-- Interior Dashboard Section -->
                        <div class="relative">
                            <CarImageSection
                                :key="'interior_dashboard'"
                                title="Interior Dashboard"
                                description="Steering wheel, center console, and dashboard features."
                                sectionKey="interior_dashboard"
                                :number="2"
                                :maxUploads="4"
                                directory="car_images/interior_dashboard"
                                scope="interior_dashboard"
                                :existingFiles="uploadedFiles.interior_dashboard"
                                @filesUpdated="handleFilesUpdated"
                            />
                            <div v-if="hasSectionErrors('interior_dashboard')" class="absolute top-2 right-2">
                                <ValidationInfo :errors="getSectionErrors('interior_dashboard')" />
                            </div>
                        </div>

                        <!-- Main Seats Section -->
                        <div class="relative">
                            <CarImageSection
                                :key="'main_seats'"
                                title="Main Seats"
                                description="Usually the front seats, showing comfort and condition."
                                sectionKey="main_seats"
                                :number="3"
                                :maxUploads="4"
                                directory="car_images/main_seats"
                                scope="main_seats"
                                :existingFiles="uploadedFiles.main_seats"
                                @filesUpdated="handleFilesUpdated"
                            />
                            <div v-if="hasSectionErrors('main_seats')" class="absolute top-2 right-2">
                                <ValidationInfo :errors="getSectionErrors('main_seats')" />
                            </div>
                        </div>

                        <!-- Back Seats or Trunk Section -->
                        <div class="relative">
                            <CarImageSection
                                :key="'back_seats_trunk'"
                                title="Back Seats or Trunk Space"
                                description="Families care about trunk space, others about back seat comfort."
                                sectionKey="back_seats_trunk"
                                :number="4"
                                :maxUploads="4"
                                directory="car_images/back_seats_trunk"
                                scope="back_seats_trunk"
                                :existingFiles="uploadedFiles.back_seats_trunk"
                                @filesUpdated="handleFilesUpdated"
                            />
                            <div v-if="hasSectionErrors('back_seats_trunk')" class="absolute top-2 right-2">
                                <ValidationInfo :errors="getSectionErrors('back_seats_trunk')" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #footer>
            <div class="flex items-center justify-end w-full">
                <PrimaryButton :loading="loading" @click="save">
                    Review & Submit
                </PrimaryButton>
            </div>
        </template>
    </EditCarLayout>
</template>
