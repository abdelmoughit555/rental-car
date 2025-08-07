<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue'
import {
  Tabs,
  TabsContent,
  TabsList,
  TabsTrigger,
} from '@/Components/shadcn/ui/tabs'
import Badge from '@/Components/Badge.vue'
import SelectInput from '@/Components/SelectInput.vue'

interface Tab {
    id: string;
    label: string;
    count?: number;
    icon?: string;
    imageUrl?: string;
}

const props = defineProps({
    id: {
        type: String,
        default: 'tabs'
    },

    tabs: {
        type: Array as () => Tab[],
        default: () => []
    },

    width: {
        type: String,
        default: 'w-full'
    },

    orientation: {
        type: String,
        default: 'horizontal'
    },

    modelValue: {
        type: String,
        default: ''
    }
})

onMounted(() => {
    if(window.location.hash) {
        const hash = window.location.hash.replace('#', '');
        if (hash && props.tabs.some(tab => tab.id === hash)) {
            activeTab.value = hash;
        }
    }
})

// Define a ref to hold the current active tab
const activeTab = ref(props.modelValue || props.tabs[0]?.id || '')

// Define emits to notify parent component of active tab changes
const emit = defineEmits(['update:modelValue'])

// Watch for changes in activeTab and emit an event
watch(activeTab, (newValue) => {
    if(activeTab.value != window.location.hash.replace('#', '')) {
        const newUrl = window.location.pathname + '#' + newValue;
        window.history.replaceState({}, '', newUrl);
    } else {
        window.location.hash = newValue;
    }

    emit('update:modelValue', newValue)
})

// Watch for changes in modelValue from parent
watch(() => props.modelValue, (newValue) => {
    activeTab.value = newValue
})

const gridWidth = computed(() => {
    if(props.orientation === 'vertical') {
        return 'grid-cols-1'
    }

    return {
        1 : 'grid-cols-1',
        2 : 'grid-cols-2',
        3 : 'grid-cols-3',
        4 : 'grid-cols-4',
        5 : 'grid-cols-5',
        6 : 'grid-cols-6',
        7 : 'grid-cols-7',
        8 : 'grid-cols-8',
    }[props.tabs.length]
})
</script>

<template>
    <div>
        <Tabs v-model="activeTab"
          :orientation="props.orientation"
          class="hidden lg:block"
        >
          <TabsList class="grid"
              :class="[gridWidth, width, props.orientation === 'vertical' ? 'grid' : 'block']"
              :orientation="props.orientation"
          >
            <TabsTrigger v-for="tab in props.tabs" :key="tab.id" :value="tab.id"
              :class="[props.orientation === 'vertical' ? 'justify-start' : '']"
              :orientation="props.orientation"
            >
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center">
                        <component :is="tab.icon" v-if="tab.icon" :size="16" class="mr-2" />
                        <img v-else-if="tab.imageUrl" :src="tab.imageUrl" :alt="tab.label" class="w-4 h-4 mr-2">
                        <span>{{ tab.label }}</span>
                    </div>
                    <Badge v-if="tab.count" variant="indigo" class="rounded-full px-2 py-0.5 ml-2 text-xs">
                        {{ tab.count }}
                    </Badge>
                </div>
            </TabsTrigger>
          </TabsList>
        </Tabs>
      
        <SelectInput v-model="activeTab" :id="props.id"
            :name="props.id"
            :items="props.tabs.map(tab => ({ id: tab.id, name: tab.label }))" 
            class="lg:hidden"
        />
    </div>
</template>