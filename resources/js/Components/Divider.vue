<template>
    <div class="relative">
        <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex items-center justify-between">
            <span class="bg-white pr-3 text-lg font-medium text-gray-900">
                <slot name="title" />
            </span>

            <slot name="buttons" />
        </div>
    </div>
</template>
