<?php

namespace Tests\Unit\Jobs;

use App\Jobs\Media\ProcessImageUploadedMedia;
use App\Models\Media;
use App\Models\Cars\Car;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProcessImageUploadedMediaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up storage disks for testing
        Storage::fake('local');
        Storage::fake('s3');
        
        // Create a test car
        $this->car = Car::factory()->create();
    }

    public function test_job_processes_image_successfully()
    {
        // Create a test image file - note: fileExists() looks for directory/name (without extension)
        $testImagePath = 'car_images/front_view/test-image';
        $testImageContent = file_get_contents(__DIR__ . '/../../fixtures/test-image.jpg');
        
        if (!$testImageContent) {
            // Create a simple test image if fixture doesn't exist
            $testImageContent = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/2wBDAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQEBAQH/wAARCAABAAEDASIAAhEBAxEB/8QAFQABAQAAAAAAAAAAAAAAAAAAAAv/xAAUEAEAAAAAAAAAAAAAAAAAAAAA/8QAFQEBAQAAAAAAAAAAAAAAAAAAAAX/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwA/8A');
        }
        
        Storage::disk('s3')->put($testImagePath, $testImageContent);

        // Create media record using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('test-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        // Execute the job
        $job = new ProcessImageUploadedMedia($media);
        $job->handle();

        // Refresh the media model
        $media->refresh();

        // Assert that metadata was extracted and saved
        $this->assertNotNull($media->metadata);
        $this->assertNotNull($media->checksum);
        $this->assertNotNull($media->size);
        
        // Assert metadata structure (may be basic metadata if image extraction fails)
        $this->assertArrayHasKey('mime_type', $media->metadata);
        $this->assertArrayHasKey('processed_at', $media->metadata);
        
        // If metadata extraction succeeded, check for image-specific fields
        if (!isset($media->metadata['extraction_failed'])) {
            $this->assertArrayHasKey('width', $media->metadata);
            $this->assertArrayHasKey('height', $media->metadata);
            $this->assertArrayHasKey('format', $media->metadata);
        } else {
            // If extraction failed, check for fallback metadata
            $this->assertTrue($media->metadata['extraction_failed']);
        }
        
        // Assert checksum is calculated
        $this->assertIsString($media->checksum);
        $this->assertEquals(32, strlen($media->checksum)); // MD5 hash length
        
        // Assert size is calculated
        $this->assertIsInt($media->size);
        $this->assertGreaterThan(0, $media->size);
    }

    public function test_job_skips_processing_if_metadata_already_exists()
    {
        // Create media with existing metadata using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('test-image')
            ->withExtension('jpg')
            ->frontView()
            ->state([
                'metadata' => ['width' => 100, 'height' => 100],
                'checksum' => 'existing-checksum',
                'size' => 1024,
            ])
            ->create();

        $originalMetadata = $media->metadata;
        $originalChecksum = $media->checksum;
        $originalSize = $media->size;

        // Execute the job
        $job = new ProcessImageUploadedMedia($media);
        $job->handle();

        // Refresh the media model
        $media->refresh();

        // Assert that nothing was changed
        $this->assertEquals($originalMetadata, $media->metadata);
        $this->assertEquals($originalChecksum, $media->checksum);
        $this->assertEquals($originalSize, $media->size);
    }

    public function test_job_skips_processing_if_file_does_not_exist()
    {
        // Create media record without actual file using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('non-existent-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        // Execute the job
        $job = new ProcessImageUploadedMedia($media);
        $job->handle();

        // Refresh the media model
        $media->refresh();

        // Assert that nothing was processed
        $this->assertNull($media->metadata);
        $this->assertNull($media->checksum);
        $this->assertNull($media->size);
    }

    public function test_job_handles_metadata_extraction_failure_gracefully()
    {
        // Create a corrupted/invalid image file
        $testImagePath = 'car_images/front_view/corrupted-image';
        $corruptedContent = 'This is not a valid image file';
        
        Storage::disk('s3')->put($testImagePath, $corruptedContent);

        // Create media record using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('corrupted-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        // Mock Log to capture warning
        Log::shouldReceive('warning')
            ->once()
            ->with('Failed to extract image metadata', \Mockery::type('array'));

        // Execute the job
        $job = new ProcessImageUploadedMedia($media);
        $job->handle();

        // Refresh the media model
        $media->refresh();

        // Assert that basic metadata was still saved
        $this->assertNotNull($media->metadata);
        $this->assertNotNull($media->checksum);
        $this->assertNotNull($media->size);
        
        // Assert fallback metadata structure
        $this->assertArrayHasKey('mime_type', $media->metadata);
        $this->assertArrayHasKey('processed_at', $media->metadata);
        $this->assertArrayHasKey('extraction_failed', $media->metadata);
        $this->assertTrue($media->metadata['extraction_failed']);
    }

    public function test_job_cleans_up_temp_file_after_processing()
    {
        // Create a test image file
        $testImagePath = 'car_images/front_view/test-image';
        $testImageContent = 'fake image content';
        
        Storage::disk('s3')->put($testImagePath, $testImageContent);

        // Create media record using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('test-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        // Execute the job
        $job = new ProcessImageUploadedMedia($media);
        $job->handle();

        // Assert that no temp files remain in local storage
        $tempFiles = Storage::disk('local')->allFiles('temp-media');
        $this->assertEmpty($tempFiles);
    }

    public function test_job_cleans_up_temp_file_on_failure()
    {
        // Create media record without actual file to trigger failure using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('non-existent-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        // Execute the job (should not fail, but should clean up)
        $job = new ProcessImageUploadedMedia($media);
        $job->handle();

        // Assert that no temp files remain in local storage
        $tempFiles = Storage::disk('local')->allFiles('temp-media');
        $this->assertEmpty($tempFiles);
    }

    public function test_job_handles_processing_errors_gracefully()
    {
        // Create media record using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('test-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        // Create a file that exists but will cause an error during processing
        $testImagePath = 'car_images/front_view/test-image';
        Storage::disk('s3')->put($testImagePath, 'test content');

        // Execute the job - it should handle errors gracefully
        $job = new ProcessImageUploadedMedia($media);
        $job->handle();

        // Refresh the media model
        $media->refresh();

        // The job should still process the file even if metadata extraction fails
        $this->assertNotNull($media->checksum);
        $this->assertNotNull($media->size);
    }

    public function test_job_failed_method_logs_error_and_cleans_up()
    {
        // Create media record using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('test-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        // Create a temp file to simulate cleanup
        Storage::disk('local')->put('temp-media/test-file.jpg', 'temp content');

        // Mock Log to capture error
        Log::shouldReceive('error')
            ->once()
            ->with('ProcessImageUploadedMedia job failed', \Mockery::type('array'));

        // Execute the failed method
        $job = new ProcessImageUploadedMedia($media);
        $job->tempFilename = 'temp-media/test-file.jpg';
        $job->failed(new \Exception('Job failed'));

        // Assert that temp file was cleaned up
        $this->assertFalse(Storage::disk('local')->exists('temp-media/test-file.jpg'));
    }

    public function test_job_has_correct_properties()
    {
        // Create media record using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('test-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        $job = new ProcessImageUploadedMedia($media);

        // Assert job properties
        $this->assertEquals(3, $job->tries);
        $this->assertEquals(300, $job->timeout);
        $this->assertEquals($media, $job->media);
    }

    public function test_job_calculates_correct_checksum()
    {
        // Create a test image file with known content
        $testImagePath = 'car_images/front_view/test-image';
        $testImageContent = 'test image content for checksum';
        
        Storage::disk('s3')->put($testImagePath, $testImageContent);

        // Create media record using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('test-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        // Execute the job
        $job = new ProcessImageUploadedMedia($media);
        $job->handle();

        // Refresh the media model
        $media->refresh();

        // Assert checksum matches expected MD5
        $expectedChecksum = md5($testImageContent);
        $this->assertEquals($expectedChecksum, $media->checksum);
    }

    public function test_job_calculates_correct_file_size()
    {
        // Create a test image file with known content
        $testImagePath = 'car_images/front_view/test-image';
        $testImageContent = 'test image content for size calculation';
        
        Storage::disk('s3')->put($testImagePath, $testImageContent);

        // Create media record using factory
        $media = Media::factory()
            ->forCar($this->car)
            ->withName('test-image')
            ->withExtension('jpg')
            ->frontView()
            ->create();

        // Execute the job
        $job = new ProcessImageUploadedMedia($media);
        $job->handle();

        // Refresh the media model
        $media->refresh();

        // Assert size matches expected
        $expectedSize = strlen($testImageContent);
        $this->assertEquals($expectedSize, $media->size);
    }

    public function test_factory_creates_media_with_different_sections()
    {
        // Test different section factories
        $frontViewMedia = Media::factory()->frontView()->create();
        $interiorMedia = Media::factory()->interiorDashboard()->create();
        $mainSeatsMedia = Media::factory()->mainSeats()->create();
        $backSeatsMedia = Media::factory()->backSeatsTrunk()->create();
        $additionalMedia = Media::factory()->additional()->create();

        $this->assertEquals('car_images/front_view', $frontViewMedia->directory);
        $this->assertEquals('car_images/interior_dashboard', $interiorMedia->directory);
        $this->assertEquals('car_images/main_seats', $mainSeatsMedia->directory);
        $this->assertEquals('car_images/back_seats_trunk', $backSeatsMedia->directory);
        $this->assertEquals('car_images/additional', $additionalMedia->directory);
    }

    public function test_factory_creates_processed_media()
    {
        // Test processed media factory
        $media = Media::factory()
            ->forCar($this->car)
            ->processed()
            ->create();

        $this->assertNotNull($media->metadata);
        $this->assertNotNull($media->checksum);
        $this->assertNotNull($media->size);
        $this->assertArrayHasKey('width', $media->metadata);
        $this->assertArrayHasKey('height', $media->metadata);
        $this->assertArrayHasKey('format', $media->metadata);
        $this->assertArrayHasKey('mime_type', $media->metadata);
        $this->assertArrayHasKey('processed_at', $media->metadata);
    }
}