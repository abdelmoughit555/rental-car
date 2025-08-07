
<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import {
  Tooltip,
  TooltipContent,
  TooltipProvider,
  TooltipTrigger,
} from '@/Components/shadcn/ui/tooltip'

const props = defineProps({
    value: {
        required: false,
    },
    copyable: {
        type: Boolean,
        default: false
    },
    link: {
        type: String,
        required: false,
    },
    linkType: {
        type: String,
        default: 'internal'
    },
    hasValue: {
        type: Boolean,
        default: true
    }
});

const copiedToClipboard = ref(false);

const copyToClipboard = () => {
    navigator.clipboard.writeText(props.value).then(() => {
        copiedToClipboard.value = true;

        setTimeout(() => {
            copiedToClipboard.value = false;
        }, 1500);
    });
};
</script>

<template>
    <dl class="sm:col-span-1">
        <dt class="text-sm font-medium text-muted-foreground">
            <slot name="label" />
        </dt>
        <dd class="mt-1 flex">
            <Link v-if="linkType == 'internal' && link" :href="link" class="text-indigo-600 font-bold">
                <slot name="value" />
            </Link>

            <a v-else-if="linkType == 'external' && link" :href="link" target="_blank" class="text-indigo-600 font-bold">
                <slot name="value" />
            </a>

            <slot name="value" v-else />

            <span v-if="!hasValue">
                --
            </span>

            <span v-if="copyable" class="ml-3 flex items-center">
                <TooltipProvider>
                    <Tooltip>
                        <TooltipTrigger asChild>
                            <button type="button" :class="{ 'text-muted-foreground hover:text-primary/80' : !copiedToClipboard, 'text-green-400' : copiedToClipboard }" class="focus:outline-none" @click="copyToClipboard">
                                <svg class="w-4 h-4" v-if="!copiedToClipboard" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z"></path><path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z"></path></svg>
                                <svg class="w-4 h-4" v-else fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </button>
                        </TooltipTrigger>
                        <TooltipContent>
                            {{ copiedToClipboard ? 'Copied to Clipboard' : 'Copy to Clipboard' }}
                        </TooltipContent>
                    </Tooltip>
                </TooltipProvider>
            </span>
        </dd>
    </dl>
</template>