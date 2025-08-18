<script setup lang="ts">
import { Button } from '@/Components/shadcn/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuGroup,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/Components/shadcn/ui/dropdown-menu'
import { router } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next'
</script>

<template>
  <DropdownMenu>
    <DropdownMenuTrigger as-child>
      <Button variant="ghost" class="relative">
        <span class="hidden md:block">
          Create New
        </span>
        <span class="md:hidden">
          <Plus />
        </span>
      </Button>
    </DropdownMenuTrigger>
    <DropdownMenuContent class="w-64" align="end">
      <DropdownMenuGroup>
          <DropdownMenuItem @click="router.visit('/cars/create')" class="cursor-pointer">
            List a Car
          </DropdownMenuItem>
      </DropdownMenuGroup>
    </DropdownMenuContent>
  </DropdownMenu>
</template>
