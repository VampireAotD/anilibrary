<script setup lang="ts">
import { ref } from 'vue';

import { DropdownMenu } from '@/features/header';
import { SearchButton } from '@/features/navigation/search-button';
import { SearchModal } from '@/features/navigation/search-modal';
import { ThemeSwitcher } from '@/features/theme-switcher';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/shared/ui/breadcrumb';

const showSearchModal = ref<boolean>(false);

const toggleSearchModalVisibility = () =>
    (showSearchModal.value = !showSearchModal.value);
</script>

<template>
    <header
        class="sticky top-0 z-30 flex justify-between h-14 gap-4 border-b bg-background px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6"
    >
        <div class="hidden md:flex">
            <Breadcrumb>
                <BreadcrumbList>
                    <BreadcrumbItem>
                        <BreadcrumbLink> Test </BreadcrumbLink>
                    </BreadcrumbItem>
                    <BreadcrumbSeparator />
                    <BreadcrumbItem>
                        <BreadcrumbPage>Breadcrumb</BreadcrumbPage>
                    </BreadcrumbItem>
                </BreadcrumbList>
            </Breadcrumb>
        </div>

        <div class="flex justify-between gap-4 flex-1 md:grow-0">
            <SearchButton
                @click="toggleSearchModalVisibility"
                @search="showSearchModal = true"
            />

            <ThemeSwitcher />

            <DropdownMenu />
        </div>
    </header>

    <Teleport to="body">
        <SearchModal :visible="showSearchModal" @closed="toggleSearchModalVisibility" />
    </Teleport>
</template>

<style scoped></style>
