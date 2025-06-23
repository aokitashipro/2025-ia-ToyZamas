<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//認証、ログイン、ログアウトのためのコントローラーを追加
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//ユーザデータの登録、ログイン
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//認証完了している場合のみ実行
Route::middleware('auth:sanctum')->group(function(){
    Route::post('/logout', [AuthController::class, 'logout']);
});

