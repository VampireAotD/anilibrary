<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';

import { type ModalSize } from './types';

type Props = {
    visible: boolean;
    closeOnEscape?: boolean;
    closeOnOutsideClick?: boolean;
    closeButton?: boolean;
    size?: ModalSize;
};

const props = withDefaults(defineProps<Props>(), { closeButton: true, size: '2xl' });
const emit = defineEmits<{ close: []; 'click:outside': [] }>();
const modalRef = ref<HTMLElement | null>(null);

const modalSize = computed(() => {
    return {
        xs: 'max-w-xs',
        sm: 'max-w-sm',
        md: 'max-w-md',
        lg: 'max-w-lg',
        xl: 'max-w-xl',
        '2xl': 'max-w-2xl',
        '3xl': 'max-w-3xl',
        '4xl': 'max-w-4xl',
        '5xl': 'max-w-5xl',
        '6xl': 'max-w-6xl',
        '7xl': 'max-w-7xl',
    }[props.size];
});

const close = () => {
    if (props.visible) {
        emit('close');
    }
};
const closeOnEscape = (event: KeyboardEvent) => {
    if (props.closeOnEscape && event.key === 'Escape') {
        close();
    }
};
const closeOnOutsideClick = () => {
    if (props.closeOnOutsideClick) {
        emit('click:outside');
        close();
    }
};

onMounted(() => {
    if (props.closeOnEscape) {
        window.addEventListener('keydown', closeOnEscape);
    }
});

onUnmounted(() => {
    if (props.closeOnEscape) {
        window.removeEventListener('keydown', closeOnEscape);
    }
});
</script>

<template>
    <transition
        enter-active-class="transition-opacity duration-300"
        leave-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="visible"
            class="modal-backdrop bg-gray-900/50 dark:bg-opacity-80 fixed inset-0 z-50"
        >
            <div
                ref="modalRef"
                class="modal-wrapper overflow-y-auto overflow-x-hidden outline-none fixed top-0 left-0 z-50 w-full md:inset-0 h-modal md:h-full justify-center items-center flex"
                @click.self="closeOnOutsideClick"
            >
                <div :class="modalSize" class="modal relative p-4 w-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                        <!-- Modal header -->
                        <header
                            :class="
                                $slots.header
                                    ? 'border-b border-gray-200 dark:border-gray-600'
                                    : ''
                            "
                            class="p-4 rounded-t flex justify-between items-center"
                        >
                            <slot name="header" />

                            <button
                                v-if="closeButton"
                                aria-label="close"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                type="button"
                                @click="close"
                            >
                                <slot name="close-icon">
                                    <svg
                                        class="w-5 h-5"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            clip-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            fill-rule="evenodd"
                                        />
                                    </svg>
                                </slot>
                            </button>
                        </header>

                        <!-- Modal body -->
                        <main :class="$slots.header ? '' : 'pt-0'" class="p-6">
                            <slot name="body" />
                        </main>

                        <!-- Modal footer -->
                        <footer
                            v-if="$slots.footer"
                            class="p-6 rounded-b border-gray-200 border-t dark:border-gray-600"
                        >
                            <slot name="footer" />
                        </footer>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<style scoped></style>
