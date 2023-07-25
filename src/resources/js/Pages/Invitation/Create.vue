<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";

const form = useForm({
  email: '',
})

const submit = () => {
  form.post(route('invitation.send'), {
    onSuccess: () => {
      form.reset();
    },
  });
}
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Create invitation to Anilibrary"/>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <form class="p-6 text-gray-900 dark:text-gray-100" @submit.prevent="submit">
            <div>
              <InputLabel for="email" value="Email"/>

              <TextInput
                  id="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  required
                  autofocus
                  autocomplete="email"
              />

              <InputError class="mt-2" :message="form.errors.email"/>
            </div>

            <div class="flex justify-end mt-4">
              <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                             :disabled="form.processing">
                Send invitation
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>

</style>
