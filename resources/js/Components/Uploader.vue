<script setup>
	import { ref, watch, defineProps, defineEmits, onMounted, onUnmounted } from 'vue';
	import { v4 as uuidv4 } from 'uuid';
	import { useUpload } from '@/stores/upload'
	import UploaderFile from './UploaderFile.vue';
	import DangerButton from '@/Components/DangerButton.vue'
	import Alert from '@/Components/Alert.vue';
	import Fader from '@/Components/Fader.vue';
	import DraggableUploadZone from './DraggableUploadZone.vue';

	const props = defineProps({
		id: {
			type: String,
			default: 'file'
		},
		name: {
			type: String,
			default: 'file'
		},
		showTitle: {
			type: Boolean,
			required: false
		},
		allowedFileExtensions: {
			type: Array,
			required: false
		},
		allowedFileTypes: {
			type: Array,
			required: false
		},
		validateSquareImage: {
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
		validateImageDimensions: {
			type: Boolean,
			default: false
		},
		message: {
			type: String,
			default: 'Maximum file upload of 50mb.'
		},
		defaultToComplete: {
			type: Boolean,
			default: false
		},
		defaultFileName: {
			type: String
		},
		directory: {
			type: String,
			default: 'tmp'
		},
		multiple: {
			type: Boolean,
			default: false
		},
		maxUploads: {
			type: Number,
			default: null
		},
		maxConcurrentUploads: {
			type: Number,
			default: 3
		},
		showUploads: {
			type: Boolean,
			default: true
		},
		scope: {
			type: String,
			default: 'default'
		}
	});

	const emit = defineEmits(['uploading', 'complete', 'failed', 'fileSelected', 'reset', 'newUpload', 'removed', 'newUploads']);

	const uploadStore = useUpload()

	const error = ref(null);
	const isUploading = ref(false);
	const uploadComplete = ref(props.defaultToComplete);

	const allowUploads = () => props.maxUploads ? uploadStore.getUploads(props.scope).length < props.maxUploads : true;

	watch(isUploading, () => emit('uploading', isUploading.value));
	watch(() => props.defaultToComplete, () => uploadComplete.value = props.defaultToComplete);

	onMounted(() => {
		if (props.defaultToComplete) {
			uploadComplete.value = true;
		}

		const interval = setInterval(() => {
			processNextUpload();
		}, 500);

		onUnmounted(() => {
			clearInterval(interval);
			if (!props.multiple) {
				uploadStore.clearUploads(props.scope);
			}
		});
	});

	const processNextUpload = () => {
		if (uploadStore.getCurrentUploadCount(props.scope) >= props.maxConcurrentUploads) {
			return;
		}

		const nextUpload = uploadStore.getNextQueuedUpload(props.scope);
		if (nextUpload) {
			uploadStore.updateUpload(nextUpload.id, { queued: false }, props.scope);
			processFileUpload(nextUpload);
		}
	};

	const processFileUpload = (upload) => {
		upload.uploading = true;
		isUploading.value = true;
	};

	const complete = (data) => {
		// Don't try to update upload store with data.id since it doesn't exist
		// Just emit the complete event with the data
		emit('complete', data);
	};

	const failed = () => {
		emit('failed');
	};

	const fileSelected = (data) => emit('fileSelected', data);
	const resetUpload = () => {
		isUploading.value = false;
		uploadComplete.value = false;
		uploadStore.clearUploads(props.scope);
		emit('reset');
	};

	const handleUploadInitiated = (uploads) => {
		console.log(`=== UPLOADER SCOPE DEBUG ===`);
		console.log(`Component name: ${props.name}`);
		console.log(`Props scope: ${props.scope}`);
		console.log(`Uploads to add:`, uploads);
		console.log(`Adding to scope: ${props.scope}`);
		console.log(`=== END SCOPE DEBUG ===`);
		
		uploadStore.addUploads(uploads, props.scope);
		processNextUpload();

		emit('newUploads', uploads)

		uploads.forEach(upload => {
			emit('newUpload', upload);
		});
	};
</script>
<template>
	<div class="w-full">
		<Fader>
			<Alert v-if="error" show type="error">
				<template #title>{{ error }}</template>
			</Alert>
		</Fader>

		<DraggableUploadZone
			v-if="(multiple || uploadStore.getUploads(props.scope).length === 0) && allowUploads"
			:id="id"
			:name="name"
			:message="message"
			:multiple="multiple"
			:allowedFileTypes="allowedFileTypes"
			:maxUploads="maxUploads"
			:validateImageDimensions="validateImageDimensions"
			:minImageHeight="minImageHeight"
			:minImageWidth="minImageWidth"
			:currentUploadCount="uploadStore.getUploads(props.scope).length"
			:directory="directory"
			@uploadInitiated="handleUploadInitiated"
		/>

		<div v-show="showUploads" v-if="uploadStore.getUploads(props.scope).length > 0" :class="{ 'mt-4 space-y-2': multiple }">
			<div v-for="upload in uploadStore.getUploads(props.scope)" :key="upload.id">
				<UploaderFile
					:upload="upload"
					:showTitle="showTitle"
					:defaultFileName="defaultFileName"
					:defaultToComplete="defaultToComplete"
					@uploading="isUploading = true"
					@fileSelected="fileSelected"
					@complete="complete"
					@failed="failed"
					:scope="scope"
				>
					<template #right>
						<DangerButton
							@click="() => {
								uploadStore.removeUpload(upload.id, scope);
								if (uploadStore.getUploads(scope).length === 0) resetUpload();
								emit('removed', upload)
							}"
							type="button"
							class="text-xs"
							size="sm"
						>
							Remove
						</DangerButton>
					</template>
				</UploaderFile>
			</div>
		</div>
	</div>
</template>
