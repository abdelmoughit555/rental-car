import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useUpload = defineStore('upload', () => {
    const uploads = ref({});
    const uploadStates = {
        WAITING: 'waiting',
        UPLOADING: 'uploading',
        COMPLETE: 'complete',
        FAILED: 'failed'
    };

    const getUploads = (scope = 'default') => {
        if (!uploads.value[scope]) {
            uploads.value[scope] = [];
        }
        return uploads.value[scope];
    };

    const getCurrentUploadCount = (scope = 'default') => {
        return getUploads(scope).length;
    };

    const getNextQueuedUpload = (scope = 'default') => {
        const scopeUploads = getUploads(scope);
        return scopeUploads.find(upload => upload.queued === true);
    };

    const addUpload = (upload, scope = 'default') => {
        const scopeUploads = getUploads(scope);
        scopeUploads.push(upload);
        return upload;
    };

    const addUploads = (newUploads, scope = 'default') => {
        const scopeUploads = getUploads(scope);
        scopeUploads.push(...newUploads);
        return newUploads;
    };

    const updateUpload = (id, updates, scope = 'default') => {
        const scopeUploads = getUploads(scope);
        const upload = scopeUploads.find(u => u.id === id);
        if (upload) {
            Object.assign(upload, updates);
        }
    };

    const removeUpload = (id, scope = 'default') => {
        const scopeUploads = getUploads(scope);
        const index = scopeUploads.findIndex(u => u.id === id);
        if (index > -1) {
            scopeUploads.splice(index, 1);
        }
    };

    const clearUploads = (scope = 'default') => {
        uploads.value[scope] = [];
    };

    return {
        uploads,
        uploadStates,
        getUploads,
        getCurrentUploadCount,
        getNextQueuedUpload,
        addUpload,
        addUploads,
        updateUpload,
        removeUpload,
        clearUploads
    };
});
