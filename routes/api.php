<?php

use Illuminate\Http\Request;
use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;

// Route::get('/api-token', function (Request $request) {
//     // Return the API token (you can fetch it from environment variables or a database)
//     return response()->json([
//         'api_token' => env('NEXT_PUBLIC_API_TOKEN'),
//     ]);
// });

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('projects', ProjectController::class);

Route::get('media/{id}', function ($mediaId) {
    $media = Media::find($mediaId);

    if ($media) {
        return Storage::url($media->path);
    }

    return null;
});

Route::get('/page/{filamentFabricatorPage?}', [PageController::class, 'getPageData']);
