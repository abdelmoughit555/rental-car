<?php

namespace App\Jobs\Media;

use App\Models\Media;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Throwable;

class ProcessCarImageDerivatives implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $timeout = 600; // 10 minutes for comprehensive processing
    
    private $tempFilename;

    /**
     * Car listing image processing spec
     */
    private const RESPONSIVE_WIDTHS = [320, 640, 960, 1280, 1600, 1920];
    private const MAX_LONG_EDGE = 4096;
    private const MIN_WIDTH = 1600;
    private const JPEG_QUALITY = 82;
    private const WEBP_QUALITY = 80;
    private const THUMB_SIZE = 200;
    private const CARD_ASPECT = [600, 400]; // 3:2
    private const HERO_ASPECT = [1920, 1080]; // 16:9

    /**
     * Create a new job instance.
     */
    public function __construct(public Media $media) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Skip if derivatives already exist or if file doesn't exist
        if ($this->media->derivatives !== null || !$this->fileExists()) {
            return;
        }

        try {
            $this->downloadFileLocally();
            $localPath = Storage::disk('local')->path($this->tempFilename);

            // Validate image meets car listing requirements
            $this->validateImage($localPath);

            // Process the image according to car listing spec
            $derivatives = $this->processImageDerivatives($localPath);
            $metadata = $this->extractEnhancedMetadata($localPath);

            $this->media->update([
                'derivatives' => $derivatives,
                'metadata' => array_merge($this->media->metadata ?? [], $metadata),
                'processed_at' => now(),
            ]);

            Log::info("Car image derivatives processed successfully", [
                'media_id' => $this->media->id,
                'derivatives_count' => count($derivatives)
            ]);

        } catch (Throwable $e) {
            Log::error("Failed to process car image derivatives", [
                'media_id' => $this->media->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
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
        Log::error("ProcessCarImageDerivatives job failed", [
            'media_id' => $this->media->id,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);

        $this->cleanupTempFile();
    }

    /**
     * Validate image meets car listing requirements
     */
    private function validateImage(string $localPath): void
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($localPath);
        
        // Check minimum width requirement
        if ($image->width() < self::MIN_WIDTH) {
            throw new \Exception("Image width must be at least " . self::MIN_WIDTH . "px. Current: {$image->width()}px");
        }

        // Check file size (should be under 10MB)
        $fileSize = filesize($localPath);
        if ($fileSize > 10 * 1024 * 1024) {
            throw new \Exception("Image file size must be under 10MB. Current: " . round($fileSize / (1024 * 1024), 2) . "MB");
        }
    }

    /**
     * Process image and generate all derivatives according to car listing spec
     */
    private function processImageDerivatives(string $localPath): array
    {
        $manager = new ImageManager(Driver::class);
        $image = $manager->read($localPath);
        $derivatives = [];

        // Cap at max 4096px on the long edge, strip EXIF, auto-rotate
        if ($image->width() > self::MAX_LONG_EDGE || $image->height() > self::MAX_LONG_EDGE) {
            $image->scaleDown(self::MAX_LONG_EDGE, self::MAX_LONG_EDGE);
        }

        // Note: orientate() method not available in v3, auto-rotation handled by browser

        // Generate responsive derivatives
        $derivatives = array_merge($derivatives, $this->generateResponsiveDerivatives($image));
        
        // Generate special crops
        $derivatives = array_merge($derivatives, $this->generateSpecialCrops($image));

        return $derivatives;
    }

    /**
     * Generate responsive width derivatives (320w, 640w, 960w, 1280w, 1600w, 1920w)
     */
    private function generateResponsiveDerivatives($image): array
    {
        $derivatives = [];

        foreach (self::RESPONSIVE_WIDTHS as $width) {
            $resizedImage = clone $image;
            $resizedImage->scale($width);

            // WebP version (primary format)
            $webpKey = $this->getDerivativeKey($width, 'webp');
            $webpData = $resizedImage->toWebp(self::WEBP_QUALITY);
            $this->uploadDerivative($webpKey, $webpData, 'image/webp');
            $derivatives[] = [
                'type' => 'responsive',
                'width' => $width,
                'format' => 'webp',
                'key' => $webpKey,
                'size' => strlen($webpData),
                'quality' => self::WEBP_QUALITY
            ];

            // JPEG fallback
            $jpegKey = $this->getDerivativeKey($width, 'jpeg');
            $jpegData = $resizedImage->toJpeg(self::JPEG_QUALITY);
            $this->uploadDerivative($jpegKey, $jpegData, 'image/jpeg');
            $derivatives[] = [
                'type' => 'responsive',
                'width' => $width,
                'format' => 'jpeg',
                'key' => $jpegKey,
                'size' => strlen($jpegData),
                'quality' => self::JPEG_QUALITY
            ];
        }

        return $derivatives;
    }

    /**
     * Generate special crops for car listings
     */
    private function generateSpecialCrops($image): array
    {
        $derivatives = [];

        // Square thumbnail (200×200) - for admin lists, avatars
        $thumbImage = clone $image;
        $thumbImage->cover(self::THUMB_SIZE, self::THUMB_SIZE);

        $thumbWebpKey = $this->getSpecialCropKey('thumb', 'webp');
        $thumbWebpData = $thumbImage->toWebp(self::WEBP_QUALITY);
        $this->uploadDerivative($thumbWebpKey, $thumbWebpData, 'image/webp');
        $derivatives[] = [
            'type' => 'special_crop',
            'crop' => 'thumb_square',
            'width' => self::THUMB_SIZE,
            'height' => self::THUMB_SIZE,
            'format' => 'webp',
            'key' => $thumbWebpKey,
            'size' => strlen($thumbWebpData),
            'quality' => self::WEBP_QUALITY
        ];

        // Card crop (3:2 aspect ratio) - for list/grid covers
        $cardImage = clone $image;
        $cardImage->cover(self::CARD_ASPECT[0], self::CARD_ASPECT[1]);

        $cardWebpKey = $this->getSpecialCropKey('card', 'webp');
        $cardWebpData = $cardImage->toWebp(self::WEBP_QUALITY);
        $this->uploadDerivative($cardWebpKey, $cardWebpData, 'image/webp');
        $derivatives[] = [
            'type' => 'special_crop',
            'crop' => 'card',
            'width' => self::CARD_ASPECT[0],
            'height' => self::CARD_ASPECT[1],
            'format' => 'webp',
            'key' => $cardWebpKey,
            'size' => strlen($cardWebpData),
            'quality' => self::WEBP_QUALITY
        ];

        // Hero crop (16:9 aspect ratio) - for banner layouts
        $heroImage = clone $image;
        $heroImage->cover(self::HERO_ASPECT[0], self::HERO_ASPECT[1]);

        $heroWebpKey = $this->getSpecialCropKey('hero', 'webp');
        $heroWebpData = $heroImage->toWebp(self::WEBP_QUALITY);
        $this->uploadDerivative($heroWebpKey, $heroWebpData, 'image/webp');
        $derivatives[] = [
            'type' => 'special_crop',
            'crop' => 'hero',
            'width' => self::HERO_ASPECT[0],
            'height' => self::HERO_ASPECT[1],
            'format' => 'webp',
            'key' => $heroWebpKey,
            'size' => strlen($heroWebpData),
            'quality' => self::WEBP_QUALITY
        ];

        return $derivatives;
    }

    /**
     * Extract enhanced metadata for car listings
     */
    private function extractEnhancedMetadata(string $localPath): array
    {
        try {
            $manager = new ImageManager(Driver::class);
            $image = $manager->read($localPath);
            $exif = $image->exif();
            
            return [
                'width' => $image->width(),
                'height' => $image->height(),
                'aspect_ratio' => round($image->width() / $image->height(), 3),
                'format' => strtolower($image->origin()->mediaType()),
                'mime_type' => mime_content_type($localPath),
                'file_size' => filesize($localPath),
                'exif' => $exif ? [
                    'camera_make' => $exif['Make'] ?? null,
                    'camera_model' => $exif['Model'] ?? null,
                    'date_taken' => $exif['DateTime'] ?? null,
                    'orientation' => $exif['Orientation'] ?? null,
                    'gps' => $this->extractGPSData($exif),
                ] : null,
                'processing_spec' => 'car_listing_v1',
                'processed_at' => now()->toISOString(),
                'derivatives_generated' => true,
            ];
        } catch (Throwable $e) {
            Log::warning("Failed to extract enhanced metadata", [
                'media_id' => $this->media->id,
                'error' => $e->getMessage()
            ]);
            
            return [
                'mime_type' => mime_content_type($localPath),
                'file_size' => filesize($localPath),
                'processing_spec' => 'car_listing_v1',
                'processed_at' => now()->toISOString(),
                'extraction_failed' => true
            ];
        }
    }

    /**
     * Extract GPS data from EXIF
     */
    private function extractGPSData(array $exif): ?array
    {
        if (!isset($exif['GPS'])) {
            return null;
        }

        $gps = $exif['GPS'];
        
        return [
            'latitude' => $this->getGPSCoordinate($gps['GPSLatitude'] ?? null, $gps['GPSLatitudeRef'] ?? null),
            'longitude' => $this->getGPSCoordinate($gps['GPSLongitude'] ?? null, $gps['GPSLongitudeRef'] ?? null),
            'altitude' => $gps['GPSAltitude'] ?? null,
        ];
    }

    /**
     * Convert GPS coordinate from EXIF format to decimal
     */
    private function getGPSCoordinate($coordinate, $ref): ?float
    {
        if (!$coordinate || !$ref) {
            return null;
        }

        $degrees = $coordinate[0] ?? 0;
        $minutes = $coordinate[1] ?? 0;
        $seconds = $coordinate[2] ?? 0;

        $result = $degrees + ($minutes / 60) + ($seconds / 3600);
        
        if (in_array($ref, ['S', 'W'])) {
            $result = -$result;
        }

        return $result;
    }

    /**
     * Generate derivative key for responsive images
     */
    private function getDerivativeKey(int $width, string $format): string
    {
        $pathInfo = pathinfo($this->media->name);
        return $this->media->directory . '/derivatives/' . $pathInfo['filename'] . '_' . $width . 'w.' . $format;
    }

    /**
     * Generate special crop key
     */
    private function getSpecialCropKey(string $cropType, string $format): string
    {
        $pathInfo = pathinfo($this->media->name);
        return $this->media->directory . '/derivatives/' . $pathInfo['filename'] . '_' . $cropType . '.' . $format;
    }

    /**
     * Upload derivative to storage
     */
    private function uploadDerivative(string $key, string $data, string $contentType): void
    {
        Storage::disk($this->media->disk)->put($key, $data, [
            'ContentType' => $contentType,
            'CacheControl' => 'public, max-age=31536000', // 1 year cache
        ]);
    }

    /**
     * Check if original file exists
     */
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
     * Clean up the temporary file.
     */
    private function cleanupTempFile(): void
    {
        if (isset($this->tempFilename) && Storage::disk('local')->exists($this->tempFilename)) {
            Storage::disk('local')->delete($this->tempFilename);
        }
    }
}
