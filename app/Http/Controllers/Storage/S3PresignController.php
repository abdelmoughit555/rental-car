<?php

namespace App\Http\Controllers\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Storage\PresignRequest;
use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;

class S3PresignController extends Controller
{
    public function __invoke(PresignRequest $request): JsonResponse
    {
        $bucket = env('AWS_BUCKET');
        $endpoint = env('AWS_ENDPOINT');
        $region = env('AWS_DEFAULT_REGION', 'us-east-1');

        $client = new S3Client([
            'version' => 'latest',
            'region' => $region,
            'endpoint' => $endpoint,
            'use_path_style_endpoint' => true,
            'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $key = $request->string('key')->toString();
        $type = $request->string('content_type')->toString() ?: 'application/octet-stream';

        $params = [
            'Bucket' => $bucket,
            'Key' => $key,
            'ContentType' => $type,
        ];

        $cmd = $client->getCommand('PutObject', $params);
        $presigned = $client->createPresignedRequest($cmd, '+15 minutes');

        return response()->json([
            'url' => (string) $presigned->getUri(),
            'headers' => [
                'Content-Type' => $type,
            ],
            'bucket' => $bucket,
            'key' => $key,
            'object_url' => rtrim((string) $endpoint, '/')."/{$bucket}/{$key}",
        ]);
    }
}


