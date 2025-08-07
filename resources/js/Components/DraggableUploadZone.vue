<script setup>
import { ref } from 'vue';
import { ImageUp } from 'lucide-vue-next';
import Fader from '@/Components/Fader.vue';
import Alert from '@/Components/Alert.vue';
import { v4 as uuidv4 } from 'uuid';

const props = defineProps({
    id: {
        type: String,
        default: 'file'
    },
    name: {
        type: String,
        default: 'file'
    },
    message: {
        type: String,
        default: 'Maximum file upload of 50mb.'
    },
    multiple: {
        type: Boolean,
        default: false
    },
    allowedFileTypes: {
        type: Array,
        required: false
    },
    validateImageDimensions: {
        type: Boolean,
        default: false
    },
    minImageWidth: {
        type: Number,
        default: 2000
    },
    minImageHeight: {
        type: Number,
        default: 2000
    },
    validateSquareImage: {
        type: Boolean,
        default: false
    },
    maxUploads: {
        type: Number,
        default: null
    },
    currentUploadCount: {
        type: Number,
        default: 0
    },
    directory: {
        type: String,
        default: 'tmp'
    }
});

const emit = defineEmits(['uploadInitiated']);

const isDraggedOver = ref(false);
const error = ref(null);
const input = ref(null);

const showError = (message) => {
    error.value = message;
    setTimeout(() => error.value = null, 2500);
};

const enter = () => isDraggedOver.value = true;
const leave = () => isDraggedOver.value = false;

const validateFiles = (files) => {
    if (props.maxUploads) {
        if (props.currentUploadCount >= props.maxUploads) {
            showError(`You can only upload ${props.maxUploads} file(s).`);
            return false;
        } else if (files.length > props.maxUploads) {
            showError(`You can only upload ${props.maxUploads} file(s).`);
            return false;
        }
    }

    if (props.allowedFileTypes && props.allowedFileTypes.length !== 0) {
        const invalidFiles = Array.from(files).filter(file => !props.allowedFileTypes.includes(file.type));
        if (invalidFiles.length > 0) {
            showError('One or more of your files is of the incorrect type.');
            return false;
        }
    }

    return true;
};

const validateImage = (file) => {
    return new Promise((resolve) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (e) => {
            const img = new Image();
            img.src = e.target.result;
            
            img.onerror = () => {
                showError('Invalid image format.');
                resolve(false);
            };

            img.onload = () => {
                let isValid = true;
                
                if (props.validateSquareImage && img.width !== img.height) {
                    showError(`Image must be square and at least ${props.minImageWidth}x${props.minImageHeight}px.`);
                    isValid = false;
                }

                if (img.width < props.minImageWidth || img.height < props.minImageHeight) {
                    showError(`Minimum size: ${props.minImageWidth}x${props.minImageHeight}px`);
                    isValid = false;
                }

                resolve(isValid);
            };
        };
    });
};

const initiateUpload = (files) => {
    const uploads = Array.from(files).map(file => ({
        id: uuidv4(),
        uploading: false,
        complete: false,
        queued: true,
        file,
        directory: props.directory
    }));

    emit('uploadInitiated', uploads);
};

const handleFiles = async (files) => {
    if (!validateFiles(files)) {
        if (input.value) {
            input.value.value = '';
        }
        return;
    }

    if (props.validateImageDimensions) {
        const validationPromises = Array.from(files).map(file => validateImage(file));
        const results = await Promise.all(validationPromises);
        
        if (results.every(isValid => isValid)) {
            initiateUpload(files);
        } else {
            if (input.value) {
                input.value.value = '';
            }
        }
    } else {
        initiateUpload(files);
    }
};

const select = (e) => {
    const files = e.target.files;
    handleFiles(files);
};

const drop = (e) => {
    leave();
    const files = e.dataTransfer.files;
    handleFiles(files);
};
</script>

<template>
    <div class="w-full">
        <Fader>
            <Alert v-if="error" show type="error">
                <template #title>{{ error }}</template>
            </Alert>
        </Fader>

        <div
            @dragover.prevent="enter"
            @dragenter.prevent="enter"
            @dragleave.prevent="leave"
            @dragend.prevent="leave"
            @drop.prevent="drop"
            :class="{ 'border border-primary': isDraggedOver, 'border-destructive mt-2': error }"
            class="flex justify-center px-6 pt-5 pb-6 border-2 border-input border-dashed rounded-md"
        >
            <div class="text-center">
                <ImageUp class="mx-auto h-10 w-10 text-muted-foreground" strokeWidth="1" />
                <p class="mt-2 text-sm text-muted-foreground">
                    <input type="file" :name="name" :id="id" class="hidden" @input="select" ref="input" :multiple="multiple" />
                    <label :for="id" class="font-medium text-primary hover:text-primary/80 hover:underline hover:cursor-pointer">
                        Upload a file
                    </label>
                    or drag and drop
                </p>
                <p class="mt-1 text-xs text-gray-500">{{ message }}</p>
            </div>
        </div>
    </div>
</template>
