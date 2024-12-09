<?php

use Illuminate\Support\Facades\Route;
use App\Models\Project;

Route::get('/', function () {
    return redirect('/admin');
});

require __DIR__ . '/auth.php';

// Route::get('/projects', function () {
//     return response()->json(Project::all());
// });
