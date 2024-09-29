<script setup lang="ts">
import { reactive } from 'vue';

import FileUpload, { type FileUploadSelectEvent } from 'primevue/fileupload';
import MultiSelect from 'primevue/multiselect';

import { useForm } from '@inertiajs/vue3';

import { Button } from '@/shared/ui/button';
import { ErrorMessage } from '@/shared/ui/error-message';
import { TextInput } from '@/shared/ui/input/text';
import { Label } from '@/shared/ui/label';
import { Modal } from '@/shared/ui/modal';

type Props = {
    visible: boolean;
};

defineProps<Props>();
defineEmits<{ close: [] }>();

const form = useForm({
    title: '',
    status: '',
    rating: '',
    episodes: '',
    urls: [{ url: '' }],
    synonyms: [{ name: '' }],
    genres: [],
    voiceActing: [],
    image: null,
});

const availableGenres = reactive([]);
const availableVoiceActing = reactive([]);

const addUrl = () => form.urls.push({ url: '' });

const removeUrl = (index: number) => form.urls.splice(index, 1);

const addSynonym = () => form.synonyms.push({ name: '' });

const removeSynonym = (index: number) => form.synonyms.splice(index, 1);

const addImage = (event: FileUploadSelectEvent) => (form.image = event.files[0]);

const removeImage = () => (form.image = null);

const createAnime = () => {};
</script>

<template>
    <Modal
        :visible="visible"
        close-on-escape
        close-on-outside-click
        size="7xl"
        @close="$emit('close')"
    >
        <template #header>
            <h1>Create new anime</h1>
        </template>

        <template #body>
            <form class="overflow-y-auto max-h-96 p-1" @submit.prevent="createAnime">
                <fieldset class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <Label
                            for="title"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            value="Title"
                        />

                        <TextInput
                            id="title"
                            v-model="form.title"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Anime title"
                        />

                        <ErrorMessage :message="form.errors.title" />
                    </div>

                    <div>
                        <Label
                            for="status"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            value="Status"
                        />

                        <TextInput
                            id="status"
                            v-model="form.status"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Anime status"
                        />

                        <ErrorMessage :message="form.errors.status" />
                    </div>

                    <div>
                        <Label
                            for="rating"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            value="Rating"
                        />

                        <TextInput
                            id="rating"
                            v-model="form.rating"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Anime rating"
                        />

                        <ErrorMessage :message="form.errors.rating" />
                    </div>

                    <div>
                        <Label
                            for="episodes"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            value="Episodes"
                        />

                        <TextInput
                            id="episodes"
                            v-model="form.episodes"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Anime episodes"
                        />

                        <ErrorMessage :message="form.errors.episodes" />
                    </div>

                    <div>
                        <span
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Genres</span
                        >

                        <MultiSelect
                            v-model="form.genres"
                            name="genre[]"
                            :options="availableGenres"
                            display="chip"
                            filter
                            class="!w-full !bg-gray-50 !border-gray-300 p-0.5 !text-gray-900 !text-sm"
                            placeholder="Anime genres"
                        />

                        <ErrorMessage :message="form.errors.genres" />
                    </div>

                    <div>
                        <span
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Voice acting</span
                        >

                        <MultiSelect
                            v-model="form.voiceActing"
                            :options="availableVoiceActing"
                            display="chip"
                            filter
                            class="!w-full !bg-gray-50 !border-gray-300 p-0.5 !text-gray-900 !text-sm"
                            placeholder="Anime voice acting"
                        />

                        <ErrorMessage :message="form.errors.voiceActing" />
                    </div>

                    <div>
                        <span
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Urls</span
                        >

                        <div
                            v-for="(url, index) in form.urls"
                            :key="index"
                            class="flex gap-2 mb-2"
                        >
                            <TextInput
                                v-model="url.url"
                                class="w-9/12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                name="url[]"
                                placeholder="Anime URL"
                            />

                            <aside class="w-3/12 flex gap-4">
                                <button label="Добавить ссылку" @click="addUrl">
                                    <i class="pi pi-plus-circle"></i>
                                </button>

                                <button
                                    v-if="form.urls.length > 1"
                                    @click="removeUrl(index)"
                                >
                                    <i class="pi pi-minus-circle"></i>
                                </button>
                            </aside>
                        </div>

                        <ErrorMessage :message="form.errors.urls" />
                    </div>

                    <div>
                        <span
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Synonyms</span
                        >

                        <div
                            v-for="(synonym, index) in form.synonyms"
                            :key="index"
                            class="flex gap-2"
                        >
                            <TextInput
                                v-model="synonym.name"
                                name="synonym[]"
                                class="w-9/12 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Anime synonym"
                            />

                            <aside class="w-3/12 flex gap-4">
                                <button label="Add synonym" @click="addSynonym">
                                    <i class="pi pi-plus-circle"></i>
                                </button>

                                <button
                                    v-if="form.synonyms.length > 1"
                                    @click="removeSynonym(index)"
                                >
                                    <i class="pi pi-minus-circle"></i>
                                </button>
                            </aside>
                        </div>

                        <ErrorMessage :message="form.errors.synonyms" />
                    </div>

                    <div class="sm:col-span-2">
                        <FileUpload
                            name="image"
                            accept="image/*"
                            :file-limit="1"
                            :show-upload-button="false"
                            :show-cancel-button="false"
                            :max-file-size="1000000"
                            @select="addImage"
                            @remove="removeImage"
                        >
                            <template #empty>
                                <p>Drag and drop files to here to upload.</p>
                            </template>
                        </FileUpload>
                    </div>
                </fieldset>

                <div class="flex justify-end gap-4">
                    <Button variant="secondary" @click="$emit('close')"> Cancel</Button>

                    <Button type="submit" :disabled="form.processing">
                        Create anime
                    </Button>
                </div>
            </form>
        </template>
    </Modal>
</template>

<style scoped>
/* width */
::-webkit-scrollbar {
    width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 5px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
