<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { v4 as uuidv4 } from 'uuid';
import Evaporate from 'evaporate';
import { useUpload } from '@/stores/upload'
import {
  Check,
  X,
  Loader,
  File
} from 'lucide-vue-next'
const props = defineProps({
    id: {
        type: String,
        default: 'file'
    },
    upload: {
        type: Object
    },
    defaultToComplete: {
        type: Boolean
    },
    defaultFileName: {
        type: String
    },
    scope: {
        type: String,
        default: 'default'
    },
    allowedFileTypes: {
        type: Array,
        required: false
    },
});

const emit = defineEmits(['change', 'fileSelected', 'uploading', 'complete', 'failed']);
const uploadStore = useUpload();
const states = uploadStore.getUploadStates
const state = ref('');

const uploadProgress = computed(() => props.upload?.progress || 0);

const uploadResponse = reactive({
    title: null,
    file_name: null,
    file_extension: null,
    original_file_name: null
});

const fileName = computed(() => props.defaultFileName || props.upload?.file.name || '');
const fileSize = computed(() => props.upload?.file ? (props.upload.file.size / 1000000).toFixed(2) : null);

watch(() => props.upload?.queued, (queued) => {
    if (queued === false) {
        runUpload(props.upload.file);
    }
});

watch(state, (newState) => {
    if (props.upload?.id) {
        uploadStore.updateUpload(props.upload.id, { state: newState });
    }
    emit("change", { id: props.upload?.id || null, state: newState });
});

onMounted(() => {
    if (props.defaultToComplete) {
        state.value = states.COMPLETE;
    }
    if (props.upload && props.upload.queued === false && props.upload.complete === false) {
        runUpload(props.upload.file);
    } else if (props.upload?.complete) {
        state.value = states.COMPLETE;
    }
});

const runUpload = (file) => {
    state.value = states.UPLOADING;
    uploadStore.updateUpload(props.upload.id, { uploading: true });

    const contentType = file.type || (props.allowedFileTypes?.some(type => type.startsWith('audio/')) ? 'audio/mpeg' : 'application/octet-stream');
    const config = {
        signerUrl: '/sign',
        aws_key: import.meta.env.VITE_AWS_ACCESS_KEY_ID,
        bucket: import.meta.env.VITE_AWS_BUCKET,
        aws_url: import.meta.env.VITE_AWS_URL,
        awsRegion: import.meta.env.VITE_AWS_REGION,
        cloudfront: true,
        computeContentMd5: true,
        logging: false,
        cryptoMd5Method: (data) => AWS.util.crypto.md5(data, 'base64'),
        cryptoHexEncodedHash256: (data) => AWS.util.crypto.sha256(data, 'hex'),
        signParams: {
            'Content-Type': contentType
        }
    };

    Evaporate.create(config).then(evaporate => {
        let uploadKey = uuidv4();
        uploadResponse.value = {
            id: props.upload?.id,
            title: file.name,
            file_name: uploadKey,
            original_file_name: file.name,
            file_extension: file.name.split('.').pop(),
            directory: props.upload.directory
        };

        emit("fileSelected", file);
        emit("uploading");
        evaporate.add({
            file: file,
            name: props.upload.directory + "/" + uploadKey,
            progress: handleUploadProgress,
            contentType: contentType,
            cancelled: () => {
                state.value = states.FAILED;
                uploadStore.updateUpload(props.upload.id, { uploading: false });
                emit('failed');
            }
        }).then(() => {
            state.value = states.COMPLETE;
            uploadStore.updateUpload(props.upload.id, {
                uploading: false,
                complete: true,
                directory: uploadResponse.value.directory,
                file_extension: uploadResponse.value.file_extension,
                file_name: uploadResponse.value.file_name,
                original_file_name: uploadResponse.value.original_file_name
            });
            emit("complete", uploadResponse.value);
        }).catch((error) => {
            state.value = states.FAILED;
            uploadStore.updateUpload(props.upload.id, { uploading: false });
            emit("failed");
        });
    });
}

const handleUploadProgress = (progress) => {
    const progressPercent = Math.round(progress * 100);
    if (props.upload?.id) {
        uploadStore.updateUpload(props.upload.id, { progress: progressPercent }, props.scope);
    }
}
</script>

<template>
    <div class="py-3 px-4 border rounded-md flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <File :id="id" class="h-6 w-6 text-muted-foreground" />
            <div>
                <p class="font-semibold text-accent-foreground">{{ fileName }}</p>
                <div class="flex items-center space-x-1 text-gray-600 text-xs">
                    <div v-if="state === states.WAITING" class="text-blue-400 font-medium flex items-center">
                        <div class="mr-1">
                            <Loader class="size-3 animate-spin" />
                        </div>
                        Waiting
                    </div>
                    <div v-else-if="state === states.UPLOADING" class="text-orange-400 font-medium flex items-center">
                        <div class="mr-1">
                            <Loader class="size-3 animate-spin" />
                        </div>
                        Uploading
                    </div>
                    <div v-else-if="state === states.FAILED" class="text-red-400 font-medium flex items-center">
                        <div class="mr-1">
                            <X class="size-3" />
                        </div>
                        Failed
                    </div>
                    <div v-else-if="state === states.COMPLETE" class="text-green-400 font-medium flex items-center">
                        <div class="mr-1">
                            <Check class="size-3" />
                        </div>
                        Complete
                    </div>
                    <p v-if="fileSize" class="text-muted-foreground">- {{ fileSize }} MB</p>
                    <p v-if="state === states.UPLOADING" class="text-muted-foreground">- {{ uploadProgress }}% Uploaded</p>
                </div>
            </div>
        </div>
        <slot name="right" />
    </div>
</template>
