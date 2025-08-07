<script setup>
import { defineEmits } from 'vue';
import { TableHead } from '@/Components/shadcn/ui/table'
import { ChevronUp, ChevronDown, ChevronsUpDown } from 'lucide-vue-next'

const emit = defineEmits(['sort']);

const props = defineProps({
    id: {
        type: String,
        required: false
    },

    class: {
        type: String
    },

    sortable: {
        type: Boolean,
        default: false
    },

    sortingColumn: {
        type: String,
        default: null
    },

    firstColumn: {
        type: Boolean,
        default: false
    },

    sortDirection: {
        type: String,
        validator: (value) => {
            return ['asc', 'desc', null].includes(value);
        },
        default: null
    }
});

const sort = () => {
    if(props.sortDirection === null) {
        emit('sort', {
            direction: 'asc',
            column: props.id
        });
    } else if(props.sortDirection === 'asc') {
        emit('sort', {
            direction: 'desc',
            column: props.id
        });
    } else if(props.sortDirection === 'desc') {
        emit('sort', {
            direction: null,
            column: null
        });
    }
}
</script>

<template>
    <TableHead :class="class" :firstColumn="firstColumn">
        <button class="flex items-center gap-2" v-if="sortable" @click="sort">
            <slot />

            <ChevronsUpDown v-if="sortingColumn != id || sortDirection === null" class="w-4 h-4" />
            <ChevronUp v-if="sortDirection === 'asc' && sortingColumn == id" class="w-4 h-4" />
            <ChevronDown v-if="sortDirection === 'desc' && sortingColumn == id" class="w-4 h-4" />
        </button>

        <slot v-else />
    </TableHead>
</template>