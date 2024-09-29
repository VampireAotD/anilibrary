<script setup lang="ts">
import { computed } from 'vue';

type Props = {
    type?: 'button' | 'submit' | 'reset';
    variant?: 'primary' | 'danger' | 'secondary';
    size?: 'small' | 'medium' | 'large';
    rounded?: boolean;
};

const {
    type = 'button',
    variant = 'primary',
    size = 'medium',
    rounded = false,
} = defineProps<Props>();

const variants = {
    primary:
        'border-transparent bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:ring-indigo-500 dark:focus:ring-offset-gray-800',
    danger: 'border-transparent bg-red-600 text-white hover:bg-red-500 active:bg-red-700 focus:ring-red-500 dark:focus:ring-offset-gray-800',
    secondary:
        'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-25',
};

const sizes = {
    small: 'px-3 py-2 text-xs',
    medium: 'px-4 py-2 text-xs',
    large: 'px-5 py-3 text-base',
};

const chosenVariant = computed(() => variants[variant]);
const chosenSize = computed(() => sizes[size]);
const isRounded = computed(() => (rounded ? 'rounded-full' : 'rounded-md'));
</script>

<template>
    <button
        class="inline-flex items-center border font-semibold uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
        :class="[chosenVariant, chosenSize, isRounded]"
        :type="type"
    >
        <slot />
    </button>
</template>
