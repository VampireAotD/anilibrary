<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/Input/InputError.vue';
import InputLabel from '@/Components/Input/InputLabel.vue';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import BaseTextInput from '@/Components/BaseTextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

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

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" />

                <BaseTextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <BaseTextInput
                    id="password"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <div class="flex items-center">
                    <Checkbox
                        id="remember"
                        v-model:checked="form.remember"
                        name="remember"
                    />
                    <label
                        for="remember"
                        class="ml-2 text-sm text-gray-600 dark:text-gray-400"
                        >Remember me</label
                    >
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
