
<script setup>
import { useSlots } from 'vue';

const slots = useSlots();
</script>

<template>
    <div class="mx-auto">
		<div>
			<div class="text-center">
				<slot name="icon" />

				<h2 class="mt-3 text-base font-semibold text-primary"
					:class="{ 'mt-0': !slots.icon }"
				>
					<slot name="title" />
				</h2>
				<p class="mt-1 text-sm text-muted-foreground max-w-md mx-auto" v-if="slots.description">
					<slot name="description" />
				</p>
			</div>

			<div class="mt-6 flex justify-center" v-if="slots.actions">
				<slot name="actions" />
			</div>
		</div>
    </div>
  </template>