<script setup>
import axios from 'axios';
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { v4 as uuidv4 } from 'uuid';
import { useUpload } from '@/stores/upload';
import { Check, X, Loader, File } from 'lucide-vue-next';

const props = defineProps({
  id: { type: String, default: 'file' },
  upload: { type: Object },
  defaultToComplete: { type: Boolean },
  defaultFileName: { type: String },
  scope: { type: String, default: 'default' },
  allowedFileTypes: { type: Array, required: false },
});

const emit = defineEmits(['change', 'fileSelected', 'uploading', 'complete', 'failed']);
const uploadStore = useUpload();
const states = uploadStore.uploadStates;
const state = ref(states.WAITING);

const uploadProgress = computed(() => props.upload?.progress || 0);

const uploadResponse = reactive({
  title: null, file_name: null, file_extension: null,
  original_file_name: null, directory: null, object_url: null,
});

const fileName = computed(() => props.defaultFileName || props.upload?.file?.name || '');
const fileSize = computed(() => props.upload?.file ? (props.upload.file.size / 1_000_000).toFixed(2) : null);

watch(() => props.upload?.queued, (q) => { if (q === false) runUpload(props.upload.file); });

watch(state, (s) => { if (props.upload?.id) uploadStore.updateUpload(props.upload.id, { state: s }); emit('change', { id: props.upload?.id || null, state: s }); });

onMounted(() => {
  if (props.defaultToComplete) state.value = states.COMPLETE;
  if (props.upload && props.upload.queued === false && props.upload.complete === false) runUpload(props.upload.file);
  else if (props.upload?.complete) state.value = states.COMPLETE;
});

async function runUpload(file) {
  try {
    if (!file) return;
    state.value = states.UPLOADING;
    if (props.upload?.id) uploadStore.updateUpload(props.upload.id, { uploading: true });
    emit('uploading'); emit('fileSelected', file);

    const directory = props.upload?.directory || 'artwork';
    const key = `${directory}/${uuidv4()}`;
    const contentType = file.type || 'application/octet-stream';

    // 1) Presign with CSRF-safe POST
    const { data: presign } = await axios.post('/s3/presign', {
      key,
      content_type: contentType,
    });

    // 2) PUT the file to MinIO with progress
    await axios.put(presign.url, file, {
      headers: presign.headers,
      onUploadProgress: (e) => {
        if (!e.total) return;
        const pct = Math.round((e.loaded / e.total) * 100);
        handleUploadProgress(pct / 100);
      },
      transformRequest: [(d) => d],
      maxBodyLength: Infinity,
      maxContentLength: Infinity,
    });

    // 3) Update UI/store
    Object.assign(uploadResponse, {
      title: file.name,
      file_name: key.split('/').pop(),
      original_file_name: file.name,
      file_extension: (file.name.split('.').pop() || '').toLowerCase(),
      directory,
      object_url: presign.object_url,
    });
    state.value = states.COMPLETE;
    if (props.upload?.id) {
      uploadStore.updateUpload(props.upload.id, {
        uploading: false, complete: true,
        directory: uploadResponse.directory,
        file_extension: uploadResponse.file_extension,
        file_name: uploadResponse.file_name,
        original_file_name: uploadResponse.original_file_name,
        object_url: uploadResponse.object_url,
      });
    }
    emit('complete', { ...uploadResponse, scope: props.scope });
  } catch (err) {
    console.error('Upload failed:', err);
    state.value = states.FAILED;
    if (props.upload?.id) uploadStore.updateUpload(props.upload.id, { uploading: false });
    emit('failed');
  }
}

function handleUploadProgress(progressFloat) {
  const percent = Math.round(progressFloat * 100);
  if (props.upload?.id) uploadStore.updateUpload(props.upload.id, { progress: percent }, props.scope);
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
            <Loader class="size-3 animate-spin mr-1" /> Waiting
          </div>
          <div v-else-if="state === states.UPLOADING" class="text-orange-400 font-medium flex items-center">
            <Loader class="size-3 animate-spin mr-1" /> Uploading
          </div>
          <div v-else-if="state === states.FAILED" class="text-red-400 font-medium flex items-center">
            <X class="size-3 mr-1" /> Failed
          </div>
          <div v-else-if="state === states.COMPLETE" class="text-green-400 font-medium flex items-center">
            <Check class="size-3 mr-1" /> Complete
          </div>
          <p v-if="fileSize" class="text-muted-foreground">- {{ fileSize }} MB</p>
          <p v-if="state === states.UPLOADING" class="text-muted-foreground">- {{ uploadProgress }}% Uploaded</p>
        </div>
      </div>
    </div>
    <slot name="right" />
  </div>
</template>
