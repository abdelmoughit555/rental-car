<script setup>
import CarStepper from '../partials/CarStepper.vue';
import PageHeader from '@/Components/PageHeader.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DangerButton from '@/Components/DangerButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { ref } from 'vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { useToast } from '@/Components/shadcn/ui/toast/use-toast';

const { toast } = useToast();
import { ChevronDown, Trash } from 'lucide-vue-next';
import { Button } from '@/Components/shadcn/ui/button'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuItem,
    DropdownMenuTrigger
} from '@/Components/shadcn/ui/dropdown-menu'

const props = defineProps({
    title: String,
    currentStep: Number,
    car: {
        type: Object,
        default: () => ({})
    },
})

const status = ref('');
const loading = ref(false);
const open = ref(false);

const deleteCar = () => {
    loading.value = true;
    axios.delete(`/api/cars/${props.car.id}`).then(res => {
        toast({
            title: 'Car deleted',
            description: 'The car has been deleted successfully.',
            variant: 'success',
        })

        router.visit('/');
    }).catch(error => {
        toast({
            title: 'Car could not be deleted',
            description: error.message,
            variant: 'destructive',
        })
    }).finally(() => {
        status.value = '';
    });
}
</script>

<template>
    <AppLayout :title="title">
        <div class="container mx-auto">
            <PageHeader>
                <template #title>
                    {{ title }}
                </template>
                <template #description>
                    Edit the information for this car.
                </template>
                <template #aside v-if="props.car && props.car.id">
                    <DropdownMenu :open="open" @update:open="open = $event">
                        <DropdownMenuTrigger as-child>
                            <Button variant="outline">
                                Actions
                                <ChevronDown class="size-4 transition-transform"
                                    :class="open ? 'rotate-180' : ''"
                                />
                            </Button>
                        </DropdownMenuTrigger>

                        <DropdownMenuContent align="end" class="w-56">
                            <DropdownMenuLabel>Car Actions</DropdownMenuLabel>

                            <DropdownMenuSeparator />
                            <DropdownMenuItem @click="status = 'delete'" class="text-destructive" >
                                <Trash class="size-4 mr-2" />
                                Delete Car
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                    <ConfirmationModal :show="status == 'delete'" @close="status = ''" maxWidth="md" type="error">
                        <template #title>Delete Car</template>
                        <template #content>
                            Are you sure you want to delete this car?
                        </template>
                        <template #footer>
                            <SecondaryButton @click="status = ''">Cancel</SecondaryButton>
                            <DangerButton @click="deleteCar" :loading="loading">Delete</DangerButton>
                        </template>
                    </ConfirmationModal>
                </template>
            </PageHeader>

            <div class="mt-12 grid grid-cols-4 gap-4">
                <div class="col-span-4 lg:col-span-1 overflow-y-auto lg:overflow-y-visible pt-2 pb-5 lg:pt-0 lg:pb-0">
                    <CarStepper :currentStep="currentStep" />
                </div>
                <div class="col-span-4 lg:col-span-3">
                   <slot name="content" />
                </div>

                <div class="col-span-4 flex justify-end border-t pt-4 mt-4">
                    <slot name="footer" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
