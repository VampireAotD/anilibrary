<script setup lang="ts">
import { ref } from 'vue';

import { Breadcrumbs, DropdownMenu } from '@/features/header';
import { SearchButton } from '@/features/navigation/search-button';
import { SearchModal } from '@/features/navigation/search-modal';
import { ThemeSwitcher } from '@/features/theme-switcher';

const showSearchModal = ref<boolean>(false);

const toggleSearchModalVisibility = () =>
    (showSearchModal.value = !showSearchModal.value);
</script>

<template>
    <header
        class="sticky top-0 z-30 flex justify-between h-14 border-b-2 bg-muted px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6"
    >
        <Breadcrumbs />

        <div class="flex justify-between items-center gap-4 flex-1 md:grow-0">
            <SearchButton
                @click="toggleSearchModalVisibility"
                @search="showSearchModal = true"
            />

            <nav class="flex items-center gap-2">
                <ThemeSwitcher />

                <DropdownMenu />
            </nav>
        </div>
    </header>

    <SearchModal :visible="showSearchModal" @closed="toggleSearchModalVisibility" />
</template>

<style scoped></style>
