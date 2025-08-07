<script setup>
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link } from '@inertiajs/vue3';
import Badge from '@/Components/Badge.vue';
import {
    Tooltip,
    TooltipContent,
    TooltipProvider,
    TooltipTrigger
} from '@/Components/shadcn/ui/tooltip';
import { Info } from 'lucide-vue-next';

const props = defineProps({
    errors: Array,
    route: Object,
})
</script>

<template>
    <TooltipProvider>
        <Tooltip>
            <TooltipTrigger class="w-fit">
                <Badge variant="destructive">
                    <Info class="size-4 mr-2" />
                    Required
                </Badge>
            </TooltipTrigger>
            <TooltipContent>
                <div class="p-2">
                    <ul class="list-disc list-inside">
                        <li v-for="error in errors" :key="error">
                            {{ error }}
                        </li>
                    </ul>
                    <Link :href="route.href" v-if="route">
                        <SecondaryButton>
                            {{ route.name }}
                        </SecondaryButton>
                    </Link>
                </div>
            </TooltipContent>
        </Tooltip>
    </TooltipProvider>
</template>
