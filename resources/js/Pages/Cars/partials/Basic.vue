<script setup>
import Panel from '@/Components/Panel.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue';
import ValidationInfo from '@/Components/ValidationInfo.vue';
import { computed } from 'vue';

const props = defineProps({
    car: Object,
    errors: Object,
    validationErrors: Object
});

const watchedFields = ['title', 'description']

const sectionErrors = computed(() => {
  if (!props.validationErrors) return []
  return watchedFields.flatMap(field => props.validationErrors[field] || [])
})

const hasSectionError = computed(() => sectionErrors.value.length > 0)
</script>

<template>
    <Panel>
        <template #title>
            Basic Information
        </template>
        <template #description>
            Give your car a title and description to help others find it easily.
        </template>
        <template #aside>
            <ValidationInfo v-if="hasSectionError" :errors="sectionErrors"/>
        </template>
        <template #content>
            <div class="space-y-4">
                <div>
                    <InputLabel for="title">Title</InputLabel>
                    <TextInput :hasError="errors.title && errors.title.length != 0" :errors="errors.title" v-model="car.title" class="mt-2" name="title" id="title" label="Title of the Car" placeholder="Enter title of the car..."/>
                </div>
                <div>
                    <InputLabel for="description">Description</InputLabel>
                    <Textarea :hasError="errors.description && errors.description.length != 0" :errors="errors.description" v-model="car.description" class="mt-2" name="description" id="description" label="Description of the Car" placeholder="Enter description of the car..."/>
                </div>
            </div>
        </template>
    </Panel>
</template>