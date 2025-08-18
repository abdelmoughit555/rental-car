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
  }]" :currentStep="currentStep" :clickable="true" @update:currentStep="handleStepUpdate" />
</template>
