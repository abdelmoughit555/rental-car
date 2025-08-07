<script setup>
import { computed, useSlots } from 'vue';

import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/Components/shadcn/ui/card'

defineEmits(['submitted']);

const hasActions = computed(() => !! useSlots().actions);

const props = defineProps({
    contentClass: {
        type: String,
        default: 'p-6',
    },
});
</script>

<template>

    <form @submit.prevent="$emit('submitted')">
        <Card>
            <CardHeader class="rounded-t-md border-b">
                <CardTitle>
                    <slot name="title" />
                </CardTitle>
                <CardDescription>
                    <slot name="description" />
                </CardDescription>
            </CardHeader>
            <CardContent :class="contentClass">
                <slot name="form" />
            </CardContent>
            <CardFooter class="flex justify-end border-t" v-if="hasActions">
                <slot name="actions" />
            </CardFooter>
        </Card>
    </form>
</template>
