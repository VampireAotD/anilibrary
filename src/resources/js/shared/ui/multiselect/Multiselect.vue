<script setup lang="ts">
import { computed, ref } from 'vue';

import { CheckIcon, ChevronDown, XCircle } from 'lucide-vue-next';

import { cn } from '@/shared/helpers/tailwind';
import { Badge } from '@/shared/ui/badge';
import { Button } from '@/shared/ui/button';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
    CommandSeparator,
} from '@/shared/ui/command';
import { Popover, PopoverContent, PopoverTrigger } from '@/shared/ui/popover';
import { Separator } from '@/shared/ui/separator';

type Item = {
    label: string;
    value: string;
};

type Props = {
    items: Item[];
    placeholder?: string;
    maxCount?: number;
};

const { items, placeholder = 'Select items', maxCount = 3 } = defineProps<Props>();
const selectedItems = defineModel<string[]>({ default: [] });

const open = ref<boolean>(false);

const allItemsSelected = computed(() => selectedItems.value.length === items.length);

const itemSelected = (item: string): boolean => selectedItems.value.includes(item);

const toggleItem = (item: string) => {
    selectedItems.value = itemSelected(item)
        ? selectedItems.value.filter((selectedItem) => selectedItem !== item)
        : [...selectedItems.value, item];
};

const clear = () => (selectedItems.value = []);

const clearExtraItems = () =>
    (selectedItems.value = selectedItems.value.slice(0, maxCount));

const toggleAll = () => {
    if (allItemsSelected.value) {
        clear();
        return;
    }

    selectedItems.value = items.map((item) => item.value);
};
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <Button
                :class="
                    cn(
                        'flex w-full p-1 rounded-md border min-h-10 h-auto items-center justify-between bg-inherit hover:bg-inherit'
                    )
                "
            >
                <!-- Render selected items -->
                <div
                    v-if="selectedItems.length > 0"
                    class="flex justify-between items-center w-full"
                >
                    <div class="flex flex-wrap items-center">
                        <Badge
                            v-for="selectedItem in selectedItems.slice(0, maxCount)"
                            :key="selectedItem"
                            class="m-1 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300 border-foreground/10 text-foreground bg-card hover:bg-card/80"
                        >
                            {{ items.find((item) => item.value === selectedItem)?.label }}

                            <XCircle
                                class="ml-2 h-4 w-4 cursor-pointer"
                                @click="toggleItem(selectedItem)"
                            />
                        </Badge>

                        <Badge
                            v-if="selectedItems.length > maxCount"
                            key="extra"
                            class="m-1 transition ease-in-out delay-150 hover:-translate-y-1 hover:scale-110 duration-300 border-foreground/10 text-foreground bg-card hover:bg-card/80"
                        >
                            {{ `+ ${selectedItems.length - maxCount} items` }}

                            <XCircle
                                class="ml-2 h-4 w-4 cursor-pointer"
                                @click="clearExtraItems"
                            />
                        </Badge>
                    </div>
                </div>

                <!-- Render placeholder -->
                <div v-else class="flex items-center justify-between w-full mx-auto">
                    <span class="text-sm text-muted-foreground mx-3">
                        {{ placeholder }}
                    </span>
                </div>

                <ChevronDown class="h-4 cursor-pointer text-muted-foreground mx-2" />
            </Button>
        </PopoverTrigger>

        <PopoverContent
            class="w-auto p-0"
            align="start"
            @keydown.escape.prevent="open = false"
        >
            <Command>
                <CommandInput placeholder="Search..." name="search" />

                <CommandList>
                    <CommandEmpty>No results found.</CommandEmpty>

                    <!-- Render items dropdown search -->
                    <CommandGroup>
                        <CommandItem
                            key="all"
                            value="all"
                            class="cursor-pointer"
                            @select.prevent="toggleAll"
                        >
                            <div
                                class="mr-2 flex h-4 w-4 items-center justify-center rounded-sm border border-primary"
                                :class="[
                                    allItemsSelected
                                        ? 'bg-primary text-primary-foreground'
                                        : 'opacity-50 [&_svg]:invisible',
                                ]"
                            >
                                <CheckIcon class="w-4 h-4" />
                            </div>

                            <span>(Select All)</span>
                        </CommandItem>

                        <CommandItem
                            v-for="item in items"
                            :key="item.label"
                            :value="item.value"
                            class="cursor-pointer"
                            @select.prevent="
                                (event: CustomEvent) => toggleItem(event.detail.value)
                            "
                        >
                            <div
                                class="mr-2 flex h-4 w-4 items-center justify-center rounded-sm border border-primary"
                                :class="[
                                    itemSelected(item.value)
                                        ? 'bg-primary text-primary-foreground'
                                        : 'opacity-50 [&_svg]:invisible',
                                ]"
                            >
                                <CheckIcon class="w-4 h-4" />
                            </div>

                            {{ item.label }}
                        </CommandItem>
                    </CommandGroup>

                    <CommandSeparator />

                    <!-- Render dropdown search controls -->
                    <CommandGroup>
                        <div class="flex items-center justify-between">
                            <template v-if="selectedItems.length > 0">
                                <CommandItem
                                    key="clear"
                                    value="clear"
                                    class="flex-1 justify-center cursor-pointer"
                                    @select.prevent="clear"
                                >
                                    Clear
                                </CommandItem>

                                <Separator
                                    orientation="vertical"
                                    class="flex min-h-6 h-full"
                                />
                            </template>

                            <CommandItem
                                key="close"
                                value="close"
                                class="flex-1 justify-center cursor-pointer max-w-full"
                                @select.prevent="open = false"
                            >
                                Close
                            </CommandItem>
                        </div>
                    </CommandGroup>
                </CommandList>
            </Command>
        </PopoverContent>
    </Popover>
</template>
