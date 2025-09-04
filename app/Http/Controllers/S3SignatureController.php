<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class S3SignatureController extends Controller
{
    /**
     * @return Application|ResponseFactory|Response
     *
     * @throws ValidationException
     */
    public function get(Request $request): Response
    {
        $request->validate([
            'datetime' => 'required|date',
            'to_sign' => 'required|string',
        ]);

        $toSign  = $request->query('to_sign');      // exact string Evaporate sends
        $amzDate = $request->query('datetime');     // e.g. 20250828T175942Z
        if (!$toSign || !$amzDate || strlen($amzDate) < 8) {
            return response('Missing params', 400);
        }
    
        $secret = env('AWS_SECRET_ACCESS_KEY');            // secretkey
        $region = env('AWS_DEFAULT_REGION', 'us-east-1');  // must match MinIO
        $date   = substr($amzDate, 0, 8);                  // YYYYMMDD
    
        $kDate    = hash_hmac('sha256', $date, 'AWS4'.$secret, true);
        $kRegion  = hash_hmac('sha256', $region, $kDate, true);
        $kService = hash_hmac('sha256', 's3', $kRegion, true);
        $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
    
        $signature = hash_hmac('sha256', $toSign, $kSigning); // HEX
        return response($signature, 200)->header('Content-Type', 'text/plain');
    }
}
