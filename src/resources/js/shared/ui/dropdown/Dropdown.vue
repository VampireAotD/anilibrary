<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';

interface Props {
    align: 'top' | 'bottom' | 'left' | 'right';
}

const props = defineProps<Props>();

const open = ref<boolean>(false);
const dropdownRef = ref<HTMLElement | null>(null);

const dropdownPosition = computed((): string => {
    switch (props.align) {
        case 'top':
            return 'bottom-full left-0';
        case 'left':
            return 'right-full top-0';
        case 'right':
            return 'left-full top-0';
        default:
            return 'top-full left-0';
    }
});

const closeOnClickOutside = (event: MouseEvent) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
        open.value = false;
    }
};

const closeOnEscape = (e: KeyboardEvent) => {
    if (open.value && e.key === 'Escape') {
        open.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeOnClickOutside);
    document.addEventListener('keydown', closeOnEscape);
});

onUnmounted(() => {
    document.removeEventListener('click', closeOnClickOutside);
    document.removeEventListener('keydown', closeOnEscape);
});
</script>

<template>
    <div ref="dropdownRef" class="relative inline-block">
        <div @click="open = !open">
            <slot name="trigger" />
        </div>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-if="open"
                :class="dropdownPosition"
                class="absolute mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-50"
                @click="open = false"
            >
                <div class="py-1">
                    <slot name="content" />
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped></style>
