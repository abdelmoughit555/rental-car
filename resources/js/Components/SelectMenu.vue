<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import InputError from './InputError.vue';
import LabelComponent from './InputLabel.vue';

const props = defineProps({
    id: {
        type: String,
        required: true
    },
    disabled: {
        type: Boolean,
        default: false
    },
    name: {
        type: String,
        required: true
    },
    items: {
        type: Array,
        required: true
    },
    hasError: {
        type: Boolean,
        default: false
    },
    errors: {
        type: Array,
        required: false
    },
    multiple: {
        type: Boolean,
        default: true
    },
    modelValue: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:modelValue']);

const showDropdown = ref(false);
const selectedItems = ref([]);

const chosenItems = computed(() => {
    return props.items.filter(item => selectedItems.value.includes(item.id));
});

onMounted(() => {
    if (props.modelValue && props.modelValue.length !== 0) {
        selectedItems.value = Array.isArray(props.modelValue) ? props.modelValue : [props.modelValue];
    }
});

watch(selectedItems, (newVal) => {
    emit('update:modelValue', newVal);
});

watch(() => props.modelValue, (newVal) => {
    selectedItems.value = newVal;
});

function handleBlur() {
    showDropdown.value = false;
}

function toggleDropdown() {
    if (props.disabled) {
        return;
    }
    showDropdown.value = !showDropdown.value;
}

function selectedItem(id) {
    if (props.disabled) {
        return;
    }

    if (props.multiple) {
        if (selectedItems.value.includes(id)) {
            selectedItems.value = selectedItems.value.filter(item => item !== id);
        } else {
            selectedItems.value = [...selectedItems.value, id];
        }
    } else {
        selectedItems.value = [id];
        toggleDropdown();
    }
}
</script>

<template>
    <div>
        <LabelComponent :for="id">
            <slot name="label" />
        </LabelComponent>
        <div class="relative mt-2">
            <button type="button" @click="toggleDropdown" :aria-expanded="showDropdown ? 'true' : 'false'"
                class="h-10 relative w-full cursor-default rounded-md border bg-background py-2 pl-3 pr-10 text-left shadow-sm text-sm leading-6
                    focus:ring-1 focus:ring-ring focus:border-ring
                "
                :class="[
                    {
                        'text-destructive placeholder-destructive focus:border-destructive focus:outline-none focus:ring-destructive border-destructive': hasError,
                        'disabled:cursor-not-allowed disabled:opacity-50': disabled,
                    }
                ]"
                aria-haspopup="listbox" aria-labelledby="listbox-label"
                :disabled="disabled"
            >
                <div class="flex items-center overflow-x-hidden"
                    v-if="chosenItems.length > 0 && chosenItems.length <= 2">
                    <div v-for="(chosenItem, index) in chosenItems" :key="chosenItem.id"
                        class="overflow-ellipsis flex-shrink-0 flex items-center whitespace-nowrap">
                        <img v-if="chosenItem.avatar" :src="chosenItem.avatar" :alt="chosenItem.name"
                            class="h-4 w-4 mr-1 flex-shrink-0 rounded-full">
                        {{ chosenItem.name }}
                        <span v-if="index + 1 != chosenItems.length" class="mr-1">, </span>
                    </div>
                </div>

                <div v-else-if="chosenItems.length > 2">
                    {{ chosenItems.length }} Selected Items
                </div>

                <span v-else class="text-muted-foreground">
                    Select an item...
                </span>
                <span class="z-20 pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                    <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M10 3a.75.75 0 01.55.24l3.25 3.5a.75.75 0 11-1.1 1.02L10 4.852 7.3 7.76a.75.75 0 01-1.1-1.02l3.25-3.5A.75.75 0 0110 3zm-3.76 9.2a.75.75 0 011.06.04l2.7 2.908 2.7-2.908a.75.75 0 111.1 1.02l-3.25 3.5a.75.75 0 01-1.1 0l-3.25-3.5a.75.75 0 01.04-1.06z"
                            clip-rule="evenodd" />
                    </svg>
                </span>
            </button>

            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3" v-if="hasError">
                <svg class="h-5 w-5 text-destructive" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            <transition enter-active-class="transition ease-out duration-100 transform" enter-class="opacity-0 scale-95"
                enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-75 transform"
                leave-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
                <ul class="absolute z-10 mt-1 max-h-64 w-full overflow-auto rounded-md bg-background text-base border border-input  shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm"
                    tabindex="-1" role="listbox" aria-labelledby="listbox-label" aria-activedescendant="listbox-option-3"
                    v-show="showDropdown" @blur="handleBlur">
                    <li class="group relative cursor-default select-none py-2 pl-3 pr-9 hover:bg-accent"
                        :class="[{
                                'rounded-t': index == 0,
                                'rounded-b': index + 1 == items.length
                            }]" id="listbox-option-0" role="option" v-for="(item, index) in items" :key="index"
                        @click="selectedItem(item.id)">
                        <div class="flex items-center">
                            <img v-if="item.avatar" :src="item.avatar" alt="" class="h-6 w-6 flex-shrink-0 rounded-full">
                            <span class="ml-3 block truncate">{{ item.name }}</span>
                        </div>

                        <span class="absolute inset-y-0 right-0 flex items-center pr-4" :class="{
                                'text-primary group-hover:text-white': !selectedItems.includes(item.id),
                                'text-primary': selectedItems.includes(item.id)
                            }" v-if="selectedItems.includes(item.id)">
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </li>
                </ul>
            </transition>
        </div>

        <div class="mt-2" v-if="errors && errors.length">
            <input-error v-for="error in errors" :key="error">{{ error }}</input-error>
        </div>
    </div>
</template>
