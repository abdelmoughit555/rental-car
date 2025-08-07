<script setup>
    import { Card, CardContent, CardHeader, CardTitle } from '@/Components/shadcn/ui/card';
</script>

<template>
    <Card>
        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">
                <slot name="title" />
            </CardTitle>
            <slot name="icon" />
        </CardHeader>
        <CardContent>
            <div class="text-2xl font-bold text-primary">
                <slot name="value" />
            </div>
            <p class="mt-1 text-xs text-muted-foreground">
                <slot name="description" />
            </p>
        </CardContent>
    </Card>
</template>