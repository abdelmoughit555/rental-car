<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useToast } from '@/Components/shadcn/ui/toast/use-toast';
import axios from 'axios';
import { v4 as uuidv4 } from 'uuid';
import DangerButton from './DangerButton.vue';
import PrimaryButton from './PrimaryButton.vue';
const props = defineProps({
    title: String,
    description: String,
    sectionKey: String,
    number: [String, Number],
    maxUploads: {
        type: Number,
        default: 4
    },
    directory: String,
    existingFiles: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['filesUpdated']);

const { toast } = useToast();
const uploadedFiles = ref([]);
const fileInput = ref(null);
const uploading = ref(false);
const uploadProgress = ref({});

const uploadFile = async (file) => {
    try {
        const key = `${props.directory}/${uuidv4()}`;
        const contentType = file.type || 'application/octet-stream';

        // 1) Get presigned URL
        const { data: presign } = await axios.post('/s3/presign', {
            key,
            content_type: contentType,
        });

        // 2) Upload to S3/MinIO with progress tracking
        await axios.put(presign.url, file, {
            headers: presign.headers,
            transformRequest: [(d) => d],
            maxBodyLength: Infinity,
            maxContentLength: Infinity,
            onUploadProgress: (progressEvent) => {
                if (progressEvent.total) {
                    const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                    uploadProgress.value[file.name] = percentCompleted;
                }
            },
        });

        // 3) Clear progress after successful upload
        delete uploadProgress.value[file.name];

        // 4) Return the uploaded file data
        return {
            id: Date.now() + Math.random(),
            original_file_name: file.name,
            file_name: key.split('/').pop(),
            file_extension: (file.name.split('.').pop() || '').toLowerCase(),
            size: file.size,
            type: file.type,
            lastModified: file.lastModified,
            directory: props.directory,
            section: props.sectionKey,
            url: presign.object_url
        };
    } catch (error) {
        // Clear progress on error
        delete uploadProgress.value[file.name];
        throw error;
    }
};

const validateImage = (file) => {
    // Check file size (max 10MB)
    if (file.size > 10 * 1024 * 1024) {
        throw new Error(`File size must be under 10MB. Current size: ${(file.size / (1024 * 1024)).toFixed(2)}MB`);
    }
    
    // Check file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/heic'];
    if (!allowedTypes.includes(file.type)) {
        throw new Error('Only JPG, PNG, WebP, and HEIC files are allowed');
    }
    
    // Check image dimensions (min 1600px width)
    return new Promise((resolve, reject) => {
        const img = new Image();
        img.onload = () => {
            if (img.width < 1600) {
                reject(new Error(`Image width must be at least 1600px. Current width: ${img.width}px`));
            } else {
                resolve(true);
            }
        };
        img.onerror = () => {
            reject(new Error('Could not read image dimensions'));
        };
        img.src = URL.createObjectURL(file);
    });
};

const handleFileSelect = async (event) => {
    const files = Array.from(event.target.files);
    const remainingSlots = props.maxUploads - uploadedFiles.value.length;
    const filesToUpload = files.slice(0, remainingSlots);
    
    if (filesToUpload.length === 0) {
        toast({
            title: 'No upload slots available',
            description: `Maximum ${props.maxUploads} files allowed for ${props.title}`,
            variant: 'destructive',
        });
        return;
    }
    
    uploading.value = true;
    
    try {
        for (const file of filesToUpload) {
            // Validate image before upload
            try {
                await validateImage(file);
            } catch (validationError) {
                toast({
                    title: 'Invalid image',
                    description: validationError.message,
                    variant: 'destructive',
                });
                continue; // Skip this file and try the next one
            }
            
            // Create preview URL for immediate display
            const previewUrl = URL.createObjectURL(file);
            
            const uploadedFileData = await uploadFile(file);
            
            // Set the URL to the preview URL for immediate display
            uploadedFileData.url = previewUrl;
            
            // Add to this section's files
            uploadedFiles.value.push(uploadedFileData);
            
            toast({
                title: 'File uploaded',
                description: `${file.name} uploaded successfully to ${props.title}`,
                variant: 'success',
            });
        }
        
        // Emit the updated files to parent
        emit('filesUpdated', props.sectionKey, uploadedFiles.value);
        
    } catch (error) {
        toast({
            title: 'Upload failed',
            description: 'One or more files failed to upload. Please try again.',
            variant: 'destructive',
        });
    } finally {
        uploading.value = false;
        event.target.value = '';
    }
};

const previewImage = (file) => {
    if (file.url) {
        window.open(file.url, '_blank');
    } else {
        toast({
            title: 'Preview not available',
            description: 'Image URL not found',
            variant: 'destructive',
        });
    }
};

const removeFile = (index) => {
    const removedFile = uploadedFiles.value[index];
    
    if (removedFile.url && removedFile.url.startsWith('blob:')) {
        URL.revokeObjectURL(removedFile.url);
    }
    
    uploadedFiles.value.splice(index, 1);
    emit('filesUpdated', props.sectionKey, uploadedFiles.value);
    
    toast({
        title: 'File removed',
        description: `${removedFile.original_file_name} removed from ${props.title}`,
        variant: 'default',
    });
};

const handleImageError = (event) => {
    const imgElement = event.target;
    const parentDiv = imgElement.parentElement;
    
    // Hide the image
    imgElement.style.display = 'none';
    
    // Show the file extension fallback
    const fallbackDiv = parentDiv.querySelector('div');
    if (fallbackDiv) {
        fallbackDiv.style.display = 'flex';
    }
};

const remainingSlots = computed(() => props.maxUploads - uploadedFiles.value.length);

// Load existing files on mount and when props change
onMounted(() => {
    if (props.existingFiles && props.existingFiles.length > 0) {
        uploadedFiles.value = [...props.existingFiles];
    }
});

// Watch for changes in existingFiles prop
watch(() => props.existingFiles, (newFiles) => {
    if (newFiles && newFiles.length > 0) {
        uploadedFiles.value = [...newFiles];
    }
}, { immediate: true });
</script>

<template>
    <div class="border border-gray-200 rounded-lg p-4" :data-section="sectionKey" :data-title="title">
        <h4 class="text-sm font-medium text-gray-900 mb-2 flex items-center">
            <span class="w-6 h-6 border border-gray-300 rounded-full flex items-center justify-center text-xs font-bold text-gray-700 mr-2">
                {{ number }}
            </span>
            {{ title }}
        </h4>
        <p class="text-sm text-gray-600 mb-3">
            {{ description }}
        </p>
        
        <div v-if="uploadedFiles.length > 0" class="mb-4 space-y-2">
            <div v-for="(file, index) in uploadedFiles" :key="file.id" class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-16 h-16 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                        <img
                            v-if="file.url"
                            :src="file.url"
                            :alt="file.original_file_name"
                            class="w-full h-full object-cover"
                            @error="handleImageError"
                        />
                        <div v-else class="w-full h-full flex items-center justify-center">
                            <span class="text-gray-500 text-xs">
                                {{ file.file_extension?.toUpperCase() || 'FILE' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ file.original_file_name }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ file.size ? (file.size / 1000000).toFixed(2) : 'Unknown' }} MB
                        </p>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <PrimaryButton 
                        @click="previewImage(file)"
                        class="text-sm"
                    >
                        Preview
                    </PrimaryButton>
                    <DangerButton 
                        @click="removeFile(index)"
                        class="text-sm"
                    >
                        Remove
                    </DangerButton>
                </div>
            </div>
        </div>
        
        <div v-if="remainingSlots > 0" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
            <div class="space-y-4">
                <div class="flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">
                        Upload {{ title.toLowerCase() }} photo (JPG, PNG, WebP, HEIC)
                    </p>
                    <p class="text-xs text-gray-500">
                        Min 1600px width • Max 10MB • {{ remainingSlots }} more images
                    </p>
                </div>
                
                <div v-if="Object.keys(uploadProgress).length > 0" class="space-y-2">
                    <div v-for="(progress, fileName) in uploadProgress" :key="fileName" class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-blue-700 dark:text-blue-300 font-medium truncate">
                                {{ fileName }}
                            </span>
                            <span class="text-xs text-blue-600 dark:text-blue-400 font-bold">
                                {{ progress }}%
                            </span>
                        </div>
                        <div class="w-full bg-blue-200 dark:bg-blue-700 rounded-full h-2">
                            <div 
                                class="bg-blue-600 h-2 rounded-full transition-all duration-300 ease-out"
                                :style="{ width: progress + '%' }"
                            ></div>
                        </div>
                    </div>
                </div>
                
                <input
                    type="file"
                    :accept="'.jpg,.jpeg,.png,.webp'"
                    :multiple="true"
                    @change="handleFileSelect"
                    class="hidden"
                    ref="fileInput"
                />
                <button
                    @click="fileInput.click()"
                    :disabled="uploading"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white text-sm font-medium rounded-lg transition-colors"
                >
                    <span v-if="uploading">Uploading...</span>
                    <span v-else>Choose Files</span>
                </button>
            </div>
        </div>
    </div>
</template>
