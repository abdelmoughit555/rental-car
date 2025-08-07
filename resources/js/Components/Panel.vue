<script setup>
import { 
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/Components/shadcn/ui/card'
import Loading from '@/Components/Loading.vue'
import { useSlots } from 'vue'

defineOptions({
    name: 'Panel',
})

const slots = useSlots()

const props = defineProps({
    loading: {
        type: Boolean,
        default: false,
    },
    contentClass: {
        type: String,
        default: '',
    },
})
</script>

<template>
    <Card class="border rounded-lg">
        <div class="flex flex-col lg:flex-row justify-between gap-x-4">
            <CardHeader>
                <CardTitle>
                    <slot name="title" />
                </CardTitle>
                <CardDescription>
                    <slot name="description" />
                </CardDescription>
            </CardHeader>
            <div class="flex-1 items-center justify-end flex flex-row gap-x-4 p-6" v-if="slots.aside">
                <slot name="aside" />
            </div>
        </div>
        <CardContent :class="props.contentClass">
            <Loading v-if="loading" class="mx-auto size-12 text-muted-foreground" />

            <div v-else>
                <slot name="content" />
            </div>
        </CardContent>
        <CardFooter v-if="!loading && slots.footer">
            <slot name="footer" />
        </CardFooter>
    </Card>
</template>