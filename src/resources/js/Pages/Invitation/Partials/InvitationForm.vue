<script setup lang="ts">
import Button from '@/Components/Button.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import BaseTextInput from '@/Components/BaseTextInput.vue';
import { useForm } from '@inertiajs/vue3';

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
            <InputLabel for="email" value="Email" />

            <BaseTextInput
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
                required
                autocomplete="email"
            />

            <InputError class="mt-2" :message="form.errors.email" />
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
