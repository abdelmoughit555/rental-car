<template>
  <div class="p-8 space-y-8">
    <h1 class="text-3xl font-bold mb-6">Component Test Suite</h1>
    
    <!-- Test Card Component -->
    <div class="max-w-sm">
      <Card>
        <CardHeader>
          <CardTitle>shadcn/ui Card</CardTitle>
          <CardDescription>This is a test of the shadcn/ui card component</CardDescription>
        </CardHeader>
        <CardContent>
          <p>If you can see this styled card, shadcn/ui is working correctly!</p>
        </CardContent>
        <CardFooter>
          <Button>Test Button</Button>
        </CardFooter>
      </Card>
    </div>

    <!-- Test Alert Components -->
    <div class="space-y-4">
      <h2 class="text-2xl font-semibold">Alert Components</h2>
      
      <Alert type="success">
        <template #title>Success Alert</template>
        <template #message>This is a success message using the Alert component.</template>
      </Alert>

      <Alert type="error">
        <template #title>Error Alert</template>
        <template #message>This is an error message using the Alert component.</template>
      </Alert>

      <Alert type="warning">
        <template #title>Warning Alert</template>
        <template #message>This is a warning message using the Alert component.</template>
      </Alert>

      <Alert type="info">
        <template #title>Info Alert</template>
        <template #message>This is an info message using the Alert component.</template>
      </Alert>
    </div>

    <!-- Test BlankSlate Component -->
    <div class="space-y-4">
      <h2 class="text-2xl font-semibold">BlankSlate Component</h2>
      
      <div class="border rounded-lg p-8">
        <BlankSlate>
          <template #icon>
            <div class="mx-auto h-12 w-12 text-gray-400">
              <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
            </div>
          </template>
          <template #title>No items found</template>
          <template #description>Get started by creating your first item.</template>
          <template #actions>
            <Button>Create Item</Button>
          </template>
        </BlankSlate>
      </div>
    </div>

    <!-- Test AlertPopup (Modal) -->
    <div class="space-y-4">
      <h2 class="text-2xl font-semibold">AlertPopup Component</h2>
      
      <Button @click="showAlertPopup = true">Show Alert Popup</Button>
      
      <AlertPopup 
        :show="showAlertPopup" 
        type="success"
        @close="showAlertPopup = false"
      >
        <template #title>Success!</template>
        <template #description>Your action was completed successfully.</template>
      </AlertPopup>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Card from './shadcn/ui/card/Card.vue';
import CardHeader from './shadcn/ui/card/CardHeader.vue';
import CardTitle from './shadcn/ui/card/CardTitle.vue';
import CardDescription from './shadcn/ui/card/CardDescription.vue';
import CardContent from './shadcn/ui/card/CardContent.vue';
import CardFooter from './shadcn/ui/card/CardFooter.vue';
import Button from './shadcn/ui/button/Button.vue';
import Alert from './Alert.vue';
import AlertPopup from './AlertPopup.vue';
import BlankSlate from './BlankSlate.vue';

const showAlertPopup = ref(false);
</script>
