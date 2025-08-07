<script setup>
    import Loading from '@/Components/Loading.vue';
    import { Progress } from '@/Components/shadcn/ui/progress';

    const props = defineProps({
        batch: Object,
        title: String,
        description: String
    });
</script>

<template>
   <div class="bg-accent border border-border rounded-lg p-8 mx-auto flex items-start gap-4">
        <Loading class="size-12" />
        <div class="flex-1">
            <p class="font-semibold text-primary">{{ title }}</p>
            <p class="text-muted-foreground">
                {{ description }}
            </p>

            <div class="mt-4 flex flex-row items-center gap-6">
                <Progress v-model="batch.progress" class="w-full h-3 border" />
                <p class="text-muted-foreground">
                    {{ batch.progress }}%
                </p>
            </div>
        </div>
    </div>
</template>