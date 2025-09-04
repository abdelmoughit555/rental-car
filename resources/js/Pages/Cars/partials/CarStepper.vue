<script setup>
import StepList from '@/Components/StepList.vue';
import { usePage, router } from '@inertiajs/vue3'

const props = defineProps({
    currentStep: {
        type: Number,
        required: true,
    },
})

const carId = usePage().props.car ? usePage().props.car.id : null

const handleStepUpdate = (step) => {
  if (step === props.currentStep) return

  if(props.currentStep === 1) {
    return;
  } else if(step === 1) {
    return;
  }

  const routes = {
    1: '/cars/create',
    2: `/cars/${carId}/information`,
    3: `/cars/${carId}/availability`,
    4: `/cars/${carId}/pricing`,
    5: `/cars/${carId}/features`,
    6: `/cars/${carId}/images`,
  }

  router.visit(routes[step])
}

</script>
<template>
  <StepList orientation="vertical" :steps="[{
    step: 1,
    title: 'Create Car',
    description: 'Start by creating a new draft car, this will allow you to add more information later.',
  }, {
    step: 2,
    title: 'Car details',
    description: 'A few details about your car will help us personalize your experience',
  }, {
    step: 3,
    title: 'Availability',
    description: 'Set the availability of your car',
  }, {
    step: 4,
    title: 'Pricing',
    description: 'Set the price of your car',
  }, {
    step: 5,
    title: 'Features',
    description: 'Set the features of your car',
  }, {
    step: 6,
    title: 'Images',
    description: 'Set the images of your car',
  }]" :currentStep="currentStep" :clickable="true" @update:currentStep="handleStepUpdate" />
</template>
