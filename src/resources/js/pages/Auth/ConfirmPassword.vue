<script setup lang="ts">
import { GuestLayout } from '@/widgets/layouts';
import { ErrorMessage } from '@/shared/ui/error-message';
import { Label } from '@/shared/ui/label';
import { Button } from '@/shared/ui/button';
import { TextInput } from '@/shared/ui/input';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            This is a secure area of the application. Please confirm your password before
            continuing.
        </div>

        <form @submit.prevent="submit">
            <div>
                <Label for="password" value="Password" />
                <TextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <ErrorMessage class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex justify-end mt-4">
                <Button
                    class="ml-4"
                    type="submit"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
