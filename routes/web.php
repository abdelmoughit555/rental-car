<?php

use App\Http\Controllers\Cars\CarCreationController;
use App\Http\Controllers\Cars\CarEditorController;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
    ]);
});

// S3 Signature route for file uploads (no authentication needed)
Route::get('/sign', [App\Http\Controllers\S3SignatureController::class, 'get']);
Route::post('/s3/presign', function (Request $req) {
    $req->validate([
        'key'          => 'required|string',                 // e.g. "artwork/uuid"
        'content_type' => 'nullable|string',                 // e.g. "image/jpeg"
    ]);

    $bucket = env('AWS_BUCKET');
    $endpoint = env('AWS_ENDPOINT');                         // http://192.168.100.74:8000
    $region = env('AWS_DEFAULT_REGION', 'us-east-1');

    $client = new S3Client([
        'version'                 => 'latest',
        'region'                  => $region,
        'endpoint'                => $endpoint,
        'use_path_style_endpoint' => true,
        'credentials'             => [
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ],
    ]);

    $key  = $req->string('key')->toString();
    $type = $req->string('content_type')->toString() ?: 'application/octet-stream';

    // Build a signed PUT URL. These headers MUST be sent in the actual PUT.
    $params = [
        'Bucket'      => $bucket,
        'Key'         => $key,
        'ContentType' => $type,
        // 'ACL'      => 'public-read', // uncomment if you want public objects by default
    ];

    $cmd     = $client->getCommand('PutObject', $params);
    $request = $client->createPresignedRequest($cmd, '+15 minutes');

    return response()->json([
        'url'     => (string) $request->getUri(),
        'headers' => [
            'Content-Type' => $type, // send this EXACTLY on the PUT
        ],
        'bucket'  => $bucket,
        'key'     => $key,
        // For convenience (if objects are public):
        'object_url' => rtrim($endpoint, '/')."/{$bucket}/{$key}",
    ]);
});
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix('cars')->group(function() {
        Route::get('/create', [CarCreationController::class, 'index'])->name('cars.create');

        Route::prefix('/{car}')->group(function() {
            Route::get('/information', [CarEditorController::class, 'information'])->name('cars.car.information');
            Route::get('/availability', [CarEditorController::class, 'availability'])->name('cars.car.availability');
            Route::get('/pricing', [CarEditorController::class, 'pricing'])->name('cars.car.pricing');
            Route::get('/features', [CarEditorController::class, 'features'])->name('cars.car.features');
            Route::get('/images', [CarEditorController::class, 'images'])->name('cars.car.images');
        });
    });
});
