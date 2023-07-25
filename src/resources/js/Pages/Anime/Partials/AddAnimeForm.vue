<script setup lang="ts">

import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { useForm, usePage } from "@inertiajs/vue3";
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';
import { ScrapeResult } from "@/types/pusher/types";

const page = usePage();
const toast = useToast();

const emit = defineEmits<{
  (e: 'added', url: string): void
}>()

const form = useForm({
  url: ''
})

const addAnime = () => {
  form.post(route('anime.store'), {
    onSuccess: () => {
      const channelName = `scraper.${ page.props.auth?.user?.id }`

      toast.info(page.props.flash.message, {position: 'bottom'})
      emit('added', form.url)

      window.echo.private(channelName)
          .listen('.scrape.result', (result: ScrapeResult) => {
            toast.info(result.message, {position: 'bottom', type: result.type})
            window.echo.leave(channelName)
          })
          .error(() => {
            toast.error('Error while establishing Pusher connection', {position: 'bottom'})
            window.echo.leave(channelName)
          })
    },
    onFinish: () => {
      form.reset()
    },
  })
}
</script>

<template>
  <section class="p-6">
    <header>
      <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Add new anime</h2>
    </header>

    <form @submit.prevent="addAnime" class="mt-6 space-y-6">
      <div>
        <InputLabel for="url" value="URL"/>

        <TextInput
            id="url"
            ref="url"
            v-model="form.url"
            type="url"
            class="mt-1 block w-full"
            autocomplete="url"
        />

        <InputError :message="form.errors.url" class="mt-2"/>
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing">Add</PrimaryButton>
      </div>
    </form>
  </section>
</template>

<style scoped>

</style>