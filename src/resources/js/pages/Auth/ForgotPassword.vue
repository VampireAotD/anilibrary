<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

import { Button } from '@/shared/ui/button';
import { ErrorMessage } from '@/shared/ui/error-message';
import { TextInput } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { GuestLayout } from '@/widgets/layouts';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <h3 class="text-center text-lg font-bold mb-4">Can't log in?</h3>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
        >
            {{ status }}
        </div>

        <form
            id="reset-password-form"
            class="flex flex-col gap-4"
            @submit.prevent="submit"
        >
            <div>
                <Label
                    id="email-label"
                    for="email"
                    value="We'll send a recovery link to"
                />

                <TextInput
                    id="email"
                    type="email"
                    placeholder="Enter email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    aria-labelledby="email-label"
                />

                <ErrorMessage class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex flex-col items-center space-y-4">
                <Button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    class="w-full"
                >
                    Send recovery Link
                </Button>

                <Link
                    :href="route('login')"
                    class="rounded-md text-sm underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Return to log in
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
