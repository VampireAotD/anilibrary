<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';

import { Button } from '@/shared/ui/button';
import { ErrorMessage } from '@/shared/ui/error-message';
import { TextInput } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { useToast } from '@/shared/ui/toast';

const page = usePage();
const { toast } = useToast();

const emit = defineEmits<{ sent: [] }>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('invitation.send'), {
        onSuccess: () => {
            toast({
                title: page.props.flash.message,
            });

            form.reset();
            emit('sent');
        },
    });
};
</script>

<template>
    <form @submit.prevent="submit">
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
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Send
            </Button>
        </div>
    </form>
</template>

<style scoped></style>
