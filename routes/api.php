<?php

use App\Http\Controllers\MailingListController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

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

Route::controller(WebsiteController::class)->group(function () {
    Route::post('/websites', 'store');
    Route::get('/websites', 'show');
    Route::get('/websites/{id}', 'show');
    Route::match(['post', 'put'],'/websites/{id}', 'update');
});

Route::controller(PostController::class)->group(function() {
    
    Route::post('/websites/{websiteId}/posts', 'store');

    # Direct path for posts
    Route::get('/posts', 'show')->name('post.show');
    Route::get('/posts/{postId}', 'show')->name('post.show');

    # The website path for posts
    Route::get('/websites/{websiteId}/posts', 'show');
    Route::get('/websites/{websiteId}/posts/{postId}', 'show');
    
    # The update
    Route::match(['put', 'post'],'/posts/{postId}', 'update')->name('post.update');;
    Route::match(['put', 'post'],'/websites/{websiteId}/posts/{postId}', 'update');
});

Route::controller(MailingListController::class)->group(function() {
    
    // New subscriber
    Route::post('/websites/{websiteId}/subscribe', 'store');

    // Update your name providing email as a query
    Route::put('/websites/{websiteId}/subscribe', 'update');
    
    // Show your name & subscription date on record (and maybe the number of notifications sent).
    // providing email as a query
    Route::get('/websites/{websiteId}/subscriber', 'show');

    // Remove the subscription of this user providing email as a query
    Route::delete('/websites/{websiteId}/subscriber', 'destroy');
});