<script setup lang="ts">
type Props = {
    text: string;
};

defineProps<Props>();
</script>

<template>
    <div class="px-3 border-l-4 border-border">
        <h3 class="text-md font-semibold text-foreground uppercase">
            {{ text }}
        </h3>
    </div>
</template>

<style scoped></style>
