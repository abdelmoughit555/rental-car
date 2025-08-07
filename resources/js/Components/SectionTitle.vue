<template>
    <div class="md:col-span-1 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-medium">
                <slot name="title" />
            </h3>

            <p class="mt-1 text-sm text-gray-600 text-muted-foreground">
                <slot name="description" />
            </p>
        </div>

        <div>
            <slot name="aside" />
        </div>
    </div>
</template>
