<script setup>
import EditCarLayout from '../Edit/EditCarLayout.vue';
</script>

<template>
    <EditCarLayout :title="`Car Information`" :currentStep="2">
        <template #content>
            <Panel>
                <template #title>
                    Car Information
                </template>
            </Panel>
        </template>
    </EditCarLayout>
</template>