<script setup lang="ts">
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/shared/ui/breadcrumb';
</script>

<template>
    <div class="hidden md:flex md:items-center">
        <Breadcrumb>
            <BreadcrumbList>
                <template v-for="(crumb, index) in $page.props.breadcrumbs" :key="index">
                    <BreadcrumbItem>
                        <template v-if="crumb.is_current_page">
                            <BreadcrumbPage>{{ crumb.title }}</BreadcrumbPage>
                        </template>
                        <template v-else>
                            <BreadcrumbLink :href="crumb.url">
                                {{ crumb.title }}
                            </BreadcrumbLink>
                        </template>
                    </BreadcrumbItem>

                    <BreadcrumbSeparator
                        v-if="index < $page.props.breadcrumbs.length - 1"
                    />
                </template>
            </BreadcrumbList>
        </Breadcrumb>
    </div>
</template>

<style scoped></style>
