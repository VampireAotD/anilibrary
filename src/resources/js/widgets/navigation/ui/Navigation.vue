<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { DropdownLink } from '@/features/navigation/dropdown-link';
import { NavigationLink } from '@/features/navigation/navigation-link';
import { ApplicationLogo } from '@/shared/ui/logo';
import { Dropdown } from '@/shared/ui/dropdown';
import { ThemeSwitcher } from '@/features/theme-switcher';
import { SearchButton } from '@/features/navigation/search-button';
import { SearchModal } from '@/features/navigation/search-modal';
import { ComponentPublicInstance, onMounted, onUnmounted, ref } from 'vue';

const showSearchModal = ref<boolean>(false);
const buttonRef = ref<HTMLButtonElement | null>(null);

const toggleSearchModalVisibility = () =>
    (showSearchModal.value = !showSearchModal.value);

const activateSearch = (event: KeyboardEvent) => {
    if (event.ctrlKey && event.key === 'k') {
        event.preventDefault();

        (buttonRef.value as ComponentPublicInstance)?.$el.click();
        (buttonRef.value as ComponentPublicInstance)?.$el.blur();
    }
};

onMounted(() => window.addEventListener('keydown', activateSearch));
onUnmounted(() => window.removeEventListener('keydown', activateSearch));
</script>

<template>
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <Link :href="route('dashboard')">
                            <ApplicationLogo
                                class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                            />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <NavigationLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Dashboard
                        </NavigationLink>
                    </div>

                    <div
                        v-if="hasRole('owner')"
                        class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                    >
                        <NavigationLink
                            :href="route('invitation.create')"
                            :active="route().current('invitation.create')"
                        >
                            Invite
                        </NavigationLink>
                    </div>

                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <NavigationLink
                            :href="route('anime.index')"
                            :active="route().current('anime.index')"
                        >
                            Anime
                        </NavigationLink>
                    </div>
                </div>

                <div class="flex sm:items-center sm:ml-6">
                    <div class="flex items-center justify-between">
                        <SearchButton
                            ref="buttonRef"
                            class="mr-3"
                            @click="toggleSearchModalVisibility"
                        />

                        <ThemeSwitcher />
                    </div>

                    <!-- Settings Dropdown -->
                    <div class="hidden sm:relative ml-3">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                    >
                                        {{ $page.props.auth.user.name }}

                                        <svg
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <SearchModal :visible="showSearchModal" @closed="toggleSearchModalVisibility" />
</template>

<style scoped></style>
