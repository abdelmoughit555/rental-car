<script setup lang="ts">
import { Stepper } from '@/Components/shadcn/ui/stepper'
import { defineProps, computed, ref, onMounted, onUnmounted, watch, nextTick } from 'vue';
import StepListItem from '@/Components/StepListItem.vue'

interface Step {
  step: number;
  title: string;
  description: string;
}

const props = defineProps({
  steps: {
    type: Array as () => Step[],
    required: true,
  },
  currentStep: {
    type: Number,
    required: true,
  },
  orientation: {
    type: String,
    required: true,
  }
})

const emit = defineEmits(['update:currentStep']);

const isMobile = ref(false);
const stepRefs = ref<HTMLElement[]>([]);

const checkScreenSize = () => {
  isMobile.value = window.innerWidth < 1024; // lg breakpoint
};

const computedOrientation = computed(() => {
  return isMobile.value ? 'horizontal' : 'vertical';
});

const stepperClasses = computed(() => {
  if (isMobile.value) {
    return 'flex w-max flex-row gap-4';
  } else {
    return 'mx-auto flex w-full max-w-md flex-col justify-start gap-10';
  }
});

function getStepState(stepNumber: number): string {
  if (stepNumber < props.currentStep) return 'completed';
  if (stepNumber === props.currentStep) return 'active';
  return 'inactive';
}

const scrollToCurrentStep = async () => {
  await nextTick();
  const currentStepIndex = props.steps.findIndex(step => step.step === props.currentStep);
  if (currentStepIndex !== -1 && stepRefs.value[currentStepIndex]) {
    const element = stepRefs.value[currentStepIndex];
    if (isMobile.value) {
      element.scrollIntoView({
        behavior: 'smooth',
        block: 'nearest',
        inline: 'center'
      });
    }
  }
};

watch(() => props.currentStep, () => {
  scrollToCurrentStep();
});

watch(isMobile, () => {
  setTimeout(() => {
    scrollToCurrentStep();
  }, 100);
});

onMounted(() => {
  checkScreenSize();
  window.addEventListener('resize', checkScreenSize);
  scrollToCurrentStep();
});

onUnmounted(() => {
  window.removeEventListener('resize', checkScreenSize);
});
</script>

<template>
  <Stepper :orientation="computedOrientation" :class="stepperClasses">
    <StepListItem 
      v-for="(step, index) in steps" 
      :key="step.step" 
      :step="step"
      :currentStep="currentStep"
      :state="getStepState(step.step)"
      :isLastStep="step.step === steps[steps.length - 1].step"
      @click="emit('update:currentStep', step.step)"
      :isMobile="isMobile"
      :ref="(el) => { if (el) stepRefs[index] = (el as any).$el || el }"
    />
  </Stepper>
</template>
