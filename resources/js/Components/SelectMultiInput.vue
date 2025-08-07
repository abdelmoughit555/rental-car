<script setup lang="ts">
import { Command, CommandInput, CommandList, CommandEmpty, CommandGroup, CommandItem } from '@/Components/shadcn/ui/command'
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
} from "@/Components/shadcn/ui/dropdown-menu";
import { Button } from "@/Components/shadcn/ui/button";
import { ChevronDown, Check } from "lucide-vue-next";
import { ref, computed } from "vue";

interface Props {
  items: Array<{
    id: Number | String
    name: String
    disabled?: boolean
  }>
  modelValue?: Array<Number | String>,
  title?: String,
  hasError?: Boolean,
  disabled?: boolean,
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: () => [],
  title: () => '',
  disabled: () => false
})

const emit = defineEmits(['update:modelValue'])

const updateValue = (itemId: Number | String) => {
  let newValue = [];
  if (props.modelValue.includes(itemId)) {
    newValue = props.modelValue.filter(id => id !== itemId);
  } else {
    newValue = [...props.modelValue, itemId];
  }

  emit('update:modelValue', newValue)
};

const selectedLabels = computed(() => {
  return props.items
    .filter((item) => props.modelValue.includes(item.id))
    .map((item) => item.name);
});

</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
        <Button
          variant="outline" 
          class="w-full justify-between h-10 px-3"
          :class="[
            {
              'text-destructive placeholder-destructive focus:border-destructive focus:outline-none focus:ring-destructive border-destructive': hasError,
            }
          ]"
          :disabled="disabled"
        >
            <div class="whitespace-nowrap overflow-hidden text-ellipsis">
              {{ 
                selectedLabels.length === 0 
                  ? 'Select items' 
                  : selectedLabels.length === 1 
                    ? selectedLabels[0] 
                    : `Selected ${selectedLabels.length} ${title}`
              }}
            </div>
            <ChevronDown class="ml-2 h-4 w-4 text-accent" />
        </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent class="w-[--radix-popper-anchor-width]">
            <Command>
            <CommandInput
                :placeholder="`Search ${title}...`" 
                class="rounded-md h-8 border-none"
            />
            <CommandList>
                <CommandEmpty>No results found.</CommandEmpty>
                <CommandGroup>
                    <CommandItem
                        v-for="(item, index) in props.items"
                        :key="index"
                        :value="item.name"
                        @select="() => updateValue(item.id)"
                        :disabled="item.disabled"
                    >
                        <div
                            class="mr-2 flex h-4 w-4 items-center justify-center rounded-sm border border-primary"
                            :class="modelValue.includes(item.id) ? 'bg-primary text-primary-foreground' : 'opacity-50 [&_svg]:invisible'"
                        >
                            <Check class="h-4 w-4" />
                        </div>
                        <span>{{ item.name }}</span>
                    </CommandItem>
                </CommandGroup>
            </CommandList>
        </Command>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
