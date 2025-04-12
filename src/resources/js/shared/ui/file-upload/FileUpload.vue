<script setup lang="ts">
import { ref } from 'vue';

import { FileBadge } from 'lucide-vue-next';

import { formatFileSize } from '@/shared/helpers/bytes';
import { Button } from '@/shared/ui/button';

type Props = {
    multiple?: boolean;
    maxFileSize?: number;
    accept?: string;
};

const defaultFileSizeLimit: number = 5 * 1024 * 1024; // 5MB

const {
    multiple = false,
    maxFileSize = defaultFileSizeLimit,
    accept = '*',
} = defineProps<Props>();

const emit = defineEmits<{
    added: [files: File[]];
    removed: [files: File[]];
    changed: [files: File[]];
}>();

const fileInput = ref<HTMLInputElement | null>(null);
const files = ref<File[]>([]);
const dragover = ref<boolean>(false);
const errors = ref<string[]>([]);

const clearInput = () => ((fileInput.value as HTMLInputElement).value = '');

const onFileChange = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (target.files) {
        addFiles(Array.from(target.files));
    }
};

const onDrop = (event: DragEvent) => {
    dragover.value = false;

    if (event.dataTransfer?.files) {
        addFiles(Array.from(event.dataTransfer.files));
    }
};

const addFiles = (addedFiles: File[]) => {
    errors.value = [];

    if (!multiple && addedFiles.length > 1) {
        errors.value.push('Only one file can be uploaded');
        return;
    }

    const validFiles = addedFiles.filter((file) => {
        if (file.size > maxFileSize) {
            errors.value.push(
                `Size of ${file.name} is too large. Max size is ${formatFileSize(maxFileSize)}`
            );
            return false;
        }

        if (accept !== '*' && !file.type.match(accept)) {
            errors.value.push(
                `File ${file.name} is not a valid type. Accepted types: ${accept}`
            );
            return false;
        }

        return true;
    });

    if (multiple) {
        files.value.push(...validFiles);
        emit('added', files.value);
        emit('changed', files.value);
        return;
    }

    files.value = validFiles.slice(0, 1);
    emit('added', files.value);
    emit('changed', files.value);
};

const removeFile = (index: number) => {
    clearInput();
    files.value.splice(index, 1);
    emit('removed', files.value);
    emit('changed', files.value);
};

const isImage = (file: File) => {
    return file.type.startsWith('image/');
};

const preview = (file: File) => {
    return URL.createObjectURL(file);
};
</script>

<template>
    <div class="w-full">
        <div
            @drop.prevent="onDrop"
            @dragover.prevent="dragover = true"
            @dragleave.prevent="dragover = false"
            @click="$refs.fileInput.click()"
            class="border-2 border-dashed rounded-lg p-4 text-center cursor-pointer"
            :class="[dragover ? 'border-blue-500 bg-blue-50' : 'border-gray-300']"
        >
            <input
                type="file"
                ref="fileInput"
                @change="onFileChange"
                :multiple="multiple"
                :accept="accept"
                class="hidden"
            />

            <p class="mb-2">Drag files here or click to upload</p>

            <Button> Choose files</Button>
        </div>

        <div
            v-if="errors.length"
            class="overflow-hidden overflow-y-scroll mt-4 p-3 bg-red-100 border border-red-400 rounded"
        >
            <p v-for="(error, index) in errors" :key="index" class="text-red-700">
                {{ error }}
            </p>
        </div>

        <ul v-if="files.length" class="mt-4 space-y-4">
            <li
                v-for="(file, index) in files"
                :key="index"
                class="flex items-center bg-gray-100 p-2 rounded"
            >
                <div class="w-16 h-16 mr-4 shrink-0">
                    <img
                        v-if="isImage(file)"
                        :src="preview(file)"
                        :alt="file.name"
                        class="w-full h-full object-cover rounded"
                    />

                    <div
                        v-else
                        class="w-full h-full flex items-center justify-center bg-gray-200 rounded"
                    >
                        <FileBadge />
                    </div>
                </div>

                <div class="grow">
                    <p class="font-semibold truncate">{{ file.name }}</p>
                    <p class="text-sm text-gray-500">{{ formatFileSize(file.size) }}</p>
                </div>

                <Button variant="destructive" @click="removeFile(index)"> Delete</Button>
            </li>
        </ul>
    </div>
</template>
