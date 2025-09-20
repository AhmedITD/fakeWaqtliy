<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $docsPath = base_path('docs/api_docs.html');
    
    if (file_exists($docsPath)) {
        return response()->file($docsPath);
    }
    
    return response()->json([
        'message' => 'Welcome to Waqitly API',
        'documentation' => '/api/docs',
        'api_base' => '/api/v1'
    ]);
});

// Alternative route for documentation
Route::get('/api/docs', function () {
    $docsPath = base_path('docs/api_docs.html');
    
    if (file_exists($docsPath)) {
        return response()->file($docsPath);
    }
    
    return response()->json([
        'error' => 'Documentation not found'
    ], 404);
});
