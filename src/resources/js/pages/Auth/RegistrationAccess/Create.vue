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
    form.post(route('registration_access.acquire'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Registration Link Request" />

        <h3 class="text-center text-lg font-bold mb-4">Want to join Anilibrary?</h3>

        <p class="mb-4 text-sm">
            Anilibrary is a closed community. To join you must have an invitation link to
            register.
        </p>

        <div
            v-if="status"
            class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
        >
            {{ status }}
        </div>

        <form
            id="request-invitation-form"
            class="flex flex-col gap-4"
            @submit.prevent="submit"
        >
            <div>
                <Label
                    id="email-label"
                    for="email"
                    value="We'll send an invitation link to"
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
                    Send an invitation
                </Button>

                <Link
                    :href="route('login')"
                    class="rounded-md text-sm underline focus:outline-none"
                >
                    Already have an account?
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
