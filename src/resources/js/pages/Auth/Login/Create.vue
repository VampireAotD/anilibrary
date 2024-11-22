<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

import { Button } from '@/shared/ui/button';
import { Checkbox } from '@/shared/ui/checkbox';
import { ErrorMessage } from '@/shared/ui/error-message';
import { TextInput } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { Separator } from '@/shared/ui/separator';
import { GuestLayout } from '@/widgets/layouts';

type Props = {
    canResetPassword?: boolean;
    status?: string;
};

defineProps<Props>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <Label for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <ErrorMessage class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <Label for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <ErrorMessage class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <Label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm"> Remember me </span>
                </Label>
            </div>

            <div class="mt-4 flex items-center justify-between">
                <div class="flex gap-2">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="rounded-md text-sm underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Can't log in?
                    </Link>

                    <Separator orientation="vertical" class="flex h-5 min-h-full" />

                    <Link
                        :href="route('registration_access.request')"
                        class="rounded-md text-sm underline focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Register
                    </Link>
                </div>

                <Button
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
