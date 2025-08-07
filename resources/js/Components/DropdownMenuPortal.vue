<script setup lang="ts">
import { DropdownMenuPortal } from '@/Components/shadcn/ui/dropdown-menu'
</script>

<template>
    <DropdownMenuPortal>
        <slot />
    </DropdownMenuPortal>
</template>