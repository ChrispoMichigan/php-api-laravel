<?php

use App\Http\Controllers\Api\SocialPlatformController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserSocialLinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'success' => true,
        'message' => 'API en Funcionamiento',
    ], 200);
});

Route::apiResource('users', UserController::class);
Route::apiResource('social-platforms', SocialPlatformController::class);
Route::apiResource('users.social-links', UserSocialLinkController::class)->shallow();
