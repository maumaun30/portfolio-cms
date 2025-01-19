<?php

use App\Helpers\MediaHelper;
use Illuminate\Http\Request;
use Awcodes\Curator\Models\Media;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProjectController;
use App\Models\Menu;

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

Route::get('/page/{filamentFabricatorPage?}', [PageController::class, 'getPageData']);
Route::get('/media/{id}', [MediaHelper::class, 'getMedia']);


Route::get('/search-models', function (\Illuminate\Http\Request $request) {
    $query = $request->get('query', '');

    $results = Menu::where('name', 'like', '%' . $query . '%')
        ->take(10)
        ->get(['id', 'name']);

    return response()->json($results);
});
