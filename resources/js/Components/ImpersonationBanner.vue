<script setup>
import { Button } from '@/Components/shadcn/ui/button'
import { usePage, router } from '@inertiajs/vue3'

const { impersonated, impersonator } = usePage().props

const stopImpersonation = () => {   
    router.visit(route('profile.impersonating'), {
        method: 'delete',
        preserveScroll: true
    })
}
</script>

<template>
    <div>
        <div class="bg-yellow-500 px-4 py-2 text-center">
            <div class="max-w-[1920px] mx-auto flex items-center justify-between">
                <div class="flex items-center space-x-2 text-white">
                    <span class="font-medium">
                        You are currently impersonating 
                        <span class="font-bold">{{ impersonated?.full_name || impersonated?.email }}</span>
                    </span>
                    <span class="text-yellow-200 text-sm">
                        (Original user: {{ impersonator?.full_name || impersonator?.email }})
                    </span>
                </div>
                <Button variant="outline"
                    @click="stopImpersonation"
                >
                    Stop Impersonation
                </Button>
            </div>
        </div>
    </div>
</template>