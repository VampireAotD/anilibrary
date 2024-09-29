<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import { Button } from '@/shared/ui/button';
import { ErrorMessage } from '@/shared/ui/error-message';
import { TextInput } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('invitation.send'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <form class="p-6 text-gray-900 dark:text-gray-100" @submit.prevent="submit">
        <div>
            <Label for="email" value="Email" />

            <TextInput
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
                required
                autocomplete="email"
            />

            <ErrorMessage class="mt-2" :message="form.errors.email" />
        </div>

        <div class="flex justify-end mt-4">
            <Button
                class="ml-4"
                type="submit"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Send invitation
            </Button>
        </div>
    </form>
</template>

<style scoped></style>
