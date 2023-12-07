<script setup lang="ts">
import BaseModal from '@/Components/BaseModal.vue';
import InputLabel from '@/Components/Input/InputLabel.vue';
import DangerButton from '@/Components/Button/DangerButton.vue';
import InputError from '@/Components/Input/InputError.vue';
import BaseTextInput from '@/Components/BaseTextInput.vue';
import SecondaryButton from '@/Components/Button/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import { ref, watchEffect } from 'vue';

type Props = {
    show: boolean;
};

defineProps<Props>();
const emit = defineEmits<{ close: [] }>();

const passwordInput = ref<HTMLInputElement | null>(null);
const form = useForm({
    password: '',
});

const closeModal = () => {
    emit('close');
    form.reset();
};
const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
    });
};

watchEffect(() => {
    if (passwordInput.value) {
        passwordInput.value!.focus();
    }
});
</script>

<template>
    <BaseModal
        v-if="show"
        :visible="show"
        close-on-escape
        close-on-outside-click
        @close="closeModal"
    >
        <template #header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Are you sure you want to delete your account?
            </h2>
        </template>

        <template #body>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Once your account is deleted, all of its resources and data will be
                permanently deleted. Please enter your password to confirm you would like
                to permanently delete your account.
            </p>

            <div class="mt-6">
                <InputLabel for="password" value="Password" class="sr-only" />

                <BaseTextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Password"
                    @keyup.enter="deleteUser"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancel</SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteUser"
                >
                    Delete Account
                </DangerButton>
            </div>
        </template>
    </BaseModal>
</template>

<style scoped></style>
