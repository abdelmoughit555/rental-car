<script setup lang="ts">
import { Button } from '@/Components/shadcn/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/shadcn/ui/dropdown-menu'
import { useSlots } from 'vue'
const slots = useSlots()

defineProps({
	width: {
		type: String,
		default: 'w-56',
	},
	align: {
		type: String,
		default: 'end',
	},
})
</script>

<template>
	<DropdownMenu>
		<DropdownMenuTrigger as-child>
			<Button variant="outline">
				<slot name="trigger" />
			</Button>
		</DropdownMenuTrigger>

		<DropdownMenuContent :class="width" :align="align">
			<DropdownMenuLabel v-if="slots.label">
				<slot name="label" />
			</DropdownMenuLabel>

			<DropdownMenuSeparator v-if="slots.label" />

			<slot name="content" />
		</DropdownMenuContent>
	</DropdownMenu>
</template>