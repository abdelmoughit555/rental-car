<?php

namespace App\Jobs\Media;

use App\Models\Media;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessImageUploadedMedia implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 300;

    /**
     * Create a new job instance.
     */
    public function __construct(public Media $media){}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Skip if metadata already exists or if file doesn't exist
        if ($this->media->metadata !== null || !$this->fileExists()) {
            return;
        }

        try {
            $this->downloadFileLocally();
            $localPath = Storage::disk('local')->path($this->tempFilename);

            $checksum = md5_file($localPath);
            $size = filesize($localPath);
            $metadata = $this->extractMetadata($localPath);

            $this->media->update([
                'checksum' => $checksum,
                'size' => $size,
                'metadata' => $metadata,
            ]);
        } catch (Throwable $e) {
            Log::error("Failed to process uploaded image", [
                'media_id' => $this->media->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        } finally {
            $this->cleanupTempFile();
        }
    }

        /**
     * Handle job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("ProcessImageUploadedMedia job failed", [
            'media_id' => $this->media->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        $this->cleanupTempFile();
    }

    private function fileExists(): bool
    {
        return Storage::disk($this->media->disk)->exists($this->media->directory . '/' . $this->media->name);
    }

        /**
     * Download the file to local storage for processing.
     */
    private function downloadFileLocally(): void
    {
        $this->tempFilename = 'temp-media/' . Str::uuid()->toString() . '.' . $this->media->extension;
        
        $fileContents = Storage::disk($this->media->disk)->get($this->media->directory . '/' . $this->media->name);
        Storage::disk('local')->put($this->tempFilename, $fileContents);
    }

        /**
     * Extract image metadata.
     */
    private function extractMetadata(string $localPath): array
    {
        try {
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($localPath);
            
            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'format' => strtolower($image->origin()->mediaType()),
                'mime_type' => mime_content_type($localPath),
                'processed_at' => now()->toISOString(),
            ];
        } catch (Throwable $e) {
            dd($e);
            Log::warning("Failed to extract image metadata", [
                'media_id' => $this->media->id,
                'error' => $e->getMessage()
            ]);
            
            // Return basic metadata if extraction fails
            return [
                'mime_type' => mime_content_type($localPath),
                'processed_at' => now()->toISOString(),
                'extraction_failed' => true
            ];
        }
    }

    /**
     * Clean up the temporary file.
     */
    private function cleanupTempFile(): void
    {
        if ($this->tempFilename && Storage::disk('local')->exists($this->tempFilename)) {
            Storage::disk('local')->delete($this->tempFilename);
        }
    }
}
