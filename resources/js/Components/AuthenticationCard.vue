<script setup>
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
    import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/Components/shadcn/ui/card';
    import Toaster from '@/Components/shadcn/ui/toast/Toaster.vue'
    import { usePage } from '@inertiajs/vue3';

    const entity = usePage().props.entity;
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="flex w-full max-w-sm flex-col gap-6">

            <div class="flex items-center gap-2 self-center font-medium">
                <ApplicationLogo class="h-16" />
            </div>

            <div class="flex flex-col gap-6">
                <Card>
                    <CardHeader class="text-center">
                        <CardTitle class="text-xl">
                            <slot name="title" />
                        </CardTitle>
                        <CardDescription>
                            <slot name="description" />
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <slot name="content" />
                    </CardContent>
                    <CardFooter>
                        <div class="w-full divide-x divide-border flex justify-center text-sm text-muted-foreground">
                            <a :href="entity.privacy_policy" class="block px-2" target="_blank" v-if="entity.privacy_policy">Privacy Policy</a>
                            <a :href="entity.terms_of_service" class="block px-2" target="_blank" v-if="entity.terms_of_service">Terms of Service</a>
                        </div>
                    </CardFooter>
                </Card>

                <Toaster />
            </div>
        </div>
    </div>
</template>
