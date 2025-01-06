import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { FileBadge } from 'lucide-vue-next';

import { Button } from '@/shared/ui/button';

import FileUpload from './FileUpload.vue';

describe('FileUpload test (FileUpload.vue)', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = mount(FileUpload, {
            global: {
                stubs: {
                    Button,
                    FileBadge,
                },
            },
        });
    });

    it('Renders correctly', () => {
        const input = wrapper.find('input[type="file"]');

        expect(input.exists()).toBeTruthy();
        expect(input.classes()).toContain('hidden');
        expect(wrapper.find('button').text()).toBe('Choose files');
    });

    it('Handles file selection', async () => {
        const file = new File([''], 'test.txt', { type: 'text/plain' });
        await wrapper.vm.addFiles([file]);

        expect(wrapper.emitted('added')).toBeTruthy();
        expect(wrapper.emitted('changed')).toBeTruthy();
        expect(wrapper.findAll('li')).toHaveLength(1);
    });

    it('Respects file size limit', async () => {
        const largeFile = new File([''], 'large.txt', { type: 'text/plain' });
        Object.defineProperty(largeFile, 'size', { value: 10 * 1024 * 1024 }); // 10MB
        await wrapper.vm.addFiles([largeFile]);

        expect(wrapper.findAll('li')).toHaveLength(0);
        expect(wrapper.find('.text-red-700').text()).toContain(
            'Size of large.txt is too large'
        );
    });

    it('Respects file type restrictions', async () => {
        await wrapper.setProps({ accept: 'image/*' });
        const textFile = new File([''], 'doc.txt', { type: 'text/plain' });
        await wrapper.vm.addFiles([textFile]);

        expect(wrapper.findAll('li')).toHaveLength(0);
        expect(wrapper.find('.text-red-700').text()).toContain(
            'File doc.txt is not a valid type'
        );
    });

    it('Allows multiple file selection when enabled', async () => {
        await wrapper.setProps({ multiple: true });
        const file1 = new File([''], 'test1.txt', { type: 'text/plain' });
        const file2 = new File([''], 'test2.txt', { type: 'text/plain' });
        await wrapper.vm.addFiles([file1, file2]);

        expect(wrapper.findAll('li')).toHaveLength(2);
    });

    it('Removes a file when delete button is clicked', async () => {
        const file = new File([''], 'test.txt', { type: 'text/plain' });
        await wrapper.vm.addFiles([file]);

        const deleteButton = wrapper.find('li button');
        await deleteButton.trigger('click');

        expect(wrapper.findAll('li')).toHaveLength(0);
        expect(wrapper.emitted('removed')).toBeTruthy();
        expect(wrapper.emitted('changed')).toBeTruthy();
    });

    it('Displays image preview for image files', async () => {
        const imageFile = new File([''], 'image.png', { type: 'image/png' });
        URL.createObjectURL = vi.fn(() => 'blob:test');
        await wrapper.vm.addFiles([imageFile]);

        expect(wrapper.find('img').attributes('src')).toBe('blob:test');
    });

    it('Displays file icon for non-image files', async () => {
        const textFile = new File([''], 'doc.txt', { type: 'text/plain' });
        await wrapper.vm.addFiles([textFile]);

        expect(wrapper.findComponent(FileBadge).exists()).toBeTruthy();
    });
});
