<script setup lang="ts">
    import { defineProps, computed } from 'vue';
    import { StepperItem, StepperSeparator, StepperTitle, StepperDescription, StepperTrigger } from '@/Components/shadcn/ui/stepper'
    import { Button } from '@/Components/shadcn/ui/button'
    import { Check, Circle, Dot } from 'lucide-vue-next'

    const props = defineProps({
        step: {
            type: Object,
            required: true,
        },

        currentStep: {
            type: Number,
            required: true,
        },

        isLastStep: {
            type: Boolean,
            default: false,
            required: true,
        },

        state: {
            type: String,
            required: true,
        },

        isMobile: {
            type: Boolean,
            required: true,
        }
    })

    const buttonVariant = computed(() => 
        props.state === 'completed' || props.state === 'active' ? 'default' : 'outline'
    );

    const buttonClass = computed(() => 
        props.state === 'active' ? 'ring-2 ring-ring ring-offset-2 ring-offset-background' : ''
    );

    const titleClass = computed(() => 
        props.state === 'active' ? 'text-primary' : props.state === 'inactive' ? 'text-muted-foreground' : ''
    );

    const descriptionClass = computed(() => 
        props.state === 'active' || props.state === 'completed' ? 'opacity-100' : 'opacity-50'
    );
    
    const separatorClass = computed(() => 
        props.isMobile  ? 
        'absolute left-[calc(50%+20px)] right-[calc(-50%+10px)] top-5 block h-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary' : 
        'absolute left-[18px] top-[38px] block h-[105%] w-0.5 shrink-0 rounded-full bg-muted group-data-[state=completed]:bg-primary'
    );
</script>

<template>
    <div class="w-[200px] lg:w-auto">
        <StepperItem class="relative flex flex-col lg:flex-row items-center lg:items-start gap-6 cursor-pointer text-center lg:text-left" :step="step.step">
            <StepperSeparator v-if="!props.isLastStep" :class="separatorClass" />

            <StepperTrigger asChild>
                <Button :variant="buttonVariant" 
                    :class="[buttonClass]"
                    class="z-10 rounded-full shrink-0"
                    size="icon" 
                    :disabled="false"
                >
                    <Check v-if="props.state === 'completed'" class="size-5" />
                    <Circle v-if="props.state === 'active'" />
                    <Dot v-if="props.state === 'inactive'" />
                </Button>
            </StepperTrigger>

            <div>
                <StepperTitle :class="titleClass">
                    {{ props.step.title }}
                </StepperTitle>
    
                <StepperDescription class="mt-1 text-muted-foreground opacity-50 text-xs lg:text-sm"
                    :class="descriptionClass"
                >
                    {{ props.step.description }}
                </StepperDescription>
            </div>
        </StepperItem>
    </div>
</template>