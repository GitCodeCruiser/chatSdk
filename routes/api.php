<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ChatController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\UserAuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(UserAuthController::class)->group(function () {
    Route::post('signup', 'signUp');
    Route::post('login', 'login');
});

Route::middleware(['auth:api'])->group(function () {
    Route::controller(UserAuthController::class)->group(function () {
        Route::post('logout', 'logout');
    });

    Route::controller(ContactController::class)->group(function () {
        Route::get('contact-list', 'contactList');
        Route::post('search-contact', 'searchContact');
        Route::post('add-to-contact-list', 'addToContactList');
    });

    Route::prefix('chat')->controller(ChatController::class)->group(function () {
        Route::post('create-room', 'createRoom');
        Route::post('send-message', 'sendMessage');
        Route::post('get-message', 'getMessages');
        Route::post('users', 'getChatRoomUsers');
        Route::post('read', 'readMessage');
        Route::delete('delete/{messageId}', 'deleteMessage');
    });
});